<?php

namespace App\Traits;

use App\Order;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

trait MidtransPayment
{
    /**
     * the list of available payments
     *
     * @var array
     */
    public $availablePayments = ['bri_va', 'bni_va', 'gopay', 'shopeepay'];

    /**
     * set midtrans configuration
     *
     * @return void
     */
    private function configuration()
    {
        Config::$serverKey = config('midtrans.midtrans_serverkey');
        Config::$isProduction = config('midtrans.midtrans_production');
        Config::$isSanitized = config('midtrans.midtrans_sanitized');
        Config::$is3ds = config('midtrans.midtrans_3ds');
    }

    /**
     * set data for payment credentials
     *
     * @param  \App\Order $transaction
     * @return array
     */
    private function setData(Order $transaction)
    {
        return [
            'transaction_details' => ['order_id' => $transaction->invoice_number, 'gross_amount' => (int) $transaction->total_price],
            'customer_details' => ['first_name' => $transaction->user->name, 'email' => $transaction->user->email],
            'enabled_payments' => $this->availablePayments,
            'vtweb' => []
        ];
    }

    /**
     * create (midtrans) payment link
     *
     * @param  \App\Order $transaction
     * @return mixed
     */
    public function createPaymentLink(Order $transaction)
    {
        $this->configuration();

        try {
            return Snap::createTransaction($this->setData($transaction))->redirect_url;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create payment link', 500);
        }
    }

    /**
     * handle data from midtrans
     *
     * @return mixed
     */
    public function notificationHandler()
    {
        $this->configuration();

        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get data from midtrans', 500);
        }

        $message = "The order with invoice number {$notification->order_id} is ";

        if (in_array($notification->transaction_status, ['deny', 'expire', 'cancel'])) {
            $status = 'FAILED';
            $message .= $status;
            $this->updateTheBooksStock($notification->order_id);
        }
        if ($notification->transaction_status === 'pending') {
            $status = 'PENDING';
            $message .= $status;
        }
        if ($notification->transaction_status === 'settlement') {
            $status = 'SUCCESS';
            $message .= $status;
        }

        if (!$this->updateOrderStatus($notification->order_id, $status)) throw new \Exception('Failed to update the status transaction', 500);

        return response()->json([
            'code' => 200,
            'message' => strtolower($message)
        ], 200);
    }

    /**
     * get the order
     *
     * @param  string $invoiceNumber
     * @return \App\Order
     */
    private function getTheOrder(string $invoiceNumber): Order
    {
        return Order::where('invoice_number', $invoiceNumber)
            ->firstOrFail();
    }

    /**
     * update the status order
     *
     * @param  string $invoiceNumber
     * @param  string $status
     * @return mixed
     */
    private function updateOrderStatus(string $invoiceNumber, string $status)
    {
        $order = $this->getTheOrder($invoiceNumber);
        return $order->update(['status' => $status]);
    }

    /**
     * update  the books stock
     *
     * @param  string $invoiceNumber
     * @param  bool $append (Define append or reduce the books stock)
     * @return mixed
     */
    private function updateTheBooksStock(string $invoiceNumber, ?bool $append = true)
    {
        $order = $this->getTheOrder($invoiceNumber);

        foreach ($order->books as $book) {
            $append ? $book->stock += $book->pivot->quantity : $book->stock -= $book->pivot->quantity;
            if (!$book->save()) throw new \Exception('Failed to update the books stock', 500);
        }

        return $append ? null : $order;
    }
}
