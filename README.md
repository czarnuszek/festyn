# README #



### Links

+ PayPal REST API:  https://developer.paypal.com/docs/api/


### How do I get set up? ###

* Download PHP API - details in WIKI
	* https://github.com/paypal/PayPal-PHP-SDK/wiki
* Create a Paypal App
	* https://developer.paypal.com/docs/integration/admin/manage-apps/
* Connect into the Paypal sandbox App
	* More about sandbox accounts here:  https://developer.paypal.com/docs/classic/lifecycle/sb_overview/


### Running Test Code

Notes - test code is based on examples on the PHP API site here: http://paypal.github.io/PayPal-PHP-SDK/sample/

+ Update app client id & client secret in config file
	+ In file ./config/config.inc.php
* Run code
	+ php TestPaypalConnector.php