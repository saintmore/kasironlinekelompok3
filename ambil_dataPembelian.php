<?php
require_once("pustaka/MyFunction.php");
require_once("config/Database.php");
$database = new Database();
$db = $database->Connect();

$getData = $db->prepare("SELECT * FROM pembelian where id_label=:id");
$getData->bindParam(':id', $_GET['id']);
$getData->execute();
$data = $getData->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);