<?php
class MB_API {
	protected $client;
	protected $sourceCredentials = array(
		"SourceName"=>'REPLACE_WITH_YOUR_SOURCENAME', 
		"Password"=>'REPLACE_WITH_YOUR_PASSWORD', 
		"SiteIDs"=>array('REPLACE_WITH_YOUR_SITE_ID')
	);
	/*
	** Uncomment if you need user credentials
	protected $userCredentials = array(
		"Username"=>'REPLACE_WITH_YOUR_USERNAME', 
		"Password"=>'REPLACE_WITH_YOUR_PASSWORD', 
		"SiteIDs"=>array('REPLACE_WITH_YOUR_SITE_ID')
	);
	*/
	protected $appointmentServiceWSDL = "https://api.mindbodyonline.com/0_5/AppointmentService.asmx?WSDL";
	protected $classServiceWSDL = "https://api.mindbodyonline.com/0_5/ClassService.asmx?WSDL";
	protected $clientServiceWSDL = "https://api.mindbodyonline.com/0_5/ClientService.asmx?WSDL";
	protected $dataServiceWSDL = "https://api.mindbodyonline.com/0_5/DataService.asmx?WSDL";
	protected $finderServiceWSDL = "https://api.mindbodyonline.com/0_5/FinderService.asmx?WSDL";
	protected $saleServiceWSDL = "https://api.mindbodyonline.com/0_5/SaleService.asmx?WSDL";
	protected $siteServiceWSDL = "https://api.mindbodyonline.com/0_5/SiteService.asmx?WSDL";
	protected $staffServiceWSDL = "https://api.mindbodyonline.com/0_5/StaffService.asmx?WSDL";

	protected $apiMethods = array();
	protected $apiServices = array();

	public $soapOptions = array('soap_version'=>SOAP_1_1, 'trace'=>true);
	public $debugSoapErrors = true;
		
	public function __construct() {
		// set apiServices array with Mindbody WSDL locations
		$this->apiServices = array(
			'AppointmentService' => $this->appointmentServiceWSDL,
			'ClassService' => $this->classServiceWSDL,
			'ClientService' => $this->clientServiceWSDL,
			'DataService' => $this->dataServiceWSDL,
			'FinderService' => $this->finderServiceWSDL,
			'SaleService' => $this->saleServiceWSDL,
			'SiteService' => $this->siteServiceWSDL,
			'StaffService' => $this->staffServiceWSDL
		);
		// set apiMethods array with available methods from Mindbody services
		foreach($this->apiServices as $serviceName => $serviceWSDL) {
			$this->client = new SoapClient($serviceWSDL, $this->soapOptions);
			$this->apiMethods = array_merge($this->apiMethods, array($serviceName=>array_map(
				function($n){
					$start = 1+strpos($n, ' ');
					$end = strpos($n, '(');
					$length = $end - $start;
					return substr($n, $start, $length);
				}, $this->client->__getFunctions()
			)));	
		}
	}

	public function __call($name, $arguments) {
		// check if method exists on one of mindbody's soap services
		$soapService = false;
		foreach($this->apiMethods as $apiServiceName=>$apiMethods) {
			if(in_array($name, $apiMethods)) {
				$soapService = $apiServiceName;
			}
		}
		if(!empty($soapService)) {
			if(empty($arguments)) {
				return $this->callMindbodyService($soapService, $name);
			} else {
				return $this->callMindbodyService($soapService, $name, $arguments[0]);
			}
		} else {
			echo "called unknown method '$name'<br />";
		}
	}

	protected function callMindbodyService($serviceName, $methodName, $requestData = array()) {
		$request = array_merge(array("SourceCredentials"=>$this->sourceCredentials),$requestData);
		if(!empty($this->userCredentials)) {
			$request = array_merge(array("UserCredentials"=>$this->userCredentials), $request);
		}
		$this->client = new SoapClient($this->apiServices[$serviceName], $this->soapOptions);
		try {
			$result = $this->client->$methodName(array("Request"=>$request));
			return $result;
		} catch (SoapFault $s) {
			if($this->debugSoapErrors) {
				echo 'ERROR: [' . $s->faultcode . '] ' . $s->faultstring;
				return false;
			}
		} catch (Exception $e) {
	    	echo 'ERROR: ' . $e->getMessage();
	    	return false;
		}
	}

	public function getXMLRequest() {
		return $this->client->__getLastRequest();
	}
	
	public function getXMLResponse() {
		return $this->client->__getLastResponse();
	}

	public function SelectDataXml($query) {
		$result = $this->callMindbodyService('DataService', 'SelectDataXml', array('SelectSql'=>$query));
		$xmlString = $this->getXMLResponse();
		// replace some invalid xml element names
		$xmlString = str_replace("Current Series", "CurrentSeries", $xmlString);
		$xmlString = str_replace("Item#", "ItemNum", $xmlString);
		$xmlString = str_replace("Massage Therapist", "MassageTherapist", $xmlString);
		$xmlString = str_replace("Workshop Instructor", "WorkshopInstructor", $xmlString);
		$sxe = new SimpleXMLElement($xmlString);
		$sxe->registerXPathNamespace("mindbody", "http://clients.mindbodyonline.com/api/0_5");
		$res = $sxe->xpath("//mindbody:SelectDataXmlResponse");
		return $res[0];
	}
}
?>