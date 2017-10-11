<?php

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

// 3. Lets try to save a credit card to Vault using Vault API mentioned here
echo "Adding credit card.... \n";
$creditCard = new \PayPal\Api\CreditCard();
$creditCard->setType("visa")
    ->setNumber("4417119669820331")
    ->setExpireMonth("11")
    ->setExpireYear("2019")
    ->setCvv2("012")
    ->setFirstName("Joe")
    ->setLastName("Shopper");

// 4. Make a Create Call and Print the Card
try {
    echo "Creating credit card....\n";
    $creditCard->create($apiContext);
    echo "Created credit card $creditCard\n\n";
}
catch (\PayPal\Exception\PayPalConnectionException $ex) {
    // This will print the detailed information on the exception. 
    //REALLY HELPFUL FOR DEBUGGING
    print_r($ex);
    echo "Problems - error: $ex->getData()";
}
