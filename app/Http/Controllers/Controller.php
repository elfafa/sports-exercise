<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Respond with JSON
     *
     * @param array $data
     * @param int $code
     * @param array $headers
     * @param int $encodingOptions
     * @return JsonResponse
     */
    protected function respondWithJson($data, $code = Response::HTTP_OK, $headers = [], $encodingOptions = 0)
    {
        return response()->json($data, $code, $headers, $encodingOptions);
    }
}
