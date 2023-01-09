<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
        '_token',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (request()->is('api/*')) {
            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'code' => $exception->getStatusCode(),
                    'message' => 'Endpoint not found'
                ], $exception->getStatusCode());
            } elseif ($exception instanceof ModelNotFoundException) {
                $message = $exception->getMessage();
                if ($exception->getModel()) $message = last(explode('\\', $exception->getModel())) . ' not found';
                return response()->json([
                    'code' => $exception->getCode() ?: 404,
                    'message' => $message
                ], $exception->getCode() ?: 404);
            } elseif ($exception instanceof ValidationException) {
                return response()->json([
                    'code' => 422,
                    'message' => $exception->getMessage(),
                    'errors' => $exception->errors()
                ], 422);
            } elseif ($exception instanceof AuthenticationException) {
                return response()->json([
                    'code' => 401,
                    'message' => $exception->getMessage()
                ], 401);
            } elseif ($exception instanceof QueryException) {
                return response()->json([
                    'code' => 500,
                    'message' => $exception->getMessage(),
                ], 500);
            } elseif ($exception instanceof Exception) {
                return response()->json([
                    'code' => $exception->getCode() ?: 500,
                    'message' => $exception->getMessage()
                ], $exception->getCode() ?: 500);
            }
        }

        return parent::render($request, $exception);
    }
}
