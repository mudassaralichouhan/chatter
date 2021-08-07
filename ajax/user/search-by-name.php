<?php
/*
 *
 */

session_start();

require_once '../../route.php';

is_login();

$user = new User();
$user->setId( $_SESSION[USER_ID] );
if(isset($_POST['like_name'])) {
    $like_name = $_POST['like_name'];
    response_msg( true, '', '#', $user->search_by_name($like_name) );
}

response_msg( false, 'Some thing was wrong', '#', [] );
