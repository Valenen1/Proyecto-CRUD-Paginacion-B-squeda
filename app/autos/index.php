<?php


session_start();

require '../config/database.php';

$sqlPistas = "SELECT p.id, p.nombre, p.descripcion, a.nombre AS autos FROM pista AS p 
INNER JOIN autos AS a 
ON p.id_autos=a.id";

$pistas = $conn->query($sqlPistas);

$dir = "vistas/";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Modal</title>

    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="container py-3">

        <h2 class="text-center"> Pistas </h2>

        <hr>

        
        <?php if(isset($_SESSION['msg']) && isset($_SESSION['color'])) { ?>
            <div class="alert alert-<?= $_SESSION['color']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        
        <?php 
        unset($_SESSION['color']);
        unset($_SESSION['msg']);
        } ?>


        <div class="row g-4">

        <div class="col-auto">
            <label for="num_registros" class="col-form-label">Mostrar: </label>
        </div>
        <div class="col-auto">
            <select name="num_registros" id="num_registros" class="form-select">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div class="col-auto">
            <label for="num_registros" class="col-form-label">registros </label>
        </div>

        <div class="col-5"></div>

        <div class="col-auto">
            <label for="campo" class="col-form-label">Buscar: </label>
        </div>
        <div class="col-auto">
            <input type="text" name="campo" id="campo" class="form-control">
        </div>
        </div>

        <hr>
        
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <a href="./Pagina_carey/Pagina_Carey.html" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left"></i> Volver a la página Principal
                </a>
            </div>
            <div class="col-auto">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoModal">
                    <i class="fa-solid fa-square-plus"></i> Nuevo Registro
                </a>
            </div>
        </div>


        <table class="table table-sm table-striped table-hover mt-4">
            <thead class="table-dark">
                <tr>
                    <th class="sort asc">#</th>
                    <th class="sort asc">Nombre</th>
                    <th class="sort asc">Descripcion</th>
                    <th class="sort asc">Id Autos</th>
                    <th class="sort asc">Fecha</th>
                    <th>Vista pista</th>
                    <th class="sort asc">Accion</th>
                </tr>
            </thead>

            <tbody id="content">

                <?php while ($row_pista = $pistas->fetch_assoc()){ ?>
                    <tr>
                    </tr>

                <?php }?>

            </tbody>
        </table>

        <div class="row">
                <div class="col-6">
                    <label id="lbl-total"></label>
                </div>

                <div class="col-6" id="nav-paginacion"></div>

                <input type="hidden" id="pagina" value="1">
                <input type="hidden" id="orderCol" value="0">
                <input type="hidden" id="orderType" value="asc">
        </div>

        <?php 
        $sqlAutos = "SELECT ID, Nombre FROM autos";
        $autos = $conn->query($sqlAutos)
        ?>

    </div>

    <?php include 'nuevoModal.php'; ?>

    <?php $autos->data_seek(0); ?>

    <?php include 'editaModal.php'; ?>
    <?php include 'eliminaModal.php'; ?>

    <script>
        let nuevoModal = document.getElementById('nuevoModal')
        let editarModal = document.getElementById('editaModal')
        let eliminaModal = document.getElementById('eliminaModal')

        nuevoModal.addEventListener('shown.bs.modal', event => {
            nuevoModal.querySelector('.modal-body #nombre').focus()
        })


        nuevoModal.addEventListener('hide.bs.modal', event => {
            nuevoModal.querySelector('.modal-body #nombre').value =""
            nuevoModal.querySelector('.modal-body #descripcion').value =""
            nuevoModal.querySelector('.modal-body #autos').value =""
            nuevoModal.querySelector('.modal-body #vista').value =""

        })

        editarModal.addEventListener('hide.bs.modal', event => {
            editarModal.querySelector('.modal-body #nombre').value =""
            editarModal.querySelector('.modal-body #descripcion').value =""
            editarModal.querySelector('.modal-body #autos').value =""
            editarModal.querySelector('.modal-body #vista').value =""
            editarModal.querySelector('.modal-body #img_vista').value =""

        })

        editarModal.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget 
            let id = button.getAttribute('data-bs-id')

            let inputId = editarModal.querySelector('.modal-body #id')
            let inputNombre = editarModal.querySelector('.modal-body #nombre')
            let inputDescripcion = editarModal.querySelector('.modal-body #descripcion')
            let inputAutos = editarModal.querySelector('.modal-body #autos')
            let vista = editarModal.querySelector('.modal-body #img_vista')

            let url = "getAuto.php"
            let formData = new FormData()
            formData.append('id', id)

            fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputNombre.value = data.nombre
                inputDescripcion.value = data.descripcion
                inputAutos.value = data.id_autos
                vista.src = '<?= $dir ?>' + data.id + '.jpg'

            }).catch(err => console.log(err))

        })

        eliminaModal.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget 
            let id = button.getAttribute('data-bs-id')
            eliminaModal.querySelector('.modal-footer #id').value = id
        })

        // Llamando a la funcion getData
        getData()

        //Escuchar un evento keyup en el campo de entrada y luego llamar a la funcion getData
        document.getElementById("campo").addEventListener("keyup", function(){
            getData()
        }, false)
        document.getElementById("num_registros").addEventListener("change", function(){
            document.getElementById('pagina').value = 1; // Aquí restableces la página
            getData()
        }, false)
         

        // Peticion AJAX
        function getData(){
            let input = document.getElementById("campo").value
            let num_registros = document.getElementById("num_registros").value
            let content = document.getElementById("content")
            let pagina = document.getElementById("pagina").value
            let orderCol = document.getElementById("orderCol").value
            let orderType = document.getElementById("orderType").value

            if(pagina == null){
                pagina = 1
            }

            let url = "load.php";
            let formData = new FormData()
            formData.append('campo', input)
            formData.append('registros', num_registros)
            formData.append('pagina', pagina )
            formData.append('orderCol', orderCol )
            formData.append('orderType', orderType )

            fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {
                content.innerHTML = data.data
                document.getElementById("lbl-total").innerHTML = 'Mostrando ' + data.totalFiltro + 
                ' de ' + data.totalRegistros + ' registros'
                document.getElementById("nav-paginacion").innerHTML = data.paginacion
            }).catch(err => console.log(err))
        }

        function nextPage(pagina){
            document.getElementById('pagina').value = pagina
            getData()
        }

        let columns = document.getElementsByClassName("sort")
        let tamanio = columns.length
        for(let i = 0; i < tamanio; i++){
            columns[i].addEventListener("click", ordenar)
        }

        function ordenar(e){
            let elemento = e.target

            document.getElementById('orderCol').value = elemento.cellIndex

            if(elemento.classList.contains("asc")){
                document.getElementById("orderType").value = "asc"
                elemento.classList.remove("asc")
                elemento.classList.add("desc")
            } else {
                document.getElementById("orderType").value = "desc"
                elemento.classList.remove("desc")
                elemento.classList.add("asc")
            }

            getData()
        }
    </script>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>