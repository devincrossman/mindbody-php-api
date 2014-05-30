<?php
/*
Update the FunctionName to your stored procedure name. Each FunctionParam needs a name, value, and datatype
*/
require '../../MB_API.php';
$mb = new MB_API();
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