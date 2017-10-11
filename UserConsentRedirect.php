<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 10/11/17
 * Time: 5:22 PM
 */

require "bootstrap.php";

use PayPal\Api\OpenIdTokeninfo;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Api\OpenIdUserinfo;

session_start();

if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $code = $_GET['code'];

    try {

        $accessToken = OpenIdTokeninfo::createFromAuthorizationCode(array('code' => $code), null, null, $apiContext);
    } catch (PayPalConnectionException $ex) {

        ResultPrinter::printError("Obtained Access Token", "Access Token", null, $_GET['code'], $ex);
        exit(1);
    }

    ResultPrinter::printResult("Obtained Access Token", "Access Token", $accessToken->getAccessToken(), $_GET['code'], $accessToken);
}

$refreshToken = $accessToken->refresh_token;

try {
    $tokenInfo = new OpenIdTokeninfo();
    $tokenInfo = $tokenInfo->createFromRefreshToken(array('refresh_token' => $refreshToken), $apiContext);

    $params = array('access_token' => $tokenInfo->getAccessToken());
    $userInfo = OpenIdUserinfo::getUserinfo($params, $apiContext);
} catch (Exception $ex) {

    ResultPrinter::printError("User Information", "User Info", null, $params, $ex);
    exit(1);
}

ResultPrinter::printResult("User Information", "User Info", $userInfo->getUserId(), $params, $userInfo);