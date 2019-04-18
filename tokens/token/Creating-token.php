<?php 
    class Token{

        private $key = 'boom12cvutn6kkl56bf0';
        private $email;
        private $name;
        private $lastname;
        private $role;

        public function generate_token($arg1,$arg2,$arg3,$arg4){
            $this->email = $arg1;
            $this->name= $arg2;
            $this->lastname= $arg3;
            $this->role = $arg4;
            $time = time();
            return [
                "iss"=> 'tEsTingApi123',
                "iat"=>$time,
                "exp"=>($time+3600),
                "email"=>$this->email,
                "name"=>$this->name,
                "lastname"=>$this->lastname,
                "role"=>$this->role
            ];
        }


        public function key(){
            return $this->key;
        }

    }
?>