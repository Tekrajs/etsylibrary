<?php

namespace Etsylib\Etsysdk\users;

use Etsylib\Etsysdk\Etsyresponse;
use Etsylib\Etsysdk\CurlRequester;

class Users {
    public function __construct()
    {

    }

    public function getusers($signedRequest, $params = array(), $is_wp = false){
        if ($is_wp) {
            return WpRequester::GET($signedRequest, $params);
        } else {
            return new Etsyresponse(CurlRequester::getCurlRequest($signedRequest));
        }
    }
}
