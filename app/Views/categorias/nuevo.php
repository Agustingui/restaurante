<div>
    <h3 class="mb-3"><?php echo $titulo; ?></h3>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body p-4">
    <form action="<?php echo base_url('categorias/insertar'); ?>" method="post">
        <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo old('nombre'); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control" value="<?php echo old('descripcion'); ?>">
        </div>

        <br>

        <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">check</span>Guardar</button>
        <a href="<?php echo base_url('categorias'); ?>" class="btn btn-soft-secondary d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">close</span>Cancelar</a>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>
