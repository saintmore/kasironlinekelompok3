<?php

class Database {

    private $host = "localhost:3306";
    private $user = "root";
    private $pass = "qwerty123";
    private $dbname = "alfurqon";
    public $konekdb;

    public function Connect(){
        $this->konekdb = null;

        try {
            $this->konekdb = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname,$this->user,$this->pass);
            $this->konekdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        }

        catch(PDOException $e){
            echo "Koneksi Database Error : ".$e->getMessage();
        }

        return $this->konekdb;
    }
}
?>