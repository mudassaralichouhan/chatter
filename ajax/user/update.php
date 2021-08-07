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
$user->setFname($_POST['fname']);
$user->setLname($_POST['lname']);
$user->setEmail($_POST['email']);
$user->setPassword($_POST['pwd']);

// validate requested data from client
if($user->getEmail() == '') {
    response_msg(false, 'E-mail required!', '#', []);
}
if($user->getFname() == '') {
    response_msg(false, 'Cheack you name speeling', '#', []);
}
if($user->getPassword() == '') {
    response_msg(false, 'Password must be greater then `Six` charactar for security reson!', '#', []);
}

// registor to storage
if( $user->update() )
{
    response_msg(true, 'Update Done', base_url().'/login.php', []);
}

response_msg(false, 'Some thing was wrong, request not process!!!', '#', []);
