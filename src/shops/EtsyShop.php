<?php

namespace Etsylib\Etsysdk\shops;

use Etsylib\Etsysdk\WpRequester;
use Etsylib\Etsysdk\CurlRequester;

class EtsyShop
{

    public function getShopDetails($signedRequest, $params = array(), $is_wp = false)
    {
        if ($is_wp) {
            return WpRequester::GET($signedRequest, $params);
        } else {
            return new EtsyShopResponse(CurlRequester::getCurlRequest($signedRequest));
        }
    }

}

class EtsyShopResponse implements EtsyShopResponseInterface
{
    public $response;
    public $success;
    public $http_code;
    public $error;

    function __construct($res = NULL)
    {
        $this->success = $res['success'];
        $this->response = $res['response'];
        $this->http_code = $res['http_response_code'];
        $this->error = $res['error'];
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function getResponse()
    {
        return $this->response;
    }
}

interface EtsyShopResponseInterface
{
    public function getResponse();

    public function getSuccess();
}
