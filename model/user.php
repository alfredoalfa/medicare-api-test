<?php

class User {
    private $conn;

    private $db_table_user = "user";
    private $db_table_patient = "patient";

    // Model
    public $id;
    public $username;
    public $password;

    // Db connection
    public function __construct($db){
        $this->conn = $db;
    }

    // GET USER LOGING
    public function getUSerLogin(){
        $sqlQuery = "SELECT * FROM " . $this->db_table_user . " WHERE username =:username AND password =:password";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));

        // bind data
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);

        $stmt->execute();
        return $stmt;
    }

    // GET PATIENT BY ID
    public function getPatient(){
        $sqlQuery = "SELECT * FROM " . $this->db_table_patient . " WHERE id_user_patient=:id ";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind data
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        return $stmt;
    }


}

?>