<?php

session_start();

require '../config/database.php';

$nombre = $conn->real_escape_string($_POST['nombre']);
$descripcion = $conn->real_escape_string($_POST['descripcion']);
$autos = $conn->real_escape_string($_POST['autos']);

$sql = "INSERT INTO pista (nombre, descripcion, id_autos, fecha_creacion) 
        VALUES('$nombre', '$descripcion', '$autos', NOW())";
if($conn->query($sql)){
    $id = $conn->insert_id;

    $_SESSION['color'] = "success";
    $_SESSION['msg'] = "Registro guardado";

    if($_FILES['vista']['error'] == UPLOAD_ERR_OK){
        $permitidos = array("image/jpg", "image/jpeg");
        if(in_array($_FILES['vista']['type'], $permitidos)){

            $dir = "vistas";

            $info_img = pathinfo($_FILES['vista']['name']);
            $info_img['extension']; 

            $vista = $dir . '/' . $id . '.jpg';

            if(!file_exists($dir)){
                mkdir($dir, 0777);
            }

            if(!move_uploaded_file($_FILES['vista']['tmp_name'], $vista)){
                $_SESSION['color'] = "danger";
                $_SESSION['msg'] .= "<br>Error al guardar imagen";
            }
        } else {
            $_SESSION['color'] = "danger";
            $_SESSION['msg'] .= "<br>Formato de imagen no permitido";
        }
    }
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error al guardar imagen";
}

header('Location: index.php');

