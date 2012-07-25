<?php


$country = '';
$IP = $_SERVER['REMOTE_ADDR'];

if (!empty($IP)) {
echo $country = file_get_contents('http://api.hostip.info/country.php?ip=24.24.24.24');
}
?>