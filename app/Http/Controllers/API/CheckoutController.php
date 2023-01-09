<?php

namespace App\Http\Controllers\API;

use App\Book;
use App\City;
use App\User;
use App\Provincy;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProvincyResource;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use App\Http\Requests\API\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Order;
use App\Traits\MidtransPayment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckoutController extends Controller
{
    use MidtransPayment;

    /**
     * Get all provincies
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllProvincies(): JsonResponse
    {
        $provincies = Provincy::with(['cities'])
            ->latest()
            ->get();
        $provincies = ProvincyResource::collection($provincies);

        return response()->json(['code' => 200, 'message' => 'Success', 'data' => $provincies], 200);
    }

    /**
     * Get all provincies
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCities(): JsonResponse
    {
        $cities = City::with(['provincy'])
            ->latest()
            ->get();
        $cities = CityResource::collection($cities);

        return response()->json(['code' => 200, 'message' => 'Success', 'data' => $cities], 200);
    }

    /**
     * Get all couriers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCouriers(): JsonResponse
    {
        $couriers = [
            ['id' => 1, 'name' => 'jne'],
            ['id' => 2, 'name' => 'tiki'],
            ['id' => 3, 'name' => 'pos']
        ];

        return response()->json(['code' => 200, 'message' => 'Success', 'data' => $couriers], 200);
    }

    /**
     * update a user's address
     *
     * @param  \App\Http\Requests\API\CheckoutRequest $request
     * @return  \Illuminate\Http\JsonResponse
     */
    public function shipping(CheckoutRequest $request): JsonResponse
    {
        $user = $this->getUser((int) request()->user()['id']);

        if (!$user->update($request->validated())) throw new \Exception('Failed to update shipping address', 500);

        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => new UserResource($user)
        ], 200);
    }

    /**
     * process the checkout cart and get the courier services
     *
     * @param  \App\Http\Requests\API\CheckoutRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(CheckoutRequest $request): JsonResponse
    {
        $request = $request->validated();
        $this->validateQuantity($request['cart']);
        $this->validateTheBooksAddress($request['cart']);
        $services = $this->getCourierServices($request, request()->user()['city_id']);

        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => [
                'cart' => $request['cart'],
                'services' => $services,
                'total' => $this->getTotal($request['cart'])
            ]
        ], 200);
    }

    /**
     * submit the checkout cart
     *
     * @param  \App\Http\Requests\API\CheckoutRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(CheckoutRequest $request)
    {
        $request = $request->validated();
        $this->validateQuantity($request['data'], true);
        $user = $this->getUser((int) request()->user()['id']);
        $services = $this->getCourierServices($request['data'], $user->city_id, true);
        $cost = $this->getServiceCost($request['data'], $services);

        try {
            DB::beginTransaction();
            $order = $this->storeCart($request['data'], (int) $user->id, $cost);
            $paymentLink = $this->createPaymentLink($order);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage() ?: 'Failed to store the cart', 500);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => ['payment_link' => $paymentLink]
        ]);
    }

    /**
     * get the history orders of a user
     *
     * @return \Illuminat\Http\JsonResponse
     */
    public function myOrder(): JsonResponse
    {
        $data = OrderResource::collection($this->getUserOrders(request()->user()['id']));
        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => $data
        ]);
    }
    /**
     * store the cart into database
     *
     * @param  array $data
     * @param  int $userId
     * @param  int $userId
     * @return \App\Order
     */
    private function storeCart(array $data, int $userId, int $cost): Order
    {
        $totalPrice = $cost;
        foreach ($data as $d) {
            foreach ($d['cart'] as $cart) {
                $bookOrder[$cart['id']] = ['quantity' => $cart['quantity']];
                $totalPrice += (int) $cart['price'] * $cart['quantity'];
            };
        }

        if (!$order = Order::create([
            'user_id' => $userId,
            'invoice_number' => 'LARABOOKS-' . date('dmy') . Str::random(15),
            'total_price' => $totalPrice,
            'status' => 'SUBMIT'
        ])) throw new \Exception('Failed to create new order', 500);

        $order->books()
            ->attach($bookOrder);

        return $this->updateTheBooksStock($order->invoice_number, false);
    }

    /**
     * get courier services
     *
     * @param  array $request
     * @param  int $userFrom
     * @param  bool $submit (Define for the checkout process or submit)
     * @return array
     */
    private function getCourierServices(array $data, int $userFrom, ?bool $submit = false): array
    {
        if ($submit) {
            foreach ($data as $d) {
                if (count($d['cart']) > 1) $this->validateTheBooksAddress($d['cart']);

                $params = [
                    'origin' => $d['cart'][0]['cityId'],
                    'destination' => $userFrom,
                    'weight' => $this->getTotal($d['cart'])['totalWeight'],
                    'courier' => $d['courier']
                ];

                $result[] = $this->fetchRajaOngkir($params);
            }
            return $result;
        } else {
            $params = [
                'origin' => $data['cart'][0]['cityId'],
                'destination' => $userFrom,
                'weight' => $this->getTotal($data['cart'])['totalWeight'],
                'courier' => $data['courier']
            ];

            return $this->fetchRajaOngkir($params);
        }
    }

    /**
     * fetch to rajaongkir service
     *
     * @param  array $data
     * @return array
     */
    private function fetchRajaOngkir(array $data): array
    {
        try {
            $result = RajaOngkir::ongkosKirim($data)
                ->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get courier services', 500);
        }
        return $result[0]['costs'];
    }

    /**
     * get costs of courier services
     *
     * @param  array $data
     * @param  array $services
     * @return int
     */
    private function getServiceCost(array $data, array $services): int
    {
        $cost = 0;
        foreach ($data as  $d) $selectedServices[] = $d['service'];

        foreach ($selectedServices as $key => $selectedService) {
            foreach ($services[$key] as $service) {
                if ($selectedService === $service['service']) {
                    $serviceCost = $service['cost'][0]['value'];
                    if ($serviceCost < 1) throw new \Exception('Service costs not available', 400);
                    $cost += $serviceCost;
                    break;
                }
            }
        }

        return $cost;
    }

    /**
     * get total quantity, weight, and price
     *
     * @param  array $carts
     * @return array
     */
    private function getTotal(array $carts): array
    {
        $totalQuantity = 0;
        $totalWeight = 0;
        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalQuantity += $cart['quantity'];
            $totalWeight += (int) $cart['weight'] * $cart['quantity'];
            $totalPrice += (int) $cart['price'] * $cart['quantity'];
        }
        return ['totalQuantity' => $totalQuantity, 'totalWeight' => $totalWeight, 'totalPrice' => $totalPrice];
    }

    /**
     * validate the books quantity
     *
     * @param  array $data
     * @param  bool $submit (Define for the checkout process or submit)
     * @return void
     */
    private function validateQuantity(array $data, ?bool $submit = false)
    {
        if ($submit) {
            foreach ($data as $data_key => $d) {
                foreach ($d['cart'] as $d_key => $cart) {
                    if ($cart['quantity'] > $this->getBook((int) $cart['id'])->stock)
                        throw ValidationException::withMessages([
                            "data.$data_key.cart.$d_key.quantity" => 'The quantity must be less then or equals stock'
                        ]);
                };
            };
        } else {
            foreach ($data as $key => $cart) {
                if ($cart['quantity'] > $this->getBook((int) $cart['id'])->stock)
                    throw ValidationException::withMessages([
                        "cart.$key.quantity" => 'The quantity must be less then or equals stock'
                    ]);
            }
        }
    }

    /**
     * validate the books address
     *
     * @param  array $carts
     * @return void
     */
    private function validateTheBooksAddress(array $carts)
    {
        $firstBookaddress = $carts[0]['cityId'];
        foreach ($carts as $cart) {
            if ($firstBookaddress !== $cart['cityId']) throw new \Exception('The books address must be the same', 400);
        }
    }

    /**
     * get the user orders
     *
     * @param  int $userId
     * @param  array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getUserOrders(int $userId): Collection
    {
        return Order::where('user_id', $userId)
            ->get();
    }

    /**
     * get a book's stock
     *
     * @param  int $id
     * @return \App\Book
     */
    private function getBook(int $id): Book
    {
        return Book::findOrFail($id);
    }

    /**
     * get a user
     *
     * @param  int $id
     * @return \App\User
     */
    private function getUser(int $id): User
    {
        return User::findOrFail($id);
    }
}
