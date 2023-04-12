<?php


    function successResponse($data, $message = '')
    {
        return response()->json([
            'success' => 200,
            'message' => $message,
            'data' => $data
        ]);
    }
    function errorResponse($message)
    {
        return response()->json([
            'status' => 400,
            'message' => $message,
        ],400);
    }
    