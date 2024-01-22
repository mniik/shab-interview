<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FileUploadException extends UploadException
{
    public function __construct(string $message = '', int $code = Response::HTTP_INTERNAL_SERVER_ERROR, ?Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
        $this->message = __('exceptions.upload.failed');
    }

    public function render(): JsonResponse
    {
        return response()->json(['error' => __('exceptions.upload.failed')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
