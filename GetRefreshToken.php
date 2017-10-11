<?php

// Get refresh token from Paypal for the user granted code obtained from PayPal account online
//
// Get code from following instructions in file GetUserAuthenticationURL.php
//
// Example online code to get refresh code:
// 		- http://paypal.github.io/PayPal-PHP-SDK/sample/doc/lipp/ObtainUserConsent.html
//
// To Run - pass in authentication code, gotten from the redirect URL from Paypal.
//
// Example redirect URL with code in it:
// 			http://conjura.com/connectors/paypal/success?code=C21AAFzGdQxnK7NkzTVoKuj-J6J2CJq1BP3sUO8PAi9Xu3d07cJRGXcGysuxoZoB8bfeDZ7fV27slZg1KPap_R1NVgQEzEinw&scope=https%3A%2F%2Furi.paypal.com%2Fservices%2Finvoicing%20openid

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\CreditCard;
use PayPal\Api\Transaction;
use PayPal\Api\OpenIdTokeninfo;
use PayPal\Api\OpenIdUserinfo;
use PayPal\Api\OpenIdSession;


// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
// 2. Provide your Secret Key. Replace the given one with your app clientId, and Secret
// See file 'bootstrap.php' for both of these steps
include "bootstrap.php";

$apiContext->setConfig(
    array(
        'log.LogEnabled' => true,
        'log.FileName' => 'PayPal.log',
        'log.LogLevel' => 'DEBUG'
    )
);

echo "Date " . date("Y/m/d") . " " . date("h:i:sa") . "\n";


$authorization_code = null;
if (count($argv) > 1) {
    $authorization_code = $argv[1];
}

if ($authorization_code == null) {
    echo "Need authorization code\n";
    exit(1);
}

$tokenInfo = getRefreshTokenFromAuthCode($apiContext, $authorization_code, $clientId, $clientSecret);


/**
 */
function getRefreshTokenFromAuthCode($apiContext, $authorization_code, $clientId, $clientSecret)
{
    echo "getRefreshTokenFromAuthCode........\n";

    $baseUrl = paypal_redirect_url;

    echo("Getting access token for:\n");
    echo(" 			- Redirect URL: 		$baseUrl\n");
    echo(" 			- Client ID:     		$clientId\n");
    echo(" 			- Authorization Code:	$authorization_code\n");
    echo("			- Mode: 				" . $apiContext->getConfig()['mode'] . "\n");

    $params = array(
        'code' => $authorization_code,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $baseUrl,
        'grant_type' => 'authorization_code'
    );
    $tokenInfo = null;
    try {
        // Underlying endpoint: /v1/identity/openidconnect/tokenservice
        $tokenInfo = new OpenIdTokeninfo();
        $tokenInfo = $tokenInfo->createFromAuthorizationCode($params, $clientId, $clientSecret, $apiContext);
        echo "Access token : " . $tokenInfo->getAccessToken() . "\n";
    } catch (Exception $ex) {
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        ResultPrinter::printError("User Information", "User Info", null, $params, $ex);
        exit(1);
    }

    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    ResultPrinter::printResult("User Information", "User Info", $tokenInfo->getAccessToken(), $params, $tokenInfo, null);

    return $tokenInfo;
}