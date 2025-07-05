<?php

namespace App\Traits;

use App\Exceptions\CustomException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use stdClass;
use Throwable;

trait HandlesErrorMessage
{
    public function handle(Throwable $exception, $default_message = 'An error occured')
    {
        $this->log($exception);

        $res = new stdClass();

        if ($exception instanceof ModelNotFoundException) {
            return $this->responseForModelNotFoundException($exception);
        }
        if ($exception instanceof ValidationException) {
            return $this->responseForValidationException($exception);
        }
        if ($exception instanceof AuthenticationException) {
            return $this->responseForAuthenticationException();
        }
        if ($exception instanceof AuthorizationException) {
            return $this->responseForAuthorizationException($exception);
        }
        if ($exception instanceof CustomException) {
            return $this->responseForCustomException($exception);
        }

        $res->message = $default_message;
        $res->code = Response::HTTP_INTERNAL_SERVER_ERROR;

        return $res;
    }

    public function log(Throwable $exception)
    {
        Log::error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            // 'trace' => $exception->getTrace(),
        ]);
    }

    public function responseForModelNotFoundException(ModelNotFoundException $e)
    {
        $id = [] !== $e->getIds() ? ' ' . implode(', ', $e->getIds()) : '.';
        $model = class_basename($e->getModel());
        $message = "{$model} with id {$id} not found Record not found!";

        $res = new stdClass();
        $res->message = $message;
        $res->code = Response::HTTP_NOT_FOUND;

        return $res;
    }

    public function responseForValidationException(ValidationException $exception)
    {
        $res = new stdClass();
        $res->message = $exception->validator->errors()->first();
        $res->code = Response::HTTP_NOT_FOUND;

        return $res;
    }

    public function responseForAuthenticationException()
    {
        $res = new stdClass();
        $res->message = 'Unauthenticated';
        $res->code = Response::HTTP_UNAUTHORIZED;

        return $res;
    }

    public function responseForAuthorizationException(AuthorizationException $exception)
    {
        $res = new stdClass();
        $res->message = 'Unauthorized';
        $res->code = Response::HTTP_UNAUTHORIZED;

        return $res;
    }

    public function responseForCustomException(CustomException $exception)
    {
        $res = new stdClass();
        $res->message = $exception->getMessage();
        $res->code = Response::HTTP_BAD_REQUEST;

        return $res;
    }
}
