<?php
    require_once("config/Database.php");
    require_once("pustaka/MyFunction.php");

    $database = new Database();
    $db = $database->Connect();

    $html = "<!DOCTYPE html>
            <html>
            <head>
            <title>Input User Baru</title>
            <meta charset='utf-8'>
            <meta name='description' content='Input User Baru'/>
            <meta name='theme-color' content='#e8315f'/>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link rel='icon' href='image/icon.png'>
            <link rel='manifest' href='manifest.json'>
            <link rel='stylesheet' href='css/bootstrap.min.css'>
            <script src='js/jquery.min.js'></script>
            <script src='js/bootstrap.min.js'></script>
            </head>
            <body>

            <div align='center'>
            ";  

    if(isset($_POST['userName']) AND isset($_POST['passWord'])){
        $username = antiInject($_POST['userName']);
        $password = md5(antiInject($_POST['passWord']));

        // cek user apakah sudah ada
        $cekUser = $db->prepare("SELECT * FROM ms_user WHERE username = :username");
        $cekUser->bindParam(':username', $username);
        $cekUser->execute();
        $hasilCek = $cekUser->rowCount();
        if($hasilCek > 0){
            echo $html."<h3 align='center' style='color:blue'>USERNAME ".$username." SUDAH TERDAFTAR, INPUT USERNAME LAIN</h3><form action='login.php' method='post'><input type='submit' class='btn btn-danger' value='Close'></form></div></body></html>";
        }else{
            // insert data
            $ins = $db->prepare("INSERT INTO ms_user (username,password,status,created_at) VALUES (:username, :password, 1,  now())");
            $ins->bindParam(':username', $username);
            $ins->bindParam(':password', $password);
            $ins->execute();
            echo $html."<h3 align='center' style='color:blue'>DATA BERHASIL DISIMPAN</h3><form action='login.php' method='post'><input type='submit' class='btn btn-success' value='Close'></form></div></body></html>";
        }        
    }

    if(isset($_POST['userNameGanti']) AND isset($_POST['passWordLama']) AND isset($_POST['passWordBaru'])){
        $userNameGanti = antiInject($_POST['userNameGanti']);
        $passWordLama = md5(antiInject($_POST['passWordLama']));
        $passWordBaru = md5(antiInject($_POST['passWordBaru']));

        $cariUserPass = $db->prepare("SELECT * FROM ms_user WHERE username = :username AND password = :password");
        $cariUserPass->bindParam(':username', $userNameGanti);
        $cariUserPass->bindParam(':password', $passWordLama);
        $cariUserPass->execute();
        $cariUserPass = $cariUserPass->rowCount();

        if($cariUserPass > 0){
            $updPass = $db->prepare("UPDATE ms_user SET password = :password WHERE username = :username ");
            $updPass->bindParam(':password', $passWordBaru);
            $updPass->bindParam(':username', $userNameGanti);
            $updPass->execute();
            echo $html."<h3 align='center' style='color:blue'>PASSWORD ANDA BERHASIL DIUBAH !</h3><form action='login.php' method='post'><input type='submit' class='btn btn-success' value='Close'></form></div></body></html>";
        }else{
            echo $html."<h3 align='center' style='color:blue'>USERNAME ATAU PASSWORD YANG ANDA MASUKKAN SALAH !</h3><form action='login.php' method='post'><input type='submit' class='btn btn-danger' value='Close'></form></div></body></html>";
        }
    }