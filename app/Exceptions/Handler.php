<?php

namespace App\Exceptions;

use App\Traits\HandlesErrorMessage;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class Handler extends Exception
{
    use HandlesErrorMessage;

    public function handleError(Throwable $throwable, Request $request)
    {
        $error = $this->handle($throwable);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $error->message,
                'details' => $error->details ?? null,
            ], $error->code);
        } else {
            return response()->view('errors.custom', [
                'status' => $error->code,
                'message' => $error->message,
                'details' => $error->details ?? null,
            ]);
        }
    }
}
