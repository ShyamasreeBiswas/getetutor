<?php
require_once 'cn.php';

$fiveMinutesAgo = time() - 600;

$sql = 'SELECT
	username, message_content, message_time
		FROM
	messages
		WHERE
	message_time > ' . $fiveMinutesAgo . '
		ORDER BY
	message_time';
$result = mysql_query($sql, $cn) or
	die(mysql_error($cn));
	
while ($row = mysql_fetch_assoc($result)) {
	$hoursAndMinutes = date('g:ia', $row['message_time']);
	
	echo '<p><strong>' . $row['username'] . '</strong>: <em>(' . $hoursAndMinutes . ')</em> ' . $row['message_content'] . '</p>';
}
?>