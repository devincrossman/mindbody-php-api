<?php
/*
Update the FunctionName to your stored procedure name. Each FunctionParam needs a name, value, and datatype
*/
require '../../src/MB_API.php';
$mb = new \DevinCrossman\Mindbody\MB_API(array(
	"SourceName"=>'REPLACE_WITH_YOUR_SOURCENAME', 
	"Password"=>'REPLACE_WITH_YOUR_PASSWORD', 
	"SiteIDs"=>array('REPLACE_WITH_YOUR_SITE_ID')
));
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
echo "<pre>".print_r($data,1)."</pre>";
?>