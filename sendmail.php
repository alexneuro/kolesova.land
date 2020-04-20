<?php
header("Content-Type: application/json; charset=utf-8");

$from="info@".$_SERVER['HTTP_HOST'];
$to="info@termod.ru";
	$subj='Thermodecking: Сообщение из формы "Заявка на термообработку"';

$headers.="To: <$to>\n";
$headers.="From: <$from>\n";
$headers.="Subject: $subj\n";
$headers.="Mime-Version: 1.0\n";
$headers.="Content-type: text/html; charset=\"utf-8\"\n";
$headers.="Content-Transfer-Encoding: 8bit\n\n";

$body.="

<html>
    <head>
        <title>$subj</title>
    </head>
    <body>
    	<p>Информационное сообщение сайта Thermodecking</p>
		<p>------------------------------------------</p>
		<br>
		<p>Данные из формы:</p>
		<p>Имя: $_POST[name]</p>
		<p>Телефон: $_POST[phone]</p>
		<br>
		<p>Сообщение сгенерировано автоматически.</p>
    </body>
</html>";



$res=mail($to, $subj, $body, $headers);
	

echo json_encode($res);

?>

