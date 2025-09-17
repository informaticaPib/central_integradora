<?php
class Database{
    private $host = "172.16.4.55";
    private $db_name = "centralintegradora";
    private $username = "root";
    private $password = "PIB-dbs#2019*";
    public $conn;

    public function getConnection(){

        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            #echo "conectado";
            //$this->conn->exec("");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}

?>