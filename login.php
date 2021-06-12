<!DOCTYPE html>
<html>
<head>
    <title>Form Login</title>
    <meta charset="utf-8">
    <meta name="description" content="Form Login"/>
    <meta name="theme-color" content="#e8315f"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/jquery.min.js"></script>
    <link rel="icon" href="image/mabrukIcon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>    
    <div class="container">
        <div class="row">
            <br/><br/>
            <button type="button" data-toggle="tooltip" title="Ganti Password" id="tombolGantiPass" onclick="gantiPassword()" class="btn btn-success"><span class="glyphicon glyphicon-lock"></span></button>
        </div>
        <center>
            <img src="image/mabrukIcon.png" alt="Icon Mabruk" style="width: 256px;height: 256px;">
        </center>
        <!-- <h2 align="center"><b>DATABASE</b></h2> -->
        <div class="col-md-4 col-md-offset-4">
            <?php
                if(isset($_GET['pesan'])){
                    if($_GET['pesan']=="gagal"){
                        echo "<div class='alert alert-danger'>Login gagal! username atau password salah!</div>";
                    }else if($_GET['pesan']=="logout"){
                        echo "<div class='alert alert-info'>Anda telah berhasil logout</div>";
                    }else if($_GET['pesan']=="belum_login"){
                        echo "<div class='alert alert-danger'>Anda harus login untuk akses ke halaman admin</div>";
                    }else if($_GET['pesan']=="nonaktif"){
                        echo "<div class='alert alert-danger'>Username Anda telah di nonaktifkan</div>";
                    }
                }
            ?>
            <br/>
            <form class="form-horizontal" method="post" action="proses_login.php">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="email">USERNAME :</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" onfocus="this.value=''" id="username" placeholder="Masukkan Username Anda" name="username">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="pwd">PASSWORD :</label>
                    <div class="col-sm-6">          
                        <input type="password" class="form-control" onfocus="this.value=''" id="password" placeholder="Masukkan Password Anda" name="password">
                    </div>
                </div>    
                <div class="form-group">        
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-success">SIGN IN</button>
                        <button type="button" onclick="tambahUser()" class="btn btn-success">DAFTAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalUser">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modaluser">Tambah User</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="tambahUser.php">
                        <input type="hidden" name="posisi" value="relawan" id="posisi">
                        <div class="form-group">
                            <label for="userName" class="col-sm-2 control-label">Username</label>
                            <input type="text" class="form-control" onfocus="this.value=''" id="userName" placeholder="Input Username" name="userName" required>
                        </div>
                        <div class="form-group">
                            <label for="passWord" class="col-sm-2 control-label">Password</label>
                            <input type="password" class="form-control" onfocus="this.value=''" id="passWord" placeholder="Input Password" name="passWord" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-info" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalGantiPass">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalgantipass">Ganti Password</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" action="tambahUser.php">
                        <div class="form-group">
                            <label for="userName" style="text-align: left" class="col-sm-4 control-label">Username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" onfocus="this.value=''" id="userNameGanti" placeholder="Input Username" name="userNameGanti" required>
                            </div>                
                        </div>
                        <div class="form-group">
                            <label for="passWord" style="text-align: left" class="col-sm-4 control-label">Password Lama</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" onfocus="this.value=''" id="passWordLama" placeholder="Input Password" name="passWordLama" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="passWord" style="text-align: left" class="col-sm-4 control-label">Password Baru</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" onfocus="this.value=''" id="passWordBaru" placeholder="Input Password" name="passWordBaru" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <button class="btn btn-info" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function tambahUser(){
            $('#modalUser').modal('show');
        }

        function gantiPassword(){
            $("#modalGantiPass").modal("show");
        }
    </script>
</body>
</html>
