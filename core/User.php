<?php

/*
* User class
*/

class User extends Person
{
	const USERS = 'users';
	const CHAT  = 'messages';
	const NOTI = 'notifications';
	const MEDIA = 'message_media';

	// constructor
	function __construct() {
        date_default_timezone_set("Asia/Karachi");
	}

	// public function for utilization
	public static function login($email, $password) {
        $sql = new SQL();
        $user_table = self::USERS;
        $date = date('Y/m/d H:i:s');
        $password = User::getPasswordHash($password);
        $query = "SELECT id,email,password FROM $user_table WHERE email='$email' AND password='$password'";

        $row = $sql->fetch($query);
        if(!empty($row))
            $sql->query("UPDATE `$user_table` SET status=now() WHERE id=".$row['id']);

        unset($row['password']);
        return $row;
	}
	public function register() {

        $fname = $this->getFname();
        $lname = $this->getLname();
        $email = $this->getEmail();
        $pass = $this->getPassword();
        $img = $this->getImage();

        $sql = new SQL();
        $user_table = self::USERS;
        $query = "INSERT INTO `$user_table`(`first_name`, `last_name`, `email`, `password`, `image`)".
            " VALUES('$fname','$lname','$email','$pass','$img')";
        return $sql->query($query);

	}
    public function update()
    {
        $fname = $this->getFname();
        $lname = $this->getLname();
        $email = $this->getEmail();
        $pass = $this->getPassword();

        $sql = new SQL();
        $user_table = self::USERS;
        $query = "UPDATE `$user_table` SET `first_name`='$fname', `last_name`='$lname', `email`='$email', `password`='$pass' WHERE id=".$this->getId();
        return $sql->query($query);
    }
    public function update_image()
    {
        $image = $this->getImage();

        $sql = new SQL();
        $user_table = self::USERS;
        $query = "UPDATE `$user_table` SET `image`='$image' WHERE id=".$this->getId();
        return $sql->query($query);
    }
	public function logout($session_id) {
		$sql = new SQL();
		$statment = "UPDATE `users` SET `status`=DATE_SUB(now(), INTERVAL 5 minute) WHERE `id`=$session_id";
		return $sql->query($statment);
	}
	public function search_by_name($query) {
        $user_id = $this->getId();
        $sql = new SQL();
        $data = [];

        $user_table = self::USERS;
        $chat_table = self::CHAT;

        //"SELECT * FROM table WHERE CONCAT(nameFirst,  ' ', nameLast) LIKE  'SEARCH_INPUT%' OR CONCAT(nameLast,  ' ', nameFirst) LIKE 'SEARCH_INPUT%'"
        $user_query = "SELECT * FROM `$user_table` WHERE (id!=$user_id) AND
                            first_name LIKE '%$query%'";
        $all_users = $sql->fetch_all($user_query);

        foreach ($all_users as $row) {

            $chat_query = "SELECT sender,receiver,message FROM `$chat_table` WHERE".
                " `sender`=$user_id AND `receiver`=".$row['id']." OR ".
                " `sender`=".$row['id']." AND `receiver`=$user_id".
                " ORDER BY `id` DESC LIMIT 1";
            $chat_result = $sql->fetch($chat_query);

            if(empty($chat_result)) {
                $msg = 'message not yet';
            } else {
                $msg = $chat_result['message'];
                if(strlen($msg) > 30)
                    $msg = substr($msg, 0, 25).'...';
                if($chat_result['receiver'] == $row['id']) {
                    $msg = '<b>You</b>: '.$msg;
                } elseif ($chat_result['sender'] == $row['id']) {
                    $msg = '<b>from</b>: '.$msg;
                }
            }

            array_push($data, [
                'id' => $row['id'],
                'name' => $row['first_name'].' '.$row['last_name'],
                'photo' => $row['image'],
                'status' => $row['status'],
                'last_msg' => $msg,
                'unreaded' => $this->count_unreaded_msg($row['id'])
            ]);
        }

        return $data;
	}
	public function get_by_id($user_id) {
        $sql = new SQL();
        $user_table = self::USERS;
        $query = "SELECT first_name,last_name,email,image FROM `$user_table` WHERE id=".$user_id;
        $row = $sql->fetch($query);

        $this->setFname( $row['first_name'] );
        $this->setLname( $row['last_name'] );
        $this->setEmail( $row['email'] );
        $this->setImage( $row['image'] );
	}
	public function exist_email($email) {
        $sql = new SQL();
        $user_table = self::USERS;
        $query = "SELECT COUNT(*) FROM `$user_table` WHERE email='$email'";

        return $sql->count($query);
    }
    public function get_top_users_list($how=20) {
	    $user_id = $this->getId();
        $sql = new SQL();
        $data = [];

        $user_table = self::USERS;
        $chat_table = self::CHAT;

	    $user_query = "SELECT id,first_name,last_name,image,status FROM `$user_table` WHERE `id`!={$this->getId()} LIMIT $how";
        $all_users = $sql->fetch_all($user_query);

        foreach ($all_users as $row) {

            $chat_query = "SELECT sender,receiver,message FROM `$chat_table` WHERE".
                " `sender`=$user_id AND `receiver`=".$row['id']." OR ".
                " `sender`=".$row['id']." AND `receiver`=$user_id".
                " ORDER BY `id` DESC LIMIT 1";
            $chat_result = $sql->fetch($chat_query);

            if(empty($chat_result)) {
                $msg = 'message not yet';
            } else {
                $msg = $chat_result['message'];
                if(strlen($msg) > 30)
                    $msg = substr($msg, 0, 25).'...';
                if($chat_result['receiver'] == $row['id']) {
                    $msg = '<b>You</b>: '.$msg;
                } elseif ($chat_result['sender'] == $row['id']) {
                    $msg = '<b>from</b>: '.$msg;
                }
            }

            array_push($data, [
                'id' => $row['id'],
                'name' => $row['first_name'].' '.$row['last_name'],
                'photo' => $row['image'],
                'status' => $row['status'],
                'last_msg' => $msg,
                'unreaded' => $this->count_unreaded_msg($row['id'])
            ]);
        }

	    return $data;
    }
    private function count_unreaded_msg($id) {
        $user_id = $this->getId();
        $sql = new SQL();

        $chat_table = self::CHAT;

        $count_msg_query = "SELECT COUNT(readed) FROM $chat_table WHERE readed=0 AND".
            " (`sender`=$user_id AND `receiver`=$id OR ".
            " `sender`=$id AND `receiver`=$user_id)";

        $unreaded = $sql->fetch($count_msg_query);
        if(!empty($unreaded))
            return ($unreaded['COUNT(readed)']);
        return 0;
    }
    public function message_read($chatter_id) {
        $user_id = $this->getId();
        $sql = new SQL();

        $chat_table = self::CHAT;

        $read_msg_query = "UPDATE $chat_table SET readed=1 WHERE readed=0 AND".
            " (`sender`=$user_id AND `receiver`=$chatter_id OR ".
            " `sender`=$chatter_id AND `receiver`=$user_id)";

        $sql->query($read_msg_query);
    }
    public function get_chat($chatter_id, $msg_id)
    {
        $user_id = $this->getId();
        $sql = new SQL();
        $chat_table = self::CHAT;

        // SELECT * FROM permlog WHERE max(id)

        $query = "SELECT `id`,`sender`, `message`, `readed`, `date_time` FROM `$chat_table` WHERE".
            " ((sender='$user_id' AND receiver='$chatter_id') OR ".
            "(sender='$chatter_id' AND receiver='$user_id')) ORDER BY id DESC LIMIT ".OFFSET;
        if($msg_id > 0) {
            $query = "SELECT `id`,`sender`, `message`, `readed`, `date_time` FROM `$chat_table` WHERE".
                " ((sender='$user_id' AND receiver='$chatter_id') OR ".
                "(sender='$chatter_id' AND receiver='$user_id')) AND".
                " `id` > $msg_id";
        }

        $chat_arr = $sql->fetch_all($query);
        for($i=0; $i<sizeof($chat_arr); $i++) {
            $msg = explode('#', $chat_arr[$i]['message']);
            if(isset($msg[1])) {
                $text = stripslashes($msg[1]);
            }
            $type = $msg[0];

            if($type != 'image' and $type != 'video') {

                $chat_arr[$i]['type'] = 'text';
                $chat_arr[$i]['message'] = stripslashes($chat_arr[$i]['message']);

            } elseif($type === 'image') {

                $chat_arr[$i]['type'] = $type;
                $chat_arr[$i]['content'] = $this->get_media_msg($chat_arr[$i]['id']);
                $chat_arr[$i]['message'] = $text;

            } elseif ($type === 'video'){
                $chat_arr[$i]['type'] = $type;
                $chat_arr[$i]['content'] = $this->get_media_msg($chat_arr[$i]['id']);
                $chat_arr[$i]['message'] = $text;
            }
            $chat_arr[$i]['date_time'] = get_msg_date_time_format($chat_arr[$i]['date_time']);
        }

        return array_reverse($chat_arr);
    }
    public function get_chat_limit($chatter_id, $offset)
    {
        $user_id = $this->getId();
        $sql = new SQL();
        $chat_table = self::CHAT;

        // SELECT * FROM messages WHERE max(id)

        $query = "SELECT `id`,`sender`, `message`, `readed`, `date_time` FROM `$chat_table` WHERE".
            " ((sender='$user_id' AND receiver='$chatter_id') OR ".
            "(sender='$chatter_id' AND receiver='$user_id')) ORDER BY id DESC".
            " LIMIT $offset,".OFFSET;

        $chat_arr = $sql->fetch_all($query);
        for($i=0; $i<sizeof($chat_arr); $i++) {
            $msg = explode('#', $chat_arr[$i]['message']);
            if(isset($msg[1])) {
                $text = stripslashes($msg[1]);
            }
            $type = $msg[0];

            if($type != 'image' and $type != 'video') {

                $chat_arr[$i]['type'] = 'text';
                $chat_arr[$i]['message'] = stripslashes($chat_arr[$i]['message']);

            } elseif($type === 'image') {

                $chat_arr[$i]['type'] = $type;
                $chat_arr[$i]['content'] = $this->get_media_msg($chat_arr[$i]['id']);
                $chat_arr[$i]['message'] = $text;

            } elseif ($type === 'video'){
                $chat_arr[$i]['type'] = $type;
                $chat_arr[$i]['content'] = $this->get_media_msg($chat_arr[$i]['id']);
                $chat_arr[$i]['message'] = $text;
            }
            $chat_arr[$i]['date_time'] = get_msg_date_time_format($chat_arr[$i]['date_time']);
        }

        return array_reverse($chat_arr);
    }
    private function get_media_msg($msg_id)
    {
        $sql = new SQL();
        $msg_media_table = self::MEDIA;
        $query = "SELECT `media` FROM `$msg_media_table` WHERE msg_id=".$msg_id;
        $row = $sql->fetch($query);
        return $row['media'];
    }
    public function save_mssage($chat_id, $msg)
    {
        $msg = addslashes($msg);
        $user_id = $this->getId();
        $sql = new SQL();
        $chat_table = self::CHAT;
        $query = "INSERT INTO `$chat_table`(`sender`, `receiver`, `message`) VALUES ('$user_id','$chat_id','$msg')";
        return $sql->insert_last_id($query);
    }
    public function save_media_message($chat_id, string $media_file, $media_type, $msg)
    {
        $msg = $media_type.'#'.$msg;
        $last_id = $this->save_mssage($chat_id, $msg);
        if($last_id > 0) {
            $sql = new SQL();
            $media_table = self::MEDIA;
            $query = "INSERT INTO `$media_table`(`msg_id`, `media`, `which_media`) VALUES ('$last_id','$media_file','$media_type')";
            return $sql->query($query);
        }
        return false;
    }
    public function typing($user_id)
    {
	    $noti_table = self::NOTI;
        $sql = new SQL();
        $query = "DELETE FROM `{$noti_table}` WHERE typer_id=".$user_id;
        $sql->query($query);
	    //$query = "INSERT INTO `{$noti_table}` (`typer_id`, `is_typed`, `time`, `date`) VALUES ('$user_id', '1', CURRENT_TIME, CURRENT_DATE)";
	    $query = "INSERT INTO `{$noti_table}` (`typer_id`, `is_typed`, `date_time`) VALUES ('$user_id', '1', now())";
        return $sql->query($query);
    }
    public function is_typed($chatter_id)
    {
        $noti_table = self::NOTI;
        $sql = new SQL();
        $query = "SELECT `is_typed` FROM `{$noti_table}` WHERE `typer_id`='$chatter_id' AND (`date_time`>= NOW() - INTERVAL 1 MINUTE)";
        //$query = "SELECT `is_typed` FROM `{$noti_table}` WHERE `typer_id`='$chatter_id' AND (`time`>= NOW() - INTERVAL 1 MINUTE) AND ".
            //"`date` > CURRENT_DATE - INTERVAL 1 DAY";
        $result = $sql->fetch($query);

        if(!empty($result)) {
            return true;
        }
        return false;
    }
    public function last_seen()
    {
        $user_id = $this->getId();
        $sql = new SQL();
        $user_table = self::USERS;
        $query = "UPDATE `$user_table` SET status=now() WHERE id=$user_id";
        return $sql->query($query);
    }
    public function get_last_seen($chatter_id)
    {
        $sql = new SQL();
        $user_table = self::USERS;
        $query = "SELECT status FROM `$user_table` WHERE id=$chatter_id";
        $status = $sql->fetch($query);

        if(!empty($status)) {

            $sys_date_time = strtotime(date('Y-m-d H:i:s'));
            $last_seen = $status['status'];
            $date_time = strtotime($last_seen);
            $minute = floor(($sys_date_time - $date_time) / 60);

            /*
             * 1440 day
             * 43200 month
             * 525600 year
             */

            if ($minute <= 1) {
                return 'Just now';
            } elseif($minute < 60) {
                return $minute. ' min ago';
            } elseif ($minute < 1440) {
                return floor($minute/60).' hour ago';
            } elseif ($minute < 11520) {
                $date_time = explode(' ', $last_seen);
                $time = substr($date_time[1], 0, 5);

                return floor($minute/1440).' day ago at: '.$time;
            } else {
                $date_time = explode(' ', $last_seen);

                $date = date_create($date_time[0]);
                $time = date_create($date_time[1]);
//                $time = substr($date_time[1], 0, 5);
                $time = date_format($time, 'h:i a');

                return 'last seen: '.date_format($date,"d M Y").' at '.$time;
            }
        }

        return 'offline';
    }
    public function delete_message($id)
    {
        $sql = new SQL();
        $chat_table = self::CHAT;
        $media_table = self::MEDIA;
        //$query = "DELETE $chat_table, $media_table FROM $chat_table INNER JOIN $media_table
                //WHERE ($chat_table.id=$id AND $media_table.msg_id=$id)";

        $query = "DELETE FROM `$chat_table` WHERE id=$id";
        if($sql->query($query)) {
            $query = "DELETE FROM `$media_table` WHERE msg_id=$id";
            return $sql->query($query);
        }
        return false;
    }
}