<?php

require '../config/database.php';

$id = $conn->real_escape_string($_POST['id']);

$sql = "SELECT id, nombre, descripcion, id_autos FROM pista WHERE id=$id LIMIT 1";
$resultado = $conn->query($sql);
$rows = $resultado->num_rows;

$pista = [];

if($rows > 0){
    $pista = $resultado->fetch_array();
}

echo json_encode($pista, JSON_UNESCAPED_UNICODE);