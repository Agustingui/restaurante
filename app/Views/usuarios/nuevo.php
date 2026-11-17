<div>
    <h3 class="mb-3"><?php echo $titulo; ?></h3>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body p-4">
    <form action="<?php echo base_url('usuarios/insertar'); ?>" method="post">
        <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo old('nombre'); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" id="apellido" class="form-control" value="<?php echo old('apellido'); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label">Usuario</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo old('username'); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label">Rol</label>
            <select name="id_rol" id="id_rol" class="form-select">
                <option value="">Seleccionar</option>
                <?php foreach ($roles as $rol_item) { ?>
                    <option value="<?php echo $rol_item['id']; ?>" <?php if (old('id_rol') == $rol_item['id']) { echo 'selected'; } ?>><?php echo esc($rol_item['nombre']); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-2">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <br>

        <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">check</span>Guardar</button>
        <a href="<?php echo base_url('usuarios'); ?>" class="btn btn-soft-secondary d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">close</span>Cancelar</a>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>
