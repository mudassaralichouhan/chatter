<?php
/*
 *
 */

session_start();

require_once '../../route.php';

is_login();

// session_id is user_id
$user_id = $_SESSION[USER_ID];
if(isset($_REQUEST['id'])) {
    $user_id = $_REQUEST['id'];
}

$user = new User();
$user->get_by_id($user_id);

response_msg(true, '', '#', [
    'fname' => $user->getFname(),
    'lname' => $user->getLname(),
    'email' => $user->getEmail(),
    'photo' => $user->getImage()
]);
