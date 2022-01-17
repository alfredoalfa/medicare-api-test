<?php
class UserAccess {

    private $role;
    private $userId;    

    public function getRole($token) {
        $userRole = $this->decodeToken($token);
        $this->role = $userRole['user']->role;
        return $this->role;
    }

    public function getLoggeUser($token) {
        $userId = $this->decodeToken($token);
        $this->userId = $userId['user']->id;
        return $this->userId;
    }

    private function decodeToken($token) {
        $decodeToken = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
        $userValues = (array) $decodeToken;
        return $userValues;
    }
}
?>