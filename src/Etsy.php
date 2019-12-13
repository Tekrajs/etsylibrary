<?php

/*
 * (c) Tekraj Shrestha <shrestharj64@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

// If you don't to add a custom vendor folder, then use the simple class
// namespace Etsy_library;
namespace Etsylib\Etsysdk;
class Etsy{

    private $signatureMethod;
    private $oauthConsumer;
    private $oauthToken;


    public function __construct()
    {

        /*$this->signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
        $this->oauthConsumer = new OAuthConsumer($api_key, $secret_key);
        $this->oauthToken = new OAuthConsumer($oauth_token, $oauth_token_secret);*/

    }

    public function start($say = "Nothing to say"){
        return $say;
    }

    public function getListing($listing_id){

    }

    public function uploadListing($data){

    }

    public function updateListing($listing_id, $data){

    }

    public function deleteListing($listing_id){

    }
}
