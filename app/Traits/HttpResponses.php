<?php

namespace App\Traits;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HttpResponses
{
    public function response(
        $message,
        string|int $status,
        array|Model|Collection $data = [],
        int $totalPages = 0
    ) {
        $response = [
            'totalPages' =>  $totalPages,
            'message' => $message,
            'status' => $status,
            'data' => $data
        ];

        return response()->json($response, $status);
    }

    public function error($message, string|int $status, array|MessageBag $errors = [], array $data = [])
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'errors' => $errors,
            'data' => $data
        ], $status);
    }
}
