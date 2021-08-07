<?php
/*
 *
 */

session_start();

require_once '../../route.php';

is_login();

/*
 * [name] => download.jpg
 * [type] => image/jpeg
 * [tmp_name] => C:\xampp\tmp\php8B03.tmp
 * [error] => 0
 * [size] => 4771
 */

// session_id is user_id
$user_id = $_SESSION[USER_ID];
$chat_id = $_POST['id'];
$msg = $_POST['message'];

$user = new User();
$user->setId( $user_id );

if(isset($_FILES)) {

    $file = $_FILES['media'];

    if( $file['size'] < (5*MB) ) {
        $meida_type = $file['type'];
        $media_file = "data:$meida_type;base64,".base64_encode(file_get_contents($file['tmp_name']));

        $mime = mime_content_type($file['tmp_name']);
        if(strstr($mime, "video/")){
            $meida_type = 'video';
        }else if(strstr($mime, "image/")){
            $meida_type = 'image';
        } else {
			response_msg(false, 'not supported file', '#', []);
		}

        if( $user->save_media_message($chat_id, $media_file, $meida_type, $msg) ) {
            response_msg(true, 'send successfully', '#', []);
        }

    } else {
        response_msg(false, 'exceeds file size', '#', []);
    }

} else {
    response_msg(false, 'Attach media file', '#', []);
    exit;
}
response_msg(false, 'Some thing was wrong', '#', []);
