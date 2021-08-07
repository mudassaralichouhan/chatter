<?php

session_start();

require_once '../../route.php';

is_login();

$message_id = $_POST['id'];

if( (new User())->delete_message( $message_id ) ) {
    response_msg(true, 'Successfully deleted', '#', []);
}
response_msg(false, 'Some thing was wrong', '#', []);