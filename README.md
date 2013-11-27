mindbody-php-api
============

PHP wrapper class for interacting with Mindbody's API via soap

update the MB_API.php file with your Mindbody API source credentials. 
Include the MB_API.php file and call the API methods like this 

	require_once ‘MB_API.php’;
	$mb = new MB_API();

	// SelectDataXml

	$sql = "SET ROWCOUNT 10 SELECT * FROM Clients";
	$clients = $mb->SelectDataXml($sql);

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

Check the [API Documentation](https://api.mindbodyonline.com/doc) for more parameters. 