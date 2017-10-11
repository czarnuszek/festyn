<?php

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

$creditCard = createCreditCard($apiContext);
listAllCreditCards($apiContext);
//listAllPaymentCards($apiContext);
//printCreditCard($creditCard, $apiContext);
//$payment = createPayment($creditCard, $apiContext);
//getPayment($payment, $apiContext);


/**
 */
function createPaymentCard($apiContext)
{
	echo "#### Creating payment card\n";

	// ### PaymentCard
	// A resource representing a payment card that can be
	// used to fund a payment.
	$card = new PaymentCard();
	$card->setType("visa")
	    ->setNumber("4669424246660779")
	    ->setExpireMonth("11")
	    ->setExpireYear("2019")
	    ->setCvv2("012")
	    ->setFirstName("Joe")
	    ->setBillingCountry("US")
	    ->setLastName("Shopper");

	$card->setMerchantId("MyStore1");
	$card->setExternalCardId("CardNumber123" . uniqid());
	$card->setExternalCustomerId("123123-myUser1@something.com");

	echo "###### Created payment card $card\n";
	print_r($card);

	return $card;
}

/**
 */
function createCreditCard($apiContext)
{
	echo "#### Creating credit card\n";

	// ### PaymentCard
	// A resource representing a payment card that can be
	// used to fund a payment.
	$card = new CreditCard();
	$card->setType("visa")
	    ->setNumber("4669424246660779")
	    ->setExpireMonth("11")
	    ->setExpireYear("2019")
	    ->setCvv2("012")
	    ->setFirstName("Joe")
	    ->setLastName("Shopper");

	$card->setMerchantId("MyStore1");
	$card->setExternalCardId("CardNumber123" . uniqid());
	$card->setExternalCustomerId("123123-myUser1@something.com");

	echo "###### Created credit card $card\n";
	print_r($card);

	return $card;
}

/**
 */
function listAllCreditCards($apiContext)
{
	echo "#### List all credit cards\n";

	/// ### List All Credit Cards
	// (See bootstrap.php for more on `ApiContext`)
	try {
	    // ### Parameters to Filter
	    // There are many possible filters that you could apply to it. For complete list, please refere to developer docs at above link.

	    $params = array(
	        "sort_by" => "create_time",
	        "sort_order" => "desc",
	        "merchant_id" => "MyStore1"  // Filtering by MerchantId set during CreateCreditCard.
	    );
	    $cards = CreditCard::all($params, $apiContext);
	    print_r($cards);
	} catch (Exception $ex) {
	    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
	    ResultPrinter::printError("List All Credit Cards", "CreditCardList", null, $params, $ex);
	    exit(1);
	}

	// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
	ResultPrinter::printResult("List All Credit Cards", "CreditCardList", null, $params, $cards);
}

/**
 */
function listAllPaymentCards($apiContext)
{
	echo "#### List all payment cards\n";

	/// ### List All Credit Cards
	// (See bootstrap.php for more on `ApiContext`)
	try {
	    // ### Parameters to Filter
	    // There are many possible filters that you could apply to it. For complete list, please refere to developer docs at above link.

	    $params = array(
	        "sort_by" => "create_time",
	        "sort_order" => "desc",
	        "merchant_id" => "MyStore1"  // Filtering by MerchantId set during CreateCreditCard.
	    );
	    $cards = PaymentCard::all($params, $apiContext);
	} catch (Exception $ex) {
	    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
	    ResultPrinter::printError("List All Payment Cards", "CreditCardList", null, $params, $ex);
	    exit(1);
	}

	// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
	ResultPrinter::printResult("List All Payment Cards", "CreditCardList", null, $params, $cards);
}

/**
 */
