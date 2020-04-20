<?php
include_once "class-phpass.php";

if ( ! empty( $_POST["name"] ) && ! empty( $_POST["email"] ) && ! empty( $_POST["phone"] ) ) {
	$email = $_POST["email"];
	$name  = $_POST["name"];
	$phone = $_POST["phone"];


	$mysqli = new mysqli( "localhost", "u0531727_new", "ALEXneur031", "u0531727_courses" );
	$mysqli->set_charset( "utf8" );


	$userres = $mysqli->query( "select ID from wp_users where user_email ='$email'" );
	$count   = $userres->num_rows;


	if ( $count == 0 ) {
		$wp_hasher    = new PasswordHash( 8, true );
		$hashPassword = $wp_hasher->HashPassword( $email );

		$mysqli->query( "Insert into wp_users(user_login, user_nicename, user_email, display_name, user_pass, user_registered) values('$email', '$phone', '$email','$name', '$hashPassword', now())" );
		$user_id = $mysqli->insert_id;
	} else {
		$user_arr = mysqli_fetch_all( $userres, MYSQLI_ASSOC );
		$user_id  = $user_arr[0]["ID"];
	}

//    print 'User = ' . $user_id;
	$orderres = $mysqli->query( "select ID from wp_posts p inner join wp_postmeta pm on pm.post_id = p.id where p.post_title ='Заказ с сайта network-life.ru' and pm.meta_key='_user_id' and pm.meta_value='$user_id' and post_status='lp-pending'" );
	$count    = $orderres->num_rows;

	if ( $count == 0 ) {
		$mysqli->query( "Insert into wp_posts(post_author, post_date, post_title, post_status, post_type) values(1, now(), 'Заказ с сайта network-life.ru', 'lp-pending', 'lp_order')" );
		$order_id = $mysqli->insert_id;
//    print 'Order = ' . $order_id;

		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_order_currency', 'RUB')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_prices_include_tax', 'no')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_user_id', '$user_id')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_order_subtotal', '9100')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_order_total', '9100')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_order_key', '" . strtoupper( uniqid( 'ORDER' ) ) . "')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_order_version', '3.0.0')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_payment_method', '')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_payment_method_title', '')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_user_ip_address', '')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_user_agent', '')" );
		$mysqli->query( "Insert into wp_postmeta(post_id,meta_key, meta_value) values($order_id,'_created_via', '')" );


		$mysqli->query( "Insert into wp_learnpress_order_items(order_item_name, order_id) values('Онлайн-курс “Бизнес в условиях сетевой экономики”',$order_id)" );
		$order_item_id = $mysqli->insert_id;
//    print 'OrderItem = ' . $order_item_id;


		$mysqli->query( "Insert into wp_learnpress_order_itemmeta(learnpress_order_item_id,meta_key, meta_value) values($order_item_id,'_course_id', '7')" );
		$mysqli->query( "Insert into wp_learnpress_order_itemmeta(learnpress_order_item_id,meta_key, meta_value) values($order_item_id,'_quantity', '1')" );
		$mysqli->query( "Insert into wp_learnpress_order_itemmeta(learnpress_order_item_id,meta_key, meta_value) values($order_item_id,'_subtotal', '9100')" );
		$mysqli->query( "Insert into wp_learnpress_order_itemmeta(learnpress_order_item_id,meta_key, meta_value) values($order_item_id,'_total', '9100')" );
	} else {
		$order_arr = mysqli_fetch_all( $orderres, MYSQLI_ASSOC );
		$order_id  = $order_arr[0]["ID"];
	}


	$emailBase64 = trim( htmlspecialchars( strip_tags( base64_encode( urlencode( $email ) ) ) ) );

	$mrh_login = "network-life.ru";
//	$mrh_pass1 = "sYaJ99RbPK3LTZv6tY7i";
	$mrh_pass1 = "ALEXneur031";

	$inv_id = $order_id;

	$inv_desc = "Оплата онлайн-курса Бизнес в условиях сетевой экономики";

	$out_summ = "4900.00";

	$shp_item = "1";

	$in_curr = "";

	$culture = "ru";

	$encoding = "utf-8";

	$crc = md5( "$mrh_login:$out_summ:$inv_id:$mrh_pass1" );



//	print "https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc&Email=$email";
	header("Location: https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc&Email=$email");
}
