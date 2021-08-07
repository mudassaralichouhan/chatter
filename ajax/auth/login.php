<?php
/*
 *
 */

session_start();

require_once '../../route.php';

if(isset($_SESSION[USER_ID])) {
    response_msg(false, 'logout first', base_url().'/user.php');
}

$email =  $_POST['email'];
$pwd = $_POST['pwd'];

// login return user ID
$user_id = User::login( $email, $pwd );
if( !empty($user_id) ) {

    $_SESSION[USER_ID] = $user_id['id'];
    response_msg(true, 'login successfully', base_url().'/user.php');

} else {
    response_msg(false, 'E-mail or Password are incorrect');
}
