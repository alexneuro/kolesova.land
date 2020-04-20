<?php
$mrh_pass2 = "X8rgNMYMZPWeG35uL52u";
//$mrh_pass2 = "AALEXneur031";

$out_summ = $_POST["OutSum"];
$inv_id   = $_POST["InvId"];
$shp_item = $_POST["Shp_item"];
$crc      = $_POST["SignatureValue"];

$crc    = strtoupper( $crc );
$my_crc = strtoupper( md5( "$out_summ:$inv_id:$mrh_pass2" ) );

$f = @fopen( "order.txt", "a+" ) or
die( "error" );
fputs( $f, "order_num :$inv_id;Summ :$out_summ;Date :$date\n" );
fclose( $f );

if ( $my_crc != $crc ) {
	echo "bad sign\n";
	exit();
}

echo "OK$inv_id\n";

$f = @fopen( "order.txt", "a+" ) or
die( "error" );
fputs( $f, "order_num :$inv_id;Summ :$out_summ;Date :$date\n" );
fclose( $f );

$mrh_login = "network-life.ru";
$mysqli    = new mysqli( "localhost", "u0531727_new", "ALEXneur031", "u0531727_courses" );
if ( $mysqli->connect_errno ) {
	echo "error_db\n";
	exit();
}
$mysqli->query( "update wp_posts set post_status = 'lp-completed' where id = '$inv_id'" );
