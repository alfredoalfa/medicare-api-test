<?php
    class Visit{

        private $conn;

        private $db_table = "visit_detail";

        // Model
        public $id;
        public $date;
        public $idPatient;
        public $idDoctor;
        public $specialization;
        public $statusVisit;
        

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL VISITS
        public function getVisitsByDate(){
            $sqlQuery = 
            "SELECT 
                    VD.id, 
                    VD.specialization as specialization, 
                    VD.date as date, 
                    P.name as name_patient,
                    P.last_name as lastname_patient,
                    D.name as name_doctor,
                    D.last_name as lastname_doctor,
                    SV.status as status_visit FROM " . $this->db_table .
                    " AS VD 
                    INNER JOIN patient AS P
                    ON VD.id_patient = P.id
                    INNER JOIN doctor AS D
                    ON VD.id_doctor = D.id
                    INNER JOIN status_visit AS SV
                    ON VD.id_status_visit = SV.id
                    WHERE VD.date =:date ";
            $stmt = $this->conn->prepare($sqlQuery);

            // sanitize
            $this->name=htmlspecialchars(strip_tags($this->date));

            // bind data
            $stmt->bindParam(":date", $this->date);
            $stmt->execute();
            return $stmt;
        }

        // CREATE A VISIT
        public function createVisit(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        id_patient = :idPatient, 
                        id_doctor = :idDoctor,
                        specialization = :specialization, 
                        id_status_visit = :statusVisit, 
                        date = :date";
        
            $stmt = $this->conn->prepare($sqlQuery);

            // sanitize
            $this->idPatient=htmlspecialchars(strip_tags($this->idPatient));
            $this->idDoctor=htmlspecialchars(strip_tags($this->idDoctor));
            $this->specialization=htmlspecialchars(strip_tags($this->specialization));
            $this->statusVisit=htmlspecialchars(strip_tags($this->statusVisit));
            $this->date=htmlspecialchars(strip_tags($this->date));
        
            // bind data
            $stmt->bindParam(":idPatient", $this->idPatient);
            $stmt->bindParam(":idDoctor", $this->idDoctor);
            $stmt->bindParam(":specialization", $this->specialization);
            $stmt->bindParam(":statusVisit", $this->statusVisit);
            $stmt->bindParam(":date", $this->date);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }  

        // UPDATE STATUS VISIT
        public function updateStatusVisit(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        id_status_visit = :statusVisit
                    WHERE 
                        id = :id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->statusVisit=htmlspecialchars(strip_tags($this->statusVisit));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind data
            $stmt->bindParam(":statusVisit", $this->statusVisit);
            $stmt->bindParam(":id", $this->id);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

    }
?>

