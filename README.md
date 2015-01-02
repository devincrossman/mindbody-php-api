mindbody-php-api
============

PHP wrapper class for interacting with Mindbody's API via soap. Requires PHP >= 5.3 with SOAP extension.

Install with composer or clone the repository. Include src/MB_API.php or include vendor/autoload.php if using composer.

Update the MB_API.php file with your Mindbody API source credentials or include them as a parameter to the MB_API constructor

	$mb = new \DevinCrossman\Mindbody\MB_API(array(
		"SourceName"=>'REPLACE_WITH_YOUR_SOURCENAME', 
		"Password"=>'REPLACE_WITH_YOUR_PASSWORD', 
		"SiteIDs"=>array('REPLACE_WITH_YOUR_SITE_ID')
	));

	// CheckoutShoppingCart

	$checkoutShoppingCartRequest = $mb->CheckoutShoppingCart(array(
		'Test'=>'true',
		'ClientID'=>1234,
		'CartItems'=>array(
			'CartItem'=>array(
				'Quantity'=>1,
				'Item' => new SoapVar(
					array('ID'=>'1357'), 
					SOAP_ENC_ARRAY, 
					'Service', 
					'http://clients.mindbodyonline.com/api/0_5'
				),
				'DiscountAmount' => 0
			)
		),
		'Payments' => array(
			'PaymentInfo' => new SoapVar(
				array(
					'CreditCardNumber'=>'4111111111111111', 
					'ExpYear'=>'2015', 
					'ExpMonth'=>'06', 
					'Amount'=>'130', 
					'BillingAddress'=>'123 Happy Ln', 
					'BillingPostalCode'=>'93405'
				), 
				SOAP_ENC_ARRAY, 
				'CreditCardInfo', 
				'http://clients.mindbodyonline.com/api/0_5'
			)
		)
	));

	// GetServices

	$options = array(
		'LocationID'=>1,
		'HideRelatedPrograms'=>true
	);
	$servicesData = $mb->GetServices($options);

	// FunctionData

	$options = array(
		'FunctionName'=>'my_function',
		'FunctionParams'=>array(
			array(
				'ParamName'=>'@startDate',
				'ParamValue'=>'2014-05-01',
				'ParamDataType'=>'datetime'
			),
			array(
				'ParamName'=>'@endDate',
				'ParamValue'=>'2014-05-30',
				'ParamDataType'=>'datetime'
			)
		)
	);
	$data = $mb->FunctionDataXml($options);


See the examples folder for other examples
Read MINDBODY's [API Documentation](https://developers.mindbodyonline.com) for more parameters. 