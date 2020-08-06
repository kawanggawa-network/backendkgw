<?php

namespace App\Http\Controllers\Helper;

use Carbon\Carbon;

/**
 * Format response.
 */
class ResponseFormatter
{
    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [
        'meta' => [
            'code' => 200,
            'status' => 'success',
            'message' => null,
        ],
        'data' => null,
    ];

    /**
     * Give success response.
     */
    public static function success($data = null, $message = null)
    {
        self::$response['meta']['message'] = $message;
        self::$response['meta']['response_date'] = Carbon::now()->format('Y-m-d H:i:s');
        self::$response['data'] = $data;
        
        return response()->json(self::$response, self::$response['meta']['code']);
    }
    
    /**
     * Give error response.
     */
    public static function error($code = 400, $message = null)
    {
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['code'] = $code;
        self::$response['meta']['message'] = $message;
        self::$response['meta']['response_date'] = Carbon::now()->format('Y-m-d H:i:s');

        return response()->json(self::$response, self::$response['meta']['code']);
    }
}
