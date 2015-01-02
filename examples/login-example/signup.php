<?php
session_start();
require '../../src/MB_API.php';
$mb = new \DevinCrossman\Mindbody\MB_API(array(
	"SourceName"=>'REPLACE_WITH_YOUR_SOURCENAME', 
	"Password"=>'REPLACE_WITH_YOUR_PASSWORD', 
	"SiteIDs"=>array('REPLACE_WITH_YOUR_SITE_ID')
));
if(!empty($_POST['data']['Client'])) {
	$options = array(
		'Clients'=>array(
			'Client'=>$_POST['data']['Client']
		)
	);
	$signupData = $mb->AddOrUpdateClients($options);
	if($signupData['AddOrUpdateClientsResult']['Clients']['Client']['Action'] == 'Added') {
		$validateLogin = $mb->ValidateLogin(array(
			'Username' => $_POST['data']['Client']['Username'],
			'Password' => $_POST['data']['Client']['Password']
		));
		if(!empty($validateLogin['ValidateLoginResult']['GUID'])) {
			$_SESSION['GUID'] = $validateLogin['ValidateLoginResult']['GUID'];
			$_SESSION['client'] = $validateLogin['ValidateLoginResult']['Client'];
		}
		header('location:index.php');
	}
}
$requiredFields = $mb->GetRequiredClientFields();
if(!empty($requiredFields['GetRequiredClientFieldsResult']['RequiredClientFields']['string'])) {
	$requiredFields = $mb->makeNumericArray($requiredFields['GetRequiredClientFieldsResult']['RequiredClientFields']['string']);
} else {
	$requiredFields = false;
}
$requiredFieldsInputs = '';
if(!empty($requiredFields)) {
	foreach($requiredFields as $field) {
		$requiredFieldsInputs .= "<label for='$field'>{$field}: </label><input type='text' name='data[Client][$field]' id='$field' placeholder='$field' required /><br />";
	}
}
echo "<h3>Sign Up</h3>";
if(!empty($signupData['AddOrUpdateClientsResult']['Clients']['Client']['Action']) && $signupData['AddOrUpdateClientsResult']['Clients']['Client']['Action'] == 'Failed' && !empty($signupData['AddOrUpdateClientsResult']['Clients']['Client']['Messages'])) {
	foreach($signupData['AddOrUpdateClientsResult']['Clients']['Client']['Messages'] as $message) {
		echo "<pre>".print_r($message,1).'</pre><br />';
	}
}
echo <<<EOD
<form method="POST" style="line-height:2">
	<label for="Username">Username: </label><input type="text" name="data[Client][Username]" id="Username" placeholder="Username" required /><br />
	<label for="Password">Password: </label><input type="password" name="data[Client][Password]" id="Password" placeholder="Password" required /><br />
	<label for="FirstName">First Name: </label><input type="text" name="data[Client][FirstName]" id="FirstName" placeholder="First Name" required /><br />
	<label for="LastName">Last Name: </label><input type="text" name="data[Client][LastName]" id="LastName" placeholder="Last Name" required /><br />
	$requiredFieldsInputs
	<button type="submit">Sign up</button>
</form>
EOD;
