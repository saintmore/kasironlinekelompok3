<?php
session_start();
// cek apakah sudah login
if($_SESSION['status'] != 'login'){
    header("location:login.php?pesan=gagal");
}

require_once("pustaka/MyFunction.php");
require_once("config/Database.php");
$database = new Database();
$db = $database->Connect();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Master Data</title>
    <meta charset="utf-8">
    <meta name="description" content="Menu Utama"/>
    <meta name="theme-color" content="#e8315f"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="image/mabrukIcon.png">
    <link rel="manifest" href="manifest.json">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>   
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="DataTables/DataTables-1.10.20/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="DataTables/DataTables-1.10.20/css/buttons.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="DataTables/DataTables-1.10.20/css/select.dataTables.min.css"/>
	<script type="text/javascript" src="DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="DataTables/DataTables-1.10.20/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="DataTables/DataTables-1.10.20/js/jszip.min.js"></script>
    <script type="text/javascript" src="DataTables/DataTables-1.10.20/js/dataTables.select.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
                <h1 style="text-align:center; color:green; text-decoration:underline;">Master Data Barang</h1>
        </div>
        <br/>
        <br/>
        <button type="button" onclick="kembaliHome()" class="btn btn-success"><span class="glyphicon glyphicon-home"></span></button>
        <br/>
        <br/>
        <div class="panel panel-primary">
            <div class="panel-body">
                <br/><br/>
                <div class="table-responsive">
                    <table id="table1" class="table table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                            <th width="15%">NO.</th>
                            <th width="18%">NAMA BARANG</th>
                            <th width="28%">KODE BARANG</th>
                            <th width="17%">STOCK BARANG</th>
                            <th width="12%">HARGA BARANG (Rp)</th>
                            </tr>
                        </thead>					
                    </table>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <div class="modal fade" id="modalEditData">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modaleditdata">EDIT DATA</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="form-horizontal" id="formEditData">
                        <input type="hidden" id="IDeditDataRekap" name="IDeditDataRekap">
                        <div class="form-group">
                                <label style="text-align: left;" for="nama_barang" class="col-sm-3 control-label">NAMA BARANG</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>         
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left;" for="kode_barang" class="col-sm-3 control-label">KODE BARANG</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left;" for="stock_barang" class="col-sm-3 control-label">STOCK BARANG</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="stock_barang" placeholder="Input Stock" name="stock_barang" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left;" for="harga_barang" class="col-sm-3 control-label">HARGA BARANG</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="harga_barang" placeholder="Input harga" name="harga_barang" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button class="btn btn-success" onclick="editSimpan()" type="button">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalAddData">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modaleditdata">ADD DATA</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="form-horizontal" id="formAddData">
                            <div class="form-group">
                                <label style="text-align: left;" for="nama_barang" class="col-sm-3 control-label">NAMA BARANG</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>         
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left;" for="kode_barang" class="col-sm-3 control-label">KODE BARANG</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left;" for="stock_barang" class="col-sm-3 control-label">STOCK BARANG</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="stock_barang" placeholder="Input Stock" name="stock_barang" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left;" for="harga_barang" class="col-sm-3 control-label">HARGA BARANG</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="harga_barang" placeholder="Input harga" name="harga_barang" required>
                                </div>
                            </div>
                            <input type="hidden" id="add" name="add">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button class="btn btn-success" onclick="addSimpan()" type="button">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalHapusData">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalhapus">CONFIRM DELETE</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="form-horizontal" id="formHapusData">
                            <input type="hidden" id="idHapusData" name="idHapusData">
                            <center><label for="confirm" class="control-label">Apakah Anda Yakin Untuk Menghapus Data ?</label></center>
                            <br/>
                            <br/>
                            <div class="form-group">
                                <div class="col-sm-5"></div>
                                <div class="col-sm-1">
                                    <button class="btn btn-danger" type="button" onclick="hapusData()">YES</button>
                                </div>
                                <div class="col-sm-6">
                                    <button class="btn btn-info" type="button" data-dismiss="modal">NO</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
	    </div>
    </div>    
    <script type="text/javascript">
		$(document).ready(function(){	        
	      	$.ajax({
		        url: "<?= baseUrl()."ambilDataMaster.php"?>",
		        success : function(result){
		          	var table1 = $('#table1').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    text: 'EDIT',                                    
                                    action: function ( e, dt, node, config ) {
                                        var pilihEdit = table1.rows({selected : true}).data();
                                        if(pilihEdit.length > 1){
                                            alert("Mohon pilih 1 baris data");
                                        }else{
                                            var isiEdit = pilihEdit[0];
                                            showDetail(isiEdit[0]);
                                        }
                                    }
                                },
                                {
                                    text: 'DELETE',                                    
                                    action: function ( e, dt, node, config ) {
                                        var pilihDel = table1.rows({selected : true}).data();
                                        if(pilihDel.length > 1){
                                            alert("Mohon pilih 1 baris data");
                                        }else{
                                            var isiDel = pilihDel[0];
                                            showDelete(isiDel[0]);
                                        }
                                    }
                                },
                                {
                                    text: 'ADD',                                    
                                    action: function ( e, dt, node, config ) {
                                        var pilihAdd = table1.rows({selected : true}).data();
                                        if(pilihAdd.length > 1){
                                            alert("Mohon pilih 1 baris data");
                                        }else{
                                            showAdd();
                                        }
                                    }
                                }
                            ],
                            select : true
                        });
		          	var res = JSON.parse(result);
		          	var no = 1;
		          	res.forEach(function(data){
		              	table1.row.add(
		              	[
                        data.id,
		                data.nama_barang,
		                data.kode_barang,
                        data.stock_barang,
						formatNumber(data.harga_barang),
		              	]).draw();
		            });
		        }					
	      	});
        });

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        function hapusData(){
            var seriHapus = $('#formHapusData').serialize();
            $.ajax({
                type : 'POST',
                url : 'edit_input_data.php',
                data : seriHapus,
                success : function(response){
                    alert("Data Berhasil dihapus",location.reload());
                }
            })
        }
        
        function showDelete(id_request){
            $('#modalHapusData').modal('show');
            $('#idHapusData').val(id_request);
        }

        function showAdd(){
            $('#modalAddData').modal('show');
        }

        function showDetail(id_request){
            $('#modalEditData').modal('show');
            $.ajax({
                url: "<?= baseUrl().'edit_input_data.php?idRekap='?>"+id_request,
                success : function(result){
                    var obj = JSON.parse(result);
                    console.log(obj);
                    $("#IDeditDataRekap").val(obj.id);
                    $("#nama_barang").val(obj.nama_barang);
                    $("#kode_barang").val(obj.kode_barang);
                    $("#stock_barang").val(obj.stock_barang);
                    $("#harga_barang").val(obj.harga_barang);
                }                
            });
        }
        
        function editSimpan(){
            var seriEdit = $("#formEditData").serialize();    
            console.log("simpan " + seriEdit);        
			$.ajax({
				type: 'POST',
				url: 'edit_input_data.php',
				data: seriEdit,
				success: function(response){
                    alert("Data Berhasil diubah",location.reload());
				}
			});
        }

        function addSimpan(){
            var seriEdit = $("#formAddData").serialize();            
			$.ajax({
				type: 'POST',
				url: 'edit_input_data.php',
				data: seriEdit,
				success: function(response){
                    console.log("masuk " + response);
                    alert("Data Berhasil ditambah",location.reload());
				}
			});
        }

        function kembaliHome(){
            location.replace('<?=baseUrl()."index.php"?>');
        }   
	</script>	    
</body>
</html>