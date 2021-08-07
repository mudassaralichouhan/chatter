<?php

/*
 *  Person Class
 */
 
class Person
{
	// local memeber
    private int $id = 0;
    private string $first_name = '';
    private string $last_name = '';
    private string $email = '';
    private string $password = '';
    private $image = null;
    private bool $user_status = false;

    private $pattern = "/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#1-9]+/i";
    private $allow_ext = ['jpg','png','jpeg','gif'];

    /*
     *  local function for validations
     */
    // set user id
    public function setId($id) {
        if($id > 0) {
            $this->id = $id;
            return $this->id;
        }
        return false;
    }
    public function getId() {
        return $this->id;
    }
    // Name
    public function setFname($name)
    {
        $len = strlen($name);
        if( $len >= 3 && !preg_match($this->pattern, $name)) {
            $this->first_name = ucwords($name);
            return true;
        }
        return $len;
    }
    public function setLname($name)
    {
        $this->last_name = ucwords($name);
        return true;

    }
    public function getFname() {
        return $this->first_name;
    }
    public function getLname() {
        return $this->last_name;
    }

    // E-mail
    public function setEmail($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
            return true;
        }
        return false;
    }
    public function getEmail() {
        return $this->email;
    }

    // Password
    public function setPassword($password) {
        $len = strlen($password);
        if($len >= 6 && $len < 255) {
//            password_hash('abc', PASSWORD_DEFAULT);
            $this->password = md5($password);
            return true;
        }
        return $len;
    }
    public function getPassword() {
        return $this->password;
    }
    public static function getPasswordHash($password) {
        return md5($password);
    }

    // Profile Photo
    public function setImageRaw($file) {
        $size = $file["size"];
        $name = $file["name"];
        $tmp_name = $file["tmp_name"];
        $type = $file["type"];

        // Get file info
        $file_name = basename($name);
        $file_type = pathinfo($file_name, PATHINFO_EXTENSION);

        if($size <= 6*1024*1024) {
            if(in_array($file_type, $this->allow_ext)){

                $img_content = "data:image/$type;charset=utf8;base64,".base64_encode(file_get_contents($tmp_name));
                // $img_content = file_get_contents($tmp_name);
                $this->image = $img_content;
            }
        }
    }
    public function getImage() {
        return $this->image;
    }
    public function setImage($image) {
        $this->image = $image;
    }

    // current status
    public function setStatus($status) {
        $this->user_status = $status;
    }
    public function getStatus() {
        return $this->user_status;
    }
}