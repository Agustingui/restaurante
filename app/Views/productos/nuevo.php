<div>
    <h3 class="mb-3"><?php echo $titulo; ?></h3>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body p-4">
    <form action="<?php echo base_url('productos/insertar'); ?>" method="post">
        <div class="mb-2">
            <label class="form-label">Categoría</label>
            <select name="id_categoria" id="id_categoria" class="form-select">
                <option value="">Seleccionar</option>
                <?php foreach ($categorias as $categoria) { ?>
                    <option value="<?php echo $categoria['id']; ?>" <?php if (old('id_categoria') == $categoria['id']) { echo 'selected'; } ?>><?php echo esc($categoria['nombre']); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo old('nombre'); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control" value="<?php echo old('descripcion'); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label">Precio</label>
            <input type="number" name="precio" id="precio" class="form-control" step="0.01" min="0" value="<?php echo old('precio'); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label">Disponible</label>
            <select name="disponible" id="disponible" class="form-select">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <br>

        <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">check</span>Guardar</button>
        <a href="<?php echo base_url('productos'); ?>" class="btn btn-soft-secondary d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">close</span>Cancelar</a>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>
