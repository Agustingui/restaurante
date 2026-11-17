<div>
    <h3 class="mb-3"><?php echo $titulo; ?></h3>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body p-4">
                    <form action="<?php echo base_url('mesas/actualizar'); ?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $mesa['id']; ?>">

                        <div class="mb-2">
                            <label class="form-label">Número</label>
                            <input type="number" name="numero" id="numero" class="form-control" min="1" value="<?php echo esc($mesa['numero']); ?>">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Capacidad</label>
                            <input type="number" name="capacidad" id="capacidad" class="form-control" min="1" value="<?php echo esc($mesa['capacidad']); ?>">
                        </div>

                        <br>

                        <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">check</span>Guardar</button>
                        <a href="<?php echo base_url('mesas'); ?>" class="btn btn-soft-secondary d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">close</span>Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
