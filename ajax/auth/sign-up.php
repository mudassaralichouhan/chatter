<?php
/*
 *
 */

session_start();

require_once '../../route.php';

if(isset($_SESSION[USER_ID])) {
    response_msg(false, 'logout first', base_url().'/user.php');
}

// create an object init
$user = new User();

// then set data to object
$user->setFname($_POST['fname']);
$user->setLname($_POST['lname']);
$user->setEmail($_POST['email']);
$user->setPassword($_POST['pwd']);
$user->setImageRaw($_FILES['image']);

// validate requested data from client

if($user->getEmail() == '') {
    response_msg(false, 'E-mail required!', '#', []);
}
if($user->exist_email( $user->getEmail() ) != 0 ) {
    response_msg(false, 'E-mail is already exist!', '#', []);
}
if($user->getImage() == null) {
    response_msg(false, 'Select an image like png jpeg jpg, gif', '#', []);
}
if($user->getFname() == '') {
    response_msg(false, 'Cheack you name speeling!', '#', []);
}
if($user->getPassword() == '') {
    response_msg(false, 'Password must be greater then `Six` charactar for security reson!', '#', []);
}

// registor to storage
if( $user->register() )
{
    response_msg(true, 'Password must be greater then `Six` charactar for security reson!', base_url().'/login.php', []);
}

response_msg(false, 'Some thing was wrong, request not process!!!', '#', []);