function printCreditCard($card, $apiContext)
{
	echo "#### Printing credit card - $card->getId()\n";
	/// ### Retrieve card
	// (See bootstrap.php for more on `ApiContext`)
	try {
	    $card = CreditCard::get($card->getId(), $apiContext);
	} catch (Exception $ex) {
	    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
	    ResultPrinter::printError("Get Credit Card", "Credit Card", $card->getId(), null, $ex);
	    exit(1);
	}

	// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
	ResultPrinter::printResult("Get Credit Card", "Credit Card", $card->getId(), null, $card);
}


/**
 */
function createPayment($card, $apiContext)
{
	echo "#### Creating payment\n";

	// ### FundingInstrument
	// A resource representing a Payer's funding instrument.
	// For direct credit card payments, set the CreditCard
	// field on this object.
	$fi = new FundingInstrument();
	$fi->setPaymentCard($card);

	// ### Payer
	// A resource representing a Payer that funds a payment
	// For direct credit card payments, set payment method
	// to 'credit_card' and add an array of funding instruments.
	$payer = new Payer();
	$payer->setPaymentMethod("credit_card")
	    ->setFundingInstruments(array($fi));

	// ### Itemized information
	// (Optional) Lets you specify item wise
	// information
	$item1 = new Item();
	$item1->setName('Ground Coffee 40 oz')
	    ->setDescription('Ground Coffee 40 oz')
	    ->setCurrency('USD')
	    ->setQuantity(1)
	    ->setTax(0.3)
	    ->setPrice(7.50);
	$item2 = new Item();
	$item2->setName('Granola bars')
	    ->setDescription('Granola Bars with Peanuts')
	    ->setCurrency('USD')
	    ->setQuantity(5)
	    ->setTax(0.2)
	    ->setPrice(2);

	$itemList = new ItemList();
	$itemList->setItems(array($item1, $item2));

	// ### Additional payment details
	// Use this optional field to set additional
	// payment information such as tax, shipping
	// charges etc.
	$details = new Details();
	$details->setShipping(1.2)
	    ->setTax(1.3)
	    ->setSubtotal(17.5);

	// ### Amount
	// Lets you specify a payment amount.
	// You can also specify additional details
	// such as shipping, tax.
	$amount = new Amount();
	$amount->setCurrency("USD")
	    ->setTotal(20)
	    ->setDetails($details);

	// ### Transaction
	// A transaction defines the contract of a
	// payment - what is the payment for and who
	// is fulfilling it.
	$transaction = new Transaction();
	$transaction->setAmount($amount)
	    ->setItemList($itemList)
	    ->setDescription("Payment description")
	    ->setInvoiceNumber(uniqid());

	// ### Payment
	// A Payment Resource; create one using
	// the above types and intent set to sale 'sale'
	$payment = new Payment();
	$payment->setIntent("sale")
	    ->setPayer($payer)
	    ->setTransactions(array($transaction));

	// For Sample Purposes Only.
	$request = clone $payment;

	// ### Create Payment
	// Create a payment by calling the payment->create() method
	// with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
	// The return object contains the state.
	try {
	    $payment->create($apiContext);
	} catch (Exception $ex) {
	    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
	    ResultPrinter::printError('Create Payment Using Credit Card. If 500 Exception, try creating a new Credit Card using <a href="https://www.paypal-knowledge.com/infocenter/index?page=content&widgetview=true&id=FAQ1413">Step 4, on this link</a>, and using it.', 'Payment', null, $request, $ex);
	    exit(1);
	}

	// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
	 ResultPrinter::printResult('Create Payment Using Credit Card', 'Payment', $payment->getId(), $request, $payment);

	return $payment;
}

/**
 */
function getPayment($createdPayment, $apiContext)
{
	echo "#### Getting payment\n";

	$paymentId = $createdPayment->getId();

	// ### Retrieve payment
	// Retrieve the payment object by calling the
	// static `get` method
	// on the Payment class by passing a valid
	// Payment ID
	// (See bootstrap.php for more on `ApiContext`)
	try {
	    $payment = Payment::get($paymentId, $apiContext);
	} catch (Exception $ex) {
	    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
	    ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
	    exit(1);
	}

	// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
	 ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);

	return $payment;
}

