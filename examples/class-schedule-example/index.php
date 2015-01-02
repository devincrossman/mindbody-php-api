<?php
require '../../src/MB_API.php';
$mb = new \DevinCrossman\Mindbody\MB_API(array(
	"SourceName"=>'REPLACE_WITH_YOUR_SOURCENAME', 
	"Password"=>'REPLACE_WITH_YOUR_PASSWORD', 
	"SiteIDs"=>array('REPLACE_WITH_YOUR_SITE_ID')
));

$data = $mb->GetClasses(array('StartDateTime'=>date('Y-m-d'), 'EndDateTime'=>date('Y-m-d', strtotime('today + 7 days'))));
if(!empty($data['GetClassesResult']['Classes']['Class'])) {
	$classes = $mb->makeNumericArray($data['GetClassesResult']['Classes']['Class']);
	$classes = sortClassesByDate($classes);
	foreach($classes as $classDate => $classes) {
		echo $classDate.'<br />';
		foreach($classes as $class) {
			$sDate = date('m/d/Y', strtotime($class['StartDateTime']));
			$sLoc = $class['Location']['ID'];
			$sTG = $class['ClassDescription']['Program']['ID'];
			$studioid = $class['Location']['SiteID'];
			$sclassid = $class['ClassScheduleID'];
			$sType = -7;
			$linkURL = "https://clients.mindbodyonline.com/ws.asp?sDate={$sDate}&sLoc={$sLoc}&sTG={$sTG}&sType={$sType}&sclassid={$sclassid}&studioid={$studioid}";
			$className = $class['ClassDescription']['Name'];
			$startDateTime = date('Y-m-d H:i:s', strtotime($class['StartDateTime']));
			$endDateTime = date('Y-m-d H:i:s', strtotime($class['EndDateTime']));
			$staffName = $class['Staff']['Name'];
			echo "<a href='{$linkURL}'>{$className}</a> w/ {$staffName} {$startDateTime} - {$endDateTime}<br />";
		}
	}
} else {
	if(!empty($data['GetClassesResult']['Message'])) {
		echo $data['GetClassesResult']['Message'];
	} else {
		echo "Error getting classes<br />";
		echo '<pre>'.print_r($data,1).'</pre>';
	}
}

function sortClassesByDate($classes = array()) {
	$classesByDate = array();
	foreach($classes as $class) {
		$classDate = date("Y-m-d", strtotime($class['StartDateTime']));
		if(!empty($classesByDate[$classDate])) {
			$classesByDate[$classDate] = array_merge($classesByDate[$classDate], array($class));
		} else {
			$classesByDate[$classDate] = array($class);
		}
	}
	ksort($classesByDate);
	foreach($classesByDate as $classDate => &$classes) {
		usort($classes, function($a, $b) {
			if(strtotime($a['StartDateTime']) == strtotime($b['StartDateTime'])) {
				return 0;
			}
			return $a['StartDateTime'] < $b['StartDateTime'] ? -1 : 1;
		});
	}
	return $classesByDate;
}
?>