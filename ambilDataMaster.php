<?php
require_once("pustaka/MyFunction.php");
require_once("config/Database.php");
$database = new Database();
$db = $database->Connect();

$getData = $db->prepare("SELECT * FROM master_data");
$getData->execute();
$data = $getData->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);