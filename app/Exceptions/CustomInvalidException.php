<?php

namespace App\Exceptions;

use Exception;

class CustomInvalidException extends Exception
{
    public function render($request){
        $result = [
            'error_msg'  => $this->getMessage()
        ];
        return response()->json($result, 400);
    }
}
