<?php 
    class RefreshToken{
        private $refresh_token_key =  "refresher5m9pktLLKn7513";
        private $firstname;

        public function generate_refresh_token($arg1){
            $time = time();
            $this->firstname = $arg1;
            return [
                "iss"=> 'ref201jhbg2980',
                "iat"=>$time,
                "exp"=>($time+600),
                "firstname"=>$this->firstname
            ];
        }

        public function key_return_refresh_token(){
            return $this->refresh_token_key;
        }
    }


?>