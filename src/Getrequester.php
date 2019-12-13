<?php
namespace Etsylib\Etsysdk;

class Getrequester{

    public static function performRequest($signedRequest, $params = array(), $is_wp = false){
        if ($is_wp) {
            return WpRequester::GET($signedRequest, $params);
        } else {
            return new Etsyresponse(CurlRequester::getCurlRequest($signedRequest));
        }
    }

    public static function getRawData($signedRequest, $params = array(), $is_wp = false){
        if ($is_wp) {
            return WpRequester::GET($signedRequest, $params);
        } else {
            return new Etsyresponse(CurlRequester::getRawData($signedRequest));
        }
    }
}
