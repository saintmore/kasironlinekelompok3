<?php
require_once("config/Database.php");
// set koneksi database
$database = new Database();
$db = $database->Connect();

if(isset($_POST['username']) AND isset($_POST['password'])){
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $cekLogin = $db->prepare("SELECT * FROM ms_user WHERE username = :username AND password = :password");
    $cekLogin->bindParam(':username', $username);
    $cekLogin->bindParam(':password', $password);
    $cekLogin->execute();
    $dataLogin = $cekLogin->fetch(PDO::FETCH_ASSOC);
    $hasilCek = $cekLogin->rowCount();
    if($hasilCek > 0){
        if($dataLogin['status'] == 1){
            session_start();
            $_SESSION['username'] = $dataLogin['username'];
            $_SESSION['status'] = "login";
            $_SESSION['pesan'] = "sukses";
            header("location:index.php");
        }else{
            header("location:login.php?pesan=nonaktif");
        }
    }else{
        header("location:login.php?pesan=gagal");
    }
}
?>