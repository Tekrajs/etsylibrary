<?php

namespace Etsylib\Etsysdk\listings;

use Etsylib\Etsysdk\CurlRequester;
use Etsylib\Etsysdk\WpRequester;
use Etsylib\Etsysdk\Etsyresponse;

class Products{

    public function listProducts($signedRequest, $params = array(), $is_wp = false){
        if ($is_wp) {
            return WpRequester::GET($signedRequest, $params);
        } else {
            return new Etsyresponse(CurlRequester::postCurlRequest($signedRequest, json_encode($params['productData'])));
        }
    }

    public function uploadProductFeatureImage($signedRequest, $params, $is_wp){
        if ($is_wp) {
            return WpRequester::GET($signedRequest, $params);
        } else {
            return new Etsyresponse(CurlRequester::postCurlRequest($signedRequest, 'imgdata'));
        }
    }
}
