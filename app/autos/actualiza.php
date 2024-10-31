<?php

session_start();

require '../config/database.php';

$id = $conn->real_escape_string($_POST['id']);
$nombre = $conn->real_escape_string($_POST['nombre']);
$descripcion = $conn->real_escape_string($_POST['descripcion']);
$autos = $conn->real_escape_string($_POST['autos']);

$sql = "UPDATE pista 
SET nombre ='$nombre', descripcion = '$descripcion', id_autos = $autos WHERE 
id=$id";

if($conn->query($sql)){

    $_SESSION['color'] = "success";
    $_SESSION['msg'] = "Registro actualizado";

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
    $_SESSION['msg'] = "Error al actualizar registro";
}

header('Location: index.php');
