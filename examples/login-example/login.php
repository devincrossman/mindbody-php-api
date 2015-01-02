<?php
require '../../src/MB_API.php';
$mb = new \DevinCrossman\Mindbody\MB_API(array(
	"SourceName"=>'REPLACE_WITH_YOUR_SOURCENAME', 
	"Password"=>'REPLACE_WITH_YOUR_PASSWORD', 
	"SiteIDs"=>array('REPLACE_WITH_YOUR_SITE_ID')
));

if(!empty($_POST)) {
	$validateLogin = $mb->ValidateLogin(array(
		'Username' => $_POST['username'],
		'Password' => $_POST['password']
	));
	if(!empty($validateLogin['ValidateLoginResult']['GUID'])) {
		$_SESSION['GUID'] = $validateLogin['ValidateLoginResult']['GUID'];
		$_SESSION['client'] = $validateLogin['ValidateLoginResult']['Client'];
		displayWelcome();
	} else {
		if(!empty($validateLogin['ValidateLoginResult']['Message'])) {
			echo $validateLogin['ValidateLoginResult']['Message'];
		} else {
			echo "Invalid Login<br />";
		}
		displayLoginForm();
	}
} else if(empty($_SESSION['GUID'])) {
	displayLoginForm();
} else {
	displayWelcome();
}

function displayLoginForm() {
	echo <<<EOD
<form method="POST">
	<input type="text" name="username" placeholder="username" />
	<input type="password" name="password" placeholder="password" />
	<button type="submit">Log in</button> <a href="signup.php">Sign up</a>
</form>	
EOD;
}

function displayWelcome() {
	echo "Welcome ".$_SESSION['client']['FirstName'].' '.$_SESSION['client']['LastName'];
	echo "<br />";
	echo "<a href='logout.php'>Log out</a>";
	echo "<pre>".print_r($_SESSION,1)."</pre>";
}
?>