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

$id = @$_GET['id'];
if (isset($_GET['new'])) {
    $cek = $db->prepare("SELECT id FROM label_pembelian WHERE label = :label");
    $cek->bindParam(':label', $_GET['new']);
    $cek->execute();
    $cekin = $cek->fetchAll(PDO::FETCH_ASSOC);

    $id = $cekin[0]['id'];
    if ($cekin == []) {
        $insTemp = $db->prepare("INSERT INTO label_pembelian (label, status) VALUES (:label, 0)");
        $insTemp->bindParam(':label', $_GET['new']);
        $insTemp->execute();
        $idInput = $db->lastInsertId();
        $id = $idInput;
    }
}

if (isset($_GET['tampungTotal']) AND isset($_GET['cash']) AND isset($_GET['id'])) {
    $id = antiInject($_GET['id']);
    $total_barang = antiInject($_GET['tampungTotal']);
    $cash = antiInject($_GET['cash']);
    $data_barang = $db->prepare("SELECT * FROM pembelian WHERE id_label = :id");
    $data_barang->bindParam(':id', $id);
    $data_barang->execute();
    $barang = $data_barang->fetchAll(PDO::FETCH_ASSOC);
    $status=1;
    $arrayTampungan=array();
    foreach ($barang as $bb) {
		$dataEdit = $db->prepare("SELECT stock_barang FROM master_data WHERE nama_barang = :nama_barang");
		$dataEdit->bindParam(':nama_barang', $bb['nama_barang']);
		$dataEdit->execute();
        $de = $dataEdit->fetch(PDO::FETCH_ASSOC);

        if ($bb['quantity'] > $de['stock_barang']) {
            echo '<script type="text/javascript">',
                    'alertKasir('.$bb['nama_barang'].' ,Stock Tidak Mencukupi!'.');',
                    '</script>';
            break;
        }
        $status=0;
        $kurangSnQ = $de['stock_barang']-$bb['quantity'];
        $arrayTampungan += array($bb['nama_barang'] => $kurangSnQ);
    }
    if ($status=1) {
        foreach($barang as $brg) {
            $arrE = array($arrayTampungan[$brg['nama_barang']], $brg['nama_barang']);
            $upd = $db->prepare("UPDATE master_data SET stock_barang = ? WHERE nama_barang = ?");
		    $upd->execute($arrE);
        }

        $arrP = array($total_barang, $cash,$id);
        $udateLabel = $db->prepare("UPDATE label_pembelian SET total_transaksi = ?, cash = ?, status = 1 WHERE  id = ?");
        $udateLabel->execute($arrP);

        // header("location:pra_kasir.php");
        // echo '<script type="text/javascript">',
        //             'alertKasirBerhasil('."Pembelian Barang Berhasil!".');',
        //             '</script>';
        echo '<script type="text/javascript">'; 
        echo 'alert("Pembelian Barang Berhasil!");'; 
        echo 'console.log("kerubahh");';
        echo 'document.location.href = "pra_kasir.php";';
        echo '</script>';
    }
}



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
                <h1 style="text-align:center; color:green; text-decoration:underline;">Kasir</h1>
        </div>
        <br/>
        <br/>
        <button type="button" onclick="kembaliHome()" class="btn btn-success"><span class="glyphicon glyphicon-arrow-left"></span></button>
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
                            <th width="12%">HARGA BARANG (Rp)</th>
                            <th width="12%">QUANTITY</th>
                            <th width="12%">Total</th>
                            </tr>
                        </thead>					
                    </table>
                </div>
            </div>
        </div>
        <div class="panel panel-success">
            <div class="panel-body">
                <br/><br/>
                <!-- <div class="row"> -->
                <form method="get" class="form-horizontal" action="kasir.php">
                <div class="form-group">
                    <label style="text-align: left;" for="total_barang" class="col-sm-3 control-label">TOTAL BARANG</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="total_barang" placeholder="" name="total_barang" required readonly>
                        </div>
                </div>
                <div class="form-group">
                    <label style="text-align: left;" for="cash" class="col-sm-3 control-label">Cash</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="cash" placeholder="" name="cash" onkeyup="hitungKembalian()">
                        </div>
                </div>
                <div class="form-group">
                    <label style="text-align: left;" for="kembalian" class="col-sm-3 control-label">Kembalian</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kembalian" placeholder="" name="kembalian" required readonly>
                        </div>
                </div>
                <input type="hidden" id="tampungTotal" name="tampungTotal">
                <input type="hidden" id="id" name="id" value="<?=$id;?>">
                <div class="row">
                    <div class="col-sm-2"><input type="submit" value="CheckOut" class="btn btn-success"></div>
                </div>
                </form>
                <!-- <div class="form-group">
                    <p>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            Cek Kembalian
                        </button>
                    </p>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                        <label style="text-align: left;" for="cash" class="col-sm-3 control-label">Cash</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="total_barang" placeholder="" name="total_barang" required readonly>
                        </div>
                        </div>
                    </div>
                </div> -->
                <!-- </div> -->
            </div>
        </div>
        <br/>
        <br/>
        <!-- Ga dipake dulu yah -->
        <!-- <div class="modal fade" id="modalEditData">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modaleditdata">EDIT DATA</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="form-horizontal" id="formEditData">
                        <input type="hidden" id="idEditKasir" name="idEditKasir">
                        <div class="form-group">
                                <label style="text-align: left;" for="nama_barang" class="col-sm-3 control-label">NAMA BARANG</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>         
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left;" for="harga_barang" class="col-sm-3 control-label">HARGA BARANG</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="harga_barang" placeholder="Input harga" name="harga_barang" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left;" for="quantity" class="col-sm-3 control-label">QUANTITY</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="quantity" placeholder="Input quantity" name="quantity" required>
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
        </div> -->
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
                                    <!-- <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>          -->
                                    <select class="form-control" name="nama_barang" id="nama_barang" onchange="loadHarga(value);">
                                    <option value="">Please Select</option>
                                    <?php  
                                        $ambilBarang = $db->prepare("SELECT nama_barang FROM master_data");
                                        $ambilBarang->execute();
                                        $barang = $ambilBarang->fetchAll(PDO::FETCH_ASSOC);

                                        foreach($barang as $b) {
                                    ?>
                                    <option value="<?=$b['nama_barang'];?>"><?=$b['nama_barang'];?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left;" for="harga_barang" class="col-sm-3 control-label">HARGA BARANG</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="harga_barang" placeholder="Input harga" name="harga_barang" required readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left;" for="quantity" class="col-sm-3 control-label">QUANTITY</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="quantity" placeholder="Input quantity" name="quantity" required>
                                </div>
                            </div>
                            <input type="hidden" id="addKasir" name="addKasir" value="<?=$id?>">
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
                            <input type="hidden" id="idHapusKasir" name="idHapusKasir">
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
		        url: "<?= baseUrl()."ambil_dataPembelian.php?id=".$id?>",
		        success : function(result){
		          	var table1 = $('#table1').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                // {
                                //     text: 'EDIT',                                    
                                //     action: function ( e, dt, node, config ) {
                                //         var pilihEdit = table1.rows({selected : true}).data();
                                //         if(pilihEdit.length > 1){
                                //             alert("Mohon pilih 1 baris data");
                                //         }else{
                                //             var isiEdit = pilihEdit[0];
                                //             showDetail(isiEdit[0]);
                                //         }
                                //     }
                                // },
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
						formatNumber(data.harga_barang),
                        data.quantity,
                        formatNumber(data.total),
		              	]).draw();
		            });
		        }					
	      	});
              console.log("keter");
            $.ajax({
                url: "<?= baseUrl().'edit_input_data.php?idGetTotal='.$id?>",
                success : function(result){
                    $("#total_barang").val("Rp. "+formatNumber(result));
                    $("#tampungTotal").val(result);
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
            $('#idHapusKasir').val(id_request);
        }

        function showAdd(){
            $('#modalAddData').modal('show');
        }

        // function showDetail(id_request){
        //     $('#modalEditData').modal('show');
        //     $.ajax({
        //         url: "<?= baseUrl().'edit_input_data.php?idKasir='?>"+id_request,
        //         success : function(result){
        //             var obj = JSON.parse(result);
        //             $("#idEditKasir").val(obj.id);
        //             $("#nama_barang").val(obj.nama_barang);
        //             $("#harga_barang").val(obj.harga_barang);
        //             $("#quantity").val(obj.quantity);
        //         }                
        //     });
        // }
        
        // function editSimpan(){
        //     var seriEdit = $("#formEditData").serialize();    
        //     console.log("simpan " + seriEdit);        
		// 	$.ajax({
		// 		type: 'POST',
		// 		url: 'edit_input_data.php',
		// 		data: seriEdit,
		// 		success: function(response){
        //             alert("Data Berhasil diubah",location.reload());
		// 		}
		// 	});
        // }

        function addSimpan(){
            var seriEdit = $("#formAddData").serialize();            
			$.ajax({
				type: 'POST',
				url: 'edit_input_data.php',
				data: seriEdit,
				success: function(response){
                    console.log("responnya gannn " + response);
                    if (response == 0) {
                        alert("Gagal!, Stock tidak mencukupi!. Cek Master Data Sekarang!",location.reload());
                    } else {
                        alert("Data Berhasil ditambah",location.reload());
                    }
				}
			});
        }

        function alertKasir(pesan) {
            alert(pesan,location.reload());
        }
        function alertKasir(pesan) {
            alert(pesan,location.reload());
        }
        function loadHarga($nb) {
            $.ajax({
                url: "<?= baseUrl().'edit_input_data.php?idBarang='?>"+$nb,
                success : function(result){
                    var obj = JSON.parse(result);
                    $("#harga_barang").val(obj.harga_barang);
                }                
            });
            alert(selectedValue);
        }

        function hitungKembalian() {
            var x = document.getElementById("cash").value;
            var f = document.getElementById("tampungTotal").value;
            var t = x-f;
            $("#kembalian").val("Rp. " + formatNumber(t));
            console.log(x);
        }

        function cekStock() {
            $.ajax({
                url: "<?= baseUrl().'edit_input_data.php?cekStock='?>"+$nb,
                success : function(result){
                    var obj = JSON.parse(result);
                    $("#harga_barang").val(obj.harga_barang);
                }                
            });
        }

        function kembaliHome(){
            location.replace('<?=baseUrl()."pra_kasir.php"?>');
        }   
	</script>	    
</body>
</html>