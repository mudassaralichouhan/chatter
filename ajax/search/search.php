<?php
/*
 *
 */

session_start();

require_once '../../route.php';

is_login();

$user = new User();
$user->setId( $_SESSION[USER_ID] );
$string_query = $_POST['query'];

response_msg( true, '', '#', $user->search_by_name($string_query) );
