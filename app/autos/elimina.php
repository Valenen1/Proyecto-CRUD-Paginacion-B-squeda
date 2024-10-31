<?php

session_start();

require '../config/database.php';

$id = $conn->real_escape_string($_POST['id']);

$sql = "DELETE FROM pista WHERE id=$id";

if($conn->query($sql)){


    $dir = "vistas";
    $vista = $dir . '/' . $id . '.jpg';

    if(file_exists($vista)){
        unlink($vista);
    }

    $_SESSION['color'] = "success";
    $_SESSION['msg'] = "Registro eliminado";
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error al elminiar registo";
}

header('Location: index.php');
