<?php

$conn = new mysqli("localhost", "root", "", "autos_dom");

if($conn->connect_error){
    die("Error de conexion" . $conn->connect_error);
}