<?php

namespace Etsylib\Etsysdk;

class Etsyresponse{

    public $response;
    public $success;
    public $http_code;
    public $error;

    public function __construct($res = NULL)
    {
        $this->success = $res['success'];
        $this->response = $res['response'];
        $this->http_code = $res['http_response_code'];
        $this->error = $res['error'];

    }

    public function getResponse(){

    }

}
