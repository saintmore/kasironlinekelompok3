<?php
    session_start();
    // cek apakah sudah login
    if($_SESSION['status'] != 'login'){
        header("location:login.php?pesan=gagal");
    }
    // cek role
    // if(!in_array('rekap_data',$_SESSION['role'])){
    //     header("location:index.php?pesan=forbidden");
	// }
	
    require_once("config/Database.php");
    require_once("pustaka/MyFunction.php");
	$database = new Database();
	$db = $database->Connect();
	// ambil data untuk select cabang
	$getCab = $db->prepare("SELECT * FROM label_pembelian where status=0");
	$getCab->execute();
    $dataCab = $getCab->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Input Label Pembelian</title>
    <meta charset="utf-8">
    <meta name="description" content="Input Label Pemberlian"/>
    <meta name="theme-color" content="#e8315f"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="image/mabrukIcon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery.min.js"></script>    
    <script src="js/bootstrap.min.js"></script>    
</head>
<body>    
    <div class="container">
            <h1 style="text-align:center; color:green; text-decoration:underline;">Input Label Pembelian atau Select Recent Pembelian</h1><br/>
        <button type="button" onclick="kembaliHome()" class="btn btn-success"><span class="glyphicon glyphicon-home"></span></button>
        <br/><br/>
        <form method="get" class="form-horizontal" action="kasir.php">
        <div class="col-sm-12">
        <div class="row">
        <div class="col-sm-10"><input type="text" name="new" id="new" placeholder="input new label" class="form-control"></div>
        <div class="col-sm-2"><input type="submit" class="btn btn-success"></div>
        </div>
        </div>
        </form>
        <br/>
        <br/>
        <br/>
        <h2 style="text-align:left; color:green; text-decoration:underline;">Recent</h2><br/>
         <br/>
        <?php
            for($i=0; $i<count($dataCab); $i++){
                echo '<div class="col-sm-12">
                            <a href="kasir.php?id='.str_rot13($dataCab[$i]['id']).'" role="button" class="btm btn-warning btn-lg btn-block center-block" style="text-align:center">'.$dataCab[$i]['label'].'</a><br/>
                      </div>';
            }
        ?>        		
    </div>
    <script>
        function kembaliHome(){
            location.replace('<?=baseUrl()."index.php"?>');
        }
	</script>			
</body>
</html>
