<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CartItemNotFoundException extends Exception
{
    public function __construct(string $message = '', int $code = Response::HTTP_UNPROCESSABLE_ENTITY, ?Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
        $this->message = __('exception.cart_item.not_found');
    }

    public function render(): JsonResponse
    {
        return response()->json(['error' => __('exception.cart_item.not_found')], Response::HTTP_NOT_FOUND);
    }
}
