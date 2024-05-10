<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BaseApiController extends Controller
{
    protected $message;
    protected $statusCode;

    public function sendResponse($data, $status = Response::HTTP_OK): JsonResponse
    {
        return $this->response($data, $status);
    }

    public function sendResourceResponse($resource, $statusCode = Response::HTTP_OK): JsonResponse
    {
        $jsonResponse = $resource->response()->getData();
        if (empty($jsonResponse->data)) {
            return response()->json(['data' => []], $statusCode);
        }
        return $this->response($jsonResponse, $statusCode);
    }

    public function sendError($message = '', $status = Response::HTTP_INTERNAL_SERVER_ERROR, $errors = []): JsonResponse
    {
        return $this->response([
            "message" => $message ?: __('alert.server_error'),
            ...$errors
        ], $status);
    }

    public function sendCsvResponse($file, $nameFile): BinaryFileResponse
    {
        return Excel::download($file, $nameFile, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function response($data, $status)
    {
        return response()->json($data, $status);
    }
}
