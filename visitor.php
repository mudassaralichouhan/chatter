<?php

require_once 'storage/SQL.php';

$sql = new SQL();

$arr = $sql->fetch_all('SELECT * FROM visitor ORDER BY id DESC LIMIT 50');

function date_time_readable($timestemp) {
    $sys_date_time = strtotime(date('Y-m-d H:i:s'));
    $date_time = strtotime($timestemp);
    $minute = floor(($sys_date_time - $date_time) / 60);

    /*
     * 1440 day
     * 43200 month
     * 525600 year
     */

    if($minute < 60) {
        return $minute. ' min ago';
    } elseif ($minute < 1440) {
        return floor($minute/60).' hour ago';
    } elseif ($minute < 43200) {
        return floor($minute/1440).' day ago';
    } elseif ($minute < 525600) {
        return floor($minute/43200).' month ago';
    } else {
        return floor($minute/525600).' year ago';
    }
}
function getBrowser($agent) {

	$browser        =   "Unknown Browser";

	$browser_array  =   array(
		'/msie/i'       =>  'Internet Explorer',
		'/firefox/i'    =>  'Firefox',
		'/Mozilla/i'	=>	'Mozila',
		//'/Mozilla/5.0/i'=>	'Mozila',
		'/safari/i'     =>  'Safari',
		'/chrome/i'     =>  'Chrome',
		'/edge/i'       =>  'Edge',
		'/opera/i'      =>  'Opera',
		'/OPR/i'        =>  'Opera',
		'/netscape/i'   =>  'Netscape',
		'/maxthon/i'    =>  'Maxthon',
		'/konqueror/i'  =>  'Konqueror',
		'/Bot/i'		=>	'BOT Browser',
		'/Valve Steam GameOverlay/i'  =>  'Steam',
		'/mobile/i'     =>  'Handheld Browser'
	);

	foreach ($browser_array as $regex => $value) {

		if (preg_match($regex, $agent)) {
			$browser    =   $value;
		}

	}

	return $browser;

}
function getOS($agent) {

	$os_platform    =   "Unknown OS Platform";

	$os_array       =   array(
		'/windows nt 10/i'     =>  'Windows 10',
		'/windows nt 6.3/i'     =>  'Windows 8.1',
		'/windows nt 6.2/i'     =>  'Windows 8',
		'/windows nt 6.1/i'     =>  'Windows 7',
		'/windows nt 6.0/i'     =>  'Windows Vista',
		'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
		'/windows nt 5.1/i'     =>  'Windows XP',
		'/windows xp/i'         =>  'Windows XP',
		'/windows nt 5.0/i'     =>  'Windows 2000',
		'/windows me/i'         =>  'Windows ME',
		'/win98/i'              =>  'Windows 98',
		'/win95/i'              =>  'Windows 95',
		'/win16/i'              =>  'Windows 3.11',
		'/macintosh|mac os x/i' =>  'Mac OS X',
		'/mac_powerpc/i'        =>  'Mac OS 9',
		'/linux/i'              =>  'Linux',
		'/kalilinux/i'          =>  'KaliLinux',
		'/ubuntu/i'             =>  'Ubuntu',
		'/iphone/i'             =>  'iPhone',
		'/ipod/i'               =>  'iPod',
		'/ipad/i'               =>  'iPad',
		'/android/i'            =>  'Android',
		'/blackberry/i'         =>  'BlackBerry',
		'/webos/i'              =>  'Mobile',
		'/Windows Phone/i'      =>  'Windows Phone'
	);

	foreach ($os_array as $regex => $value) {

		if (preg_match($regex, $agent)) {
			$os_platform    =   $value;
		}

	}
	return $os_platform;

}
?>
<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

<h2>Visitor Information only for <b>education purpose</b></h2>

<table>
  <tr>
    <th>ID</th>
    <th>Client IP:port</th>
    <th>Server IP:port</th>
    <th>OS</th>
    <th>Browser</th>
    <th>Date and Time</th>
  </tr>
  <?php foreach($arr as $row) { ?>
  <tr>
    <td><?=$row['id']?></td>
    <td><?=$row['ip']?></td>
    <td><?=$row['server_ip']?></td>
    <td><?=getOS($row['agent'])?></td>
    <td><?=getBrowser($row['agent'])?></td>
    <td><?=date_time_readable($row['date_time'])?></td>
  </tr>
  <?php } ?>
</table>

</body>
</html>