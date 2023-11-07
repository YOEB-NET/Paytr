<?php

namespace Yoeb\Paytr\Exceptions;

use Exception;

class PaytrExceptions extends Exception
{
    protected $status;
    protected $error_code;
    protected $message;

    public function __construct($status = 400, $error_code = 'GENERIC_ERROR', $message = 'Bir hata oluştu!')
    {
        parent::__construct($message);
        $this->status = $status;
        $this->error_code = $error_code;
        $this->message = $message;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getErrorCode()
    {
        return $this->error_code;
    }

    
    public function __toString()
    {
        return json_encode([
            'status' => $this->getStatus(),
            'error_code' => $this->getErrorCode(),
            'message' => $this->getMessage()
        ]);
    }

    public function render()
    {
        return response()->json([
            'status' => $this->getStatus(),
            'error_code' => $this->getErrorCode(),
            'message' => $this->getMessage()
        ], $this->getStatus());
    }
}
