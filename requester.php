<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use Etsylib\Etsysdk\EtsyClient;
use Etsylib\Etsysdk\CurlRequester;
use Etsylib\Etsysdk\shops\EtsyShop;
use Etsylib\Etsysdk\users\Users;
use Etsylib\Etsysdk\Getrequester;
use Etsylib\Etsysdk\listings\Products;
$OAUTHPARAMS = [
    'oauth_token' => '98d15115737346ce47c2b3fdc9afc4',
    'oauth_token_secret' => 'a0f4d4f5cd'
];

$invoker = new EtsyClient('zuth9qqhf92vzlw15lxardlo','ky34n9e6m4');
//$authorozedRequest = $invoker->authorizeRequest('/users/__SELF__/shops',$OAUTHPARAMS, array(),'GET');
$authorozedRequest = $invoker->authorizeRequest('/listings',$OAUTHPARAMS, array(),'POST');

/*$etsyshop = new EtsyShop();
$shops = $etsyshop->getShopDetails($authorozedRequest, false, false);*/
$params['productData'] = array(
    'title' => 'This is test title',
    'sku' => 'thisistestsku',
    'description' => 'this is test description',
    'shipping_template_id' => '75807368854',
    'state' => 'draft',
    'taxonomy_id' => '1231',
    'tags' => '',
    'who_made' => 'i_did',
    'is_supply' => true,
    'when_made' => '2010_2019',
    'recipient' => 'boys',
    'style' => array('Avant garde'),
    'language' => 'en',
    'quantity' => 10,
    'price' => 200
    /*'shop_section_id',
      'image_ids',
      'is_customizable',
      'non_taxable',
      'image',
      'processing_min',
      'processing_max',
      */
);

$products = new Products();
$response = $products->listProducts($authorozedRequest,$params,false);
if($response->http_code === 200){
    echo "<pre>";
    print_r($response);
    echo "</pre>";
    exit;
}
echo "<pre>";
print_r($response);
echo "</pre>";
exit;
//echo "<pre>";
//print_r(($response));
//echo "</pre>";
//exit;
