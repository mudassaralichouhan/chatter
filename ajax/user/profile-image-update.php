<?php
/*
 *
 */

session_start();

require_once '../../route.php';

// create an object init
$user = new User();

// then set data to object
$user->setId( $_SESSION[USER_ID] );

$type = $_FILES['image']['type'];
$image = $_FILES['image']['tmp_name'];
$image = file_get_contents($image);
$user->setImage( "data:image/$type;charset=utf8;base64,".base64_encode( $image ) );

// registor to storage
if( $user->update_image() )
{
    response_msg(true, '', '#', []);
}

response_msg(false, 'Some thing was wrong, request not process!!!', '#', []);