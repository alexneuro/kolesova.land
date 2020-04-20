<?php

if ( ! empty( $_POST["name"] ) && ! empty( $_POST["email"] ) && ! empty( $_POST["phone"] ) ) {
	$email = $_POST['email'];
	$name  = $_POST['name'];
	$phone = $_POST['phone'];
	$mes = $_POST['mes'];


	$to      = "alexneuro31@ya.ru";
	$subject = 'Посетитель оставил отзыв на сайте "Онлайн-курсы Международной академии менеджмента"';

		$message = $name . "\r\n" . $email . "\r\n".$phone . "\r\n Отзыв: \r\n".$mes;



	$headers = "From: user <info@mam-unex.ru>\r\n" .
	           "MIME-Version: 1.0" . "\r\n" .
	           "Content-type: text/html; charset=UTF-8" . "\r\n";

	mail( $to, $subject, $message, implode( "\r\n", $headers ) ); //Отправка письма с помощью функции mail

}


?>

