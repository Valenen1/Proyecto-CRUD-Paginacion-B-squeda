<?php

require '../config/database.php';

$columns = [
    'id', 'nombre', 'descripcion', 'id_autos', 'fecha_creacion'];
$columnsWhere = [
    'id', 'nombre', 'descripcion', 'fecha_creacion'];


$table = "pista";

$dir = "vistas/";

$id = 'id';

$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']) : null;

$where = '';

if($campo != null){
    $where = "WHERE (";

    $cont = count($columnsWhere);
    for ($i = 0; $i < $cont; $i++) {
        $where .= $columnsWhere[$i] . " LIKE '%". $campo . "%' OR ";
    }
    $where = substr_replace($where, "", -3);
    $where .= ")";
}
// Limit

$limit = isset($_POST['registros']) ? $conn->real_escape_string($_POST['registros']) : 10;
$pagina = isset($_POST['pagina']) ? $conn->real_escape_string($_POST['pagina']) : 0;

if(!$pagina){
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $limit; 
}

$sLimit = "LIMIT $inicio , $limit";

//Ordenamientos

$sOrder = "";
if(isset($_POST['orderCol'])){
    $orderCol = $_POST['orderCol'];
    $orderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';

    $sOrder = "ORDER BY ". $columns[intval($orderCol)] . ' ' . $orderType;
}


// Consulta

$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
FROM $table
$where 
$sOrder
$sLimit";
$resultado = $conn->query($sql);
$num_rows = $resultado->num_rows;

// Consulta para total de registros filtrados

// Consulta para total de registros filtrados
$sqlFiltro = "SELECT FOUND_ROWS()";
$resFiltro = $conn->query($sqlFiltro);
$row_Filtro = $resFiltro->fetch_array();
$totalFiltro = $row_Filtro[0];

// Consulta total de registros filtrados
$sqlTotal = "SELECT COUNT($id) FROM $table $where";
$resTotal = $conn->query($sqlTotal);
$row_Total = $resTotal->fetch_array();
$totalRegistros = $row_Total[0];


// Mostrando Resultados
$output = [];
$output['totalRegistros'] = $totalRegistros;
$output['totalFiltro'] = $totalFiltro;
$output['data'] = '';
$output['paginacion'] = '';

if($num_rows > 0){
while($row = $resultado->fetch_assoc()){
    $output['data'] .= '<tr>';
    $output['data'] .= '<td>' . $row['id'] . '</td>';
    $output['data'] .= '<td>' . $row['nombre'] . '</td>';
    $output['data'] .= '<td style="max-width: 150px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">' . $row['descripcion'] . '</td>';
    $output['data'] .= '<td>' . $row['id_autos'] . '</td>';
    $output['data'] .= '<td>' . $row['fecha_creacion'] . '</td>';
    $output['data'] .= '<td><img src="' . $dir . $row['id'] . '.jpg?n=' . time() . '" width="100"></td>';
    $output['data'] .= '<td>'; 
    $output['data'] .= '<div class="d-flex flex-column">'; 
    $output['data'] .= '<a href="#" class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#editaModal" data-bs-id="' . $row['id'] . '"> <i class="fa-solid fa-pen"></i> Editar</a>';
    $output['data'] .= '<a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminaModal" data-bs-id="' . $row['id'] . '"> <i class="fa-solid fa-eraser"></i> Eliminar</a>';
    $output['data'] .= '</div>';
    $output['data'] .= '</td>'; 
    $output['data'] .= '</tr>'; 

}
} else {
    $output['data'] .= '<tr>';
    $output['data'] .= '<td  colspan="7">Sin resultados</td>';
    $output['data'] .= '</tr>';
}

if($output['totalRegistros'] > 0){
    $totalPaginas = ceil($output['totalRegistros'] / $limit);

    $output['paginacion'] .= '<nav>';
    $output['paginacion'] .= '<ul class="pagination">';

    $numeroInicio = 1;

    if(($pagina - 1) > 1){
        $numeroInicio = $pagina - 1; 
    }

    $numeroFin = $numeroInicio + 2;

    if($numeroFin > $totalPaginas){
        $numeroFin = $totalPaginas;
    }

    for($i = $numeroInicio; $i <= $numeroFin; $i++){
        if($pagina == $i){
            $output['paginacion'] .= '<li class="page-item active"><a class="page-link" href="#">' .$i. '</a></li>';
        } else{
            $output['paginacion'] .= '<li class="page-item"><a class="page-link" href="#" onclick="nextPage
            ('.$i.')">' .$i. '</a></li>';
        }
    }

    $output['paginacion'] .= '</ul>';
    $output['paginacion'] .= '</nav>';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);