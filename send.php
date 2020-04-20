<?php

include_once "class-phpass.php";

if ( ! empty( $_POST["name"] ) && ! empty( $_POST["email"] ) && ! empty( $_POST["phone"] ) ) {
	$email = $_POST['email'];
	$name  = $_POST['name'];
	$phone = $_POST['phone'];


	$mysqli = new mysqli( "localhost", "u0531727_new", "ALEXneur031", "u0531727_courses" );
	$mysqli->set_charset( "utf8" );


	$userres = $mysqli->query( "select ID from wp_users where user_email ='$email'" );
	$count   = $userres->num_rows;

	if ( $count == 0 ) {
		$wp_hasher    = new PasswordHash( 8, true );
		$password     = generate_password( 8 );
		$hashPassword = $wp_hasher->HashPassword( $password );

		$mysqli->query( "Insert into wp_users(user_login, user_nicename, user_email, display_name, user_pass, user_registered) values('$email', '$phone', '$email','$name', '$hashPassword', now())" );
		$user_id = $mysqli->insert_id;
	} else {
		$user_arr = mysqli_fetch_all( $userres, MYSQLI_ASSOC );
		$user_id  = $user_arr[0]["ID"];
	}

	////////////////////////////

	$to      = $email;
	$subject = 'Регистрация на сайте "Онлайн-курсы Международной академии менеджмента"';
	if ( $count == 0 ) {
		$message = "Здравствуйте, " . $name . ". Спасибо за регистрацию. \r\n Для доступа в личный кабинет перейдите по ссылке https://courses.mam-unex.ru/login и авторизируйтесь, используя следующие регистрационные данные:\r\n Логин: " . $email . "\r\n Пароль:" . $password
		           . "\r\n \r\n После оплаты данного курса в личном кабинете появится доступ на просмотр курса. \r\n \r\n В целях усиления безопасности рекомендуем заменить пароль в настройках личного кабинета.";

	} else {
		$message = "Здравствуйте, " . $name . ".Спасибо за обращение. \r\n Вы уже зарегистрированы на нашем сайте. Для доступа в личный кабинет перейдите по ссылке https://courses.mam-unex.ru/login и авторизируйтесь, используя свои регистрационные данные"
		           . "\r\n \r\n После оплаты данного курса в личном кабинете появится доступ на просмотр курса. ";

	}

	$headers = "From: user <info@mam-unex.ru>\r\n" .
	           "MIME-Version: 1.0" . "\r\n" .
	           "Content-type: text/html; charset=UTF-8" . "\r\n";

	mail( $to, $subject, $message, implode( "\r\n", $headers ) ); //Отправка письма с помощью функции mail

	if ( $count == 0 ) {


		$to      = "alexneuro31@ya.ru, ant-roman@yandex.ru";
		$subject = 'Сообщение с сайта "Онлайн-курсы Международной академии менеджмента". Регистрация нового клиента.';

		$message = "Новая регистрация на сайте. Регистрационные данные:\r\n Логин: " . $email . "\r\n Имя: " . $name . "\r\n Телефон: " . $phone;

		$headers = "From: user <info@mam-unex.ru>\r\n" .
		           "MIME-Version: 1.0" . "\r\n" .
		           "Content-type: text/html; charset=UTF-8" . "\r\n";

		mail( $to, $subject, $message, implode( "\r\n", $headers ) );
	}
}


function generate_password( $number ) {
	$arr = array(
		'a',
		'b',
		'c',
		'd',
		'e',
		'f',
		'g',
		'h',
		'i',
		'j',
		'k',
		'l',
		'm',
		'n',
		'o',
		'p',
		'r',
		's',
		't',
		'u',
		'v',
		'x',
		'y',
		'z',
		'A',
		'B',
		'C',
		'D',
		'E',
		'F',
		'G',
		'H',
		'I',
		'J',
		'K',
		'L',
		'M',
		'N',
		'O',
		'P',
		'R',
		'S',
		'T',
		'U',
		'V',
		'X',
		'Y',
		'Z',
		'1',
		'2',
		'3',
		'4',
		'5',
		'6',
		'7',
		'8',
		'9',
		'0',
		'.',
		',',
		'(',
		')',
		'[',
		']',
		'!',
		'?',
		'&',
		'^',
		'%',
		'@',
		'*',
		'$',
		'<',
		'>',
		'/',
		'|',
		'+',
		'-',
		'{',
		'}',
		'`',
		'~'
	);
	// Генерируем пароль
	$pass = "";
	for ( $i = 0; $i < $number; $i ++ ) {
		// Вычисляем случайный индекс массива
		$index = rand( 0, count( $arr ) - 1 );
		$pass  .= $arr[ $index ];
	}

	return $pass;
}

?>

