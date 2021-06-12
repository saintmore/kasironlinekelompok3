<?php
require_once("config/Database.php");
require_once("pustaka/MyFunction.php");
$database = new Database();
$db = $database->Connect();

	if(isset($_POST['nama_barang']) AND isset($_POST['kode_barang']) AND isset($_POST['stock_barang']) AND isset($_POST['harga_barang']) AND isset($_POST['add'])){
		$nama_barang = antiInject($_POST['nama_barang']);
		$kode_barang = antiInject($_POST['kode_barang']);
		$stock_barang = antiInject($_POST['stock_barang']);
		$harga_barang = antiInject($_POST['harga_barang']);

		$insTemp = $db->prepare("INSERT INTO master_data (nama_barang, kode_barang, stock_barang, harga_barang) VALUES (:nama_barang, :kode_barang, :stock_barang, :harga_barang)");
		$insTemp->bindParam(':nama_barang', $nama_barang);
		$insTemp->bindParam(':kode_barang', $kode_barang);
		$insTemp->bindParam(':stock_barang', $stock_barang);
		$insTemp->bindParam(':harga_barang', $harga_barang);
		$insTemp->execute();
		$idInput = $db->lastInsertId();
		echo $idInput;
	}

	if(isset($_GET['idRekap'])){
		$id = antiInject($_GET['idRekap']);
		$dataEdit = $db->prepare("SELECT * FROM master_data WHERE id = :id");
		$dataEdit->bindParam(':id', $id);
		$dataEdit->execute();
		echo json_encode($dataEdit->fetch(PDO::FETCH_ASSOC));
	}
	
	if(isset($_POST['IDeditDataRekap']) AND isset($_POST['nama_barang']) AND isset($_POST['kode_barang']) AND isset($_POST['stock_barang']) AND isset($_POST['harga_barang'])){
		$dataUpdate = array(antiInject($_POST['nama_barang']),antiInject($_POST['kode_barang']),antiInject($_POST['stock_barang']),antiInject($_POST['harga_barang']), antiInject($_POST['IDeditDataRekap']));
		$upd = $db->prepare("UPDATE master_data SET nama_barang = ?, kode_barang = ?, stock_barang = ?, harga_barang = ? WHERE id = ?");
		$upd->execute($dataUpdate);
	}

	if(isset($_POST['idHapusData'])){
		$idHapusData = antiInject($_POST['idHapusData']);
		$hapus = $db->prepare("DELETE FROM master_data WHERE id = :idHapus");
		$hapus->bindParam(':idHapus', $idHapusData);
		$hapus->execute();
	}

	//kasir
	if(isset($_POST['nama_barang']) AND isset($_POST['quantity']) AND isset($_POST['harga_barang']) AND isset($_POST['addKasir'])){
		$nama_barang = antiInject($_POST['nama_barang']);
		$quantity = antiInject($_POST['quantity']);
		$harga_barang = antiInject($_POST['harga_barang']);
		$id_label = antiInject($_POST['addKasir']);
		$total = $harga_barang*$quantity;
		
		$cekStock = $db->prepare("SELECT stock_barang FROM master_data WHERE nama_barang = :nama_barang");
		$cekStock->bindParam(':nama_barang', $nama_barang);
		$cekStock->execute();
		$stock_barang = ($cekStock->fetch(PDO::FETCH_ASSOC));
		// error_log("stockbarang".$stock_barang);
		error_log($stock_barang['stock_barang']." asdadasd ".$quantity );
		if ($stock_barang['stock_barang'] < $quantity) {
			echo 0;
		} else {
			$cekbarang = $db->prepare("SELECT id FROM pembelian WHERE nama_barang = :nama_barang AND id_label = :id_label");
			$cekbarang->bindParam(':nama_barang', $nama_barang);
			$cekbarang->bindParam(':id_label', $id_label);
			$cekbarang->execute();
			$cb = ($cekbarang->fetchAll(PDO::FETCH_ASSOC));

			if (Count($cb) < 1) {
				
				$insTemp = $db->prepare("INSERT INTO pembelian (id_label, nama_barang, quantity, harga_barang, total) VALUES (:id_label, :nama_barang, :quantity , :harga_barang, :total)");
				$insTemp->bindParam(':id_label', $id_label);
				$insTemp->bindParam(':nama_barang', $nama_barang);
				$insTemp->bindParam(':quantity', $quantity);
				$insTemp->bindParam(':harga_barang', $harga_barang);
				$insTemp->bindParam(':total', $total);
				$insTemp->execute();
				$idInput = $db->lastInsertId();
				echo $idInput;
			} else {
				echo 0;
			}
		}
	}

	if(isset($_GET['idKasir'])){
		$id = antiInject($_GET['idKasir']);
		$dataEdit = $db->prepare("SELECT * FROM pembelian WHERE id = :id");
		$dataEdit->bindParam(':id', $id);
		$dataEdit->execute();
		echo json_encode($dataEdit->fetch(PDO::FETCH_ASSOC));
	}
	
	if(isset($_GET['idBarang'])){
		$id = antiInject($_GET['idBarang']);
		$dataEdit = $db->prepare("SELECT harga_barang FROM master_data WHERE nama_barang = :id");
		$dataEdit->bindParam(':id', $id);
		$dataEdit->execute();
		echo json_encode($dataEdit->fetch(PDO::FETCH_ASSOC));
	}
	
	// if(isset($_POST['idEditKasir']) AND isset($_POST['nama_barang']) AND isset($_POST['quantity']) AND isset($_POST['harga_barang'])){
	// 	$dataUpdate = array(antiInject($_POST['nama_barang']),antiInject($_POST['quantity']),antiInject($_POST['harga_barang']), antiInject($_POST['idEditKasir']));
	// 	$upd = $db->prepare("UPDATE master_data SET nama_barang = ?, quantity = ?,  harga_barang = ? WHERE id = ?");
	// 	$upd->execute($dataUpdate);
	// }

	if(isset($_POST['idHapusKasir'])){
		$idHapusKasir = antiInject($_POST['idHapusKasir']);
		$hapus = $db->prepare("DELETE FROM pembelian WHERE id = :idHapus");
		$hapus->bindParam(':idHapus', $idHapusKasir);
		$hapus->execute();
	}

	if (isset($_GET['idGetTotal'])) {
		$id = antiInject($_GET['idGetTotal']);
		$dataEdit = $db->prepare("SELECT total FROM pembelian WHERE id_label = :id");
		$dataEdit->bindParam(':id', $id);
		$dataEdit->execute();
		$data = $dataEdit->fetchAll(PDO::FETCH_ASSOC);
		$totalTampungan = 0;

		foreach ($data as $d) {
			$totalTampungan += $d['total'];
		}

		echo json_encode($totalTampungan);
	}
?>		