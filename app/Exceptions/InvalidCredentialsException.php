<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidCredentialsException extends Exception
{
    public function __construct(string $message = '', int $code = Response::HTTP_UNPROCESSABLE_ENTITY, ?Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
        $this->message = __('auth.failed');
    }

    public function render(): JsonResponse
    {
        return response()->json(['error' => __('auth.failed')], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
