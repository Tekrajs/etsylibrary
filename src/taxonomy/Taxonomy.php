<?php

namespace Etsylib\Etsysdk\taxonomy;
use Etsylib\Etsysdk\CurlRequester;

class Taxonomy {

    public function getTaxonomyLists($signedRequest, $params = array(), $is_wp = false){
        if ($is_wp) {
            return WpRequester::GET($signedRequest, $params);
        } else {
            return new Etsyresponse(CurlRequester::getCurlRequest($signedRequest));
        }
    }

}
