<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 10/11/17
 * Time: 4:54 PM
 */

include "bootstrap.php";

use PayPal\Api\OpenIdSession;

$apiContext->setConfig(
    array(
        'log.LogEnabled' => true,
        'log.FileName' => 'PayPal.log',
        'log.LogLevel' => 'DEBUG'
    )
);

$baseUrl = getBaseUrl() . '/UserConsentRedirect.php?success=true';

$redirectUrl = OpenIdSession::getAuthorizationUrl(
    $baseUrl,
    array('openid', 'profile', 'address', 'email', 'phone',
        'https://uri.paypal.com/services/paypalattributes',
        'https://uri.paypal.com/services/expresscheckout',
        'https://uri.paypal.com/services/invoicing'),
    null,
    null,
    null,
    $apiContext
);
ResultPrinter::printResult("Generated the User Consent URL", "URL", '<a href="' . $redirectUrl . '" >Click Here to Obtain User Consent</a>', $baseUrl, $redirectUrl);
