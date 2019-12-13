<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
use Etsylib\Etsysdk\EtsyClient;

$OAUTHPARAMS = [
    'oauth_token' => $_REQUEST['oauth_token'],
    'oauth_verifier' => $_REQUEST['oauth_verifier'],
    'oauth_token_secret' => 'd5e2627d5a'
];

$invoker = new EtsyClient('zuth9qqhf92vzlw15lxardlo','ky34n9e6m4');
echo "<pre>";
print_r($invoker->getAccessToken($OAUTHPARAMS));
echo "</pre>";
exit;
