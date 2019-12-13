<?php

namespace Etsylib\Etsysdk;
/*
 * (c) Tekraj Shrestha <shrestharj64@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

// If you don't to add a custom vendor folder, then use the simple class
// namespace Etsy_library

use Etsylib\Etsysdk\oauth\OAuthConsumer;
use Etsylib\Etsysdk\oauth\OAuthSignatureMethod_HMAC_SHA1;
use Etsylib\Etsysdk\oauth\OAuthRequest;

class EtsyClient
{
    private $base_url = "https://openapi.etsy.com/v2";
    private $base_path = "/private";
    private $oauth = null;
    private $authorized = false;
    private $debug = true;
    private $consumer_key = "";
    private $consumer_secret = "";

    private $signatureMethod;
    private $oauthConsumer;
    private $oauthToken;
    private $oauthMethod = 'GET';

    public $loginTokens;

    protected $login_url;

    function __construct($consumer_key, $consumer_secret)
    {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
    }

    private function oauthEnticate($api_key, $secret_key, $params)
    {
        $this->signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
        $this->oauthConsumer = new OAuthConsumer($api_key, $secret_key, $params);
        return true;
    }

    public function getRequestToken(array $extra = array())
    {
        $url = $this->base_url . "/oauth/request_token";
        $params = array();
        if (isset($extra['scope']) && !empty($extra['scope'])) {
            $params['scope'] = $extra['scope'];
        }
        if (isset($extra['callback']) && !empty($extra['callback'])) {
            $params['oauth_callback'] = $extra['callback'];
        }
        try {
            $this->oauthEnticate($this->consumer_key, $this->consumer_secret, array());
            $req_req = OAuthRequest::from_consumer_and_token($this->oauthConsumer, NULL, "GET", $url, $params);
            $req_req->sign_request($this->signatureMethod, $this->oauthConsumer, NULL);
            return $this->getParsedResponse(CurlRequester::getCurlRequest($req_req));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAccessToken($oauthsParams)
    {
        try {

            $params = array(
                'oauth_token' => $oauthsParams['oauth_token'],
                'oauth_verifier' => $oauthsParams['oauth_verifier'],
            );
            $oauthconsumer = new OAuthConsumer($oauthsParams['oauth_token'], $oauthsParams['oauth_token_secret']);
            $this->oauthEnticate($this->consumer_key, $this->consumer_secret, array());
            $OAUTH_REQUEST = OAuthRequest::from_consumer_and_token($this->oauthConsumer, NULL, "GET", $this->base_url . "/oauth/access_token", $params);
            $OAUTH_REQUEST->sign_request($this->signatureMethod, $this->oauthConsumer, $oauthconsumer);

            $token_req = CurlRequester::getCurlRequest($OAUTH_REQUEST);
            return $token_req;
        } catch (\Exception $e) {
            return $e;
        }
        return null;
    }

    public function authorizeRequest($path, $oauthParams, $params = array(), $method = null)
    {
        if ($method === null) {
            $method = $this->oauthMethod;
        }
        $this->oauthEnticate($this->consumer_key, $this->consumer_secret, array());
        $OAUTHCONSUMER = new OAuthConsumer($oauthParams['oauth_token'], $oauthParams['oauth_token_secret']);
        $OAUTH_REQUEST = OAuthRequest::from_consumer_and_token($this->oauthConsumer, $OAUTHCONSUMER, $method, $this->base_url . $this->base_path . $path, $params);
        $OAUTH_REQUEST->sign_request($this->signatureMethod, $this->oauthConsumer, $OAUTHCONSUMER);
        return $OAUTH_REQUEST;

    }

    public function getParsedResponse($params)
    {
        $position = strpos($params, '=');
        $data = substr($params, $position + 1);
        $data = urldecode($data);
        $exploded = explode("&", $data);
        $this->loginTokens = new EtsyClientResponder();
        $this->loginTokens->login_url = $data;
        $this->loginTokens->url = $exploded[0];
        $this->loginTokens->oauth_token = str_replace('oauth_token=', "", $exploded[3]);
        $this->loginTokens->oauth_token_secret = str_replace('oauth_token_secret=', "", $exploded[4]);
        $this->loginTokens->oauth_consumer_key = str_replace('oauth_consumer_key=', "", $exploded[6]);
        $this->loginTokens->oauth_callback = str_replace('oauth_callback=', "", $exploded[7]);
        return $this->loginTokens;
    }

}

class CurlRequester
{

    public function __construct()
    {

    }

    public static function getRawData($signedRequest){
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $signedRequest,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
        );
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            $responseData = array(
                'success' => false,
                'error' => curl_error($ch),
                'http_response_code' => $httpCode,
                'response' => $response
            );
        }else{
            $responseData = array(
                'success' => true,
                'error' => curl_error($ch),
                'http_response_code' => $httpCode,
                'response' => $response
            );
        }
        curl_close($ch);
        return $responseData;
    }

    public static function getCurlRequest($signedRequest)
    {
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $signedRequest,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
        );
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            $responseData = array(
                'success' => false,
                'error' => curl_error($ch),
                'http_response_code' => $httpCode,
                'response' => json_decode($response) ? json_decode($response) : $response
            );
        }else{
            $responseData = array(
                'success' => true,
                'error' => curl_error($ch),
                'http_response_code' => $httpCode,
                'response' => json_decode($response) ? json_decode($response) : $response
            );
        }
        curl_close($ch);
        return $responseData;
    }

    public static function postCurlRequest($signedHTTPRequest, $postData)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $signedHTTPRequest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            $responseData = array(
                'success' => false,
                'error' => curl_error($ch),
                'http_response_code' => $httpCode,
                'response' => json_decode($response) ? json_decode($response) : $response
            );
        }else{
            $responseData = array(
                'success' => true,
                'error' => curl_error($ch),
                'http_response_code' => $httpCode,
                'response' => json_decode($response) ? json_decode($response) : $response
            );
        }
        curl_close($ch);

        return $responseData;
    }

}

class WpRequester
{

    public static function GET($signedRequest, $params = array())
    {
        return wp_remote_get($signedRequest, $params);
    }

    public static function POST($signedRequest, $params)
    {
        return wp_remote_post($signedRequest, $params);
    }

    public static function REQUEST($signedRequest, $params)
    {

    }

}

class EtsyRequestException extends \Exception
{
    private $lastResponse;
    private $lastResponseInfo;
    private $lastResponseHeaders;
    private $debugInfo;
    private $exception;
    private $params;

    function __construct($exception, $oauth, $params = array())
    {
        $this->lastResponse = $oauth->getLastResponse();
        $this->lastResponseInfo = $oauth->getLastResponseInfo();
        $this->lastResponseHeaders = $oauth->getLastResponseHeaders();
        $this->debugInfo = $oauth->debugInfo;
        $this->exception = $exception;
        $this->params = $params;
        parent::__construct($this->buildMessage(), 1, $exception);
    }

    private function buildMessage()
    {
        return $this->exception->getMessage() . ": " .
            print_r($this->params, true) .
            print_r($this->lastResponse, true) .
            print_r($this->lastResponseInfo, true) .
            // print_r($this->lastResponseHeaders, true) .
            print_r($this->debugInfo, true);
    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function getLastResponseInfo()
    {
        return $this->lastResponseInfo;
    }

    public function getLastResponseHeaders()
    {
        return $this->lastResponseHeaders;
    }

    public function getDebugInfo()
    {
        return $this->debugInfo;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: " . $this->buildMessage();
    }
}

class EtsyClientResponder
{
    public $oauth_token_secret;
    public $oauth_consumer_key;
    public $oauth_token;
    public $oauth_callback;
}
