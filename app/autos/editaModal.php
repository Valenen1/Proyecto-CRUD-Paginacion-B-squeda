<!-- Modal -->
<div class="modal fade" id="editaModal" tabindex="-1" aria-labelledby="editaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editaModalLabel">Editar Registro</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="actualiza.php" method="post" enctype="multipart/form-data">

        <input type="hidden" id="id" name="id">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion:</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="autos" class="form-label">Autos:</label>
                <select name="autos" id="autos" class="form-select" required>
                    <option value="">Seleccionar...</option>
                    <?php while ($row_autos = $autos->fetch_assoc()) { ?>
                            <option value="<?php echo $row_autos["ID"]; ?>"><?= $row_autos
                            ["Nombre"] ?></option>
                        <?php } ?>
                </select>
            </div>

            <div class="mb-3">
              <img id="img_vista" width="100">
            </div>

            <div class="mb-3">
                <label for="vista" class="form-label">Vista Pista:</label>
                <input type="file" name="vista" id="vista" class="form-control" accept="image/jpeg">
            </div>

            <div class="">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk"></i> Guardar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>