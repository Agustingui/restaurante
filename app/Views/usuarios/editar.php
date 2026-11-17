<div>
    <h3 class="mb-3"><?php echo $titulo; ?></h3>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body p-4">
    <form action="<?php echo base_url('usuarios/actualizar'); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">

        <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo esc($usuario['nombre']); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" id="apellido" class="form-control" value="<?php echo esc($usuario['apellido']); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label">Usuario</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo esc($usuario['username']); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label">Rol</label>
            <select name="id_rol" id="id_rol" class="form-select">
                <option value="">Seleccionar</option>
                <?php foreach ($roles as $rol_item) { ?>
                    <option value="<?php echo $rol_item['id']; ?>" <?php if ($usuario['id_rol'] == $rol_item['id']) { echo 'selected'; } ?>><?php echo esc($rol_item['nombre']); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-2">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control">
            <small class="text-muted">Dejalo vacío para no cambiar la contraseña</small>
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
