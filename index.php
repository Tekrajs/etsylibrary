<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use Etsylib\Etsysdk\EtsyClient;
$callback = 'http://localhost/etsyLib/etsysdk/callback.php';
$scope = ''; // not necessary for now
$instance = new EtsyClient('zuth9qqhf92vzlw15lxardlo','ky34n9e6m4');
$response = $instance->getRequestToken(array('scope'=>$scope,'callback'=>$callback));

echo "<pre>";
print_r($response);
echo "</pre>";
exit;

