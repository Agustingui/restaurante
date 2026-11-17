<div>
    <h3 class="mb-3"><?php echo $titulo; ?></h3>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body p-4">
    <div class="alert alert-warning">
        <p>¿Seguro que querés dar de baja el producto <strong><?php echo esc($producto['nombre']); ?></strong>?</p>
        <p class="mb-0">El registro no se elimina de la base de datos: queda con la fecha de baja.</p>
    </div>

    <form action="<?php echo base_url('productos/borrar/' . $producto['id']); ?>" method="post">
        <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">delete</span>Dar de baja</button>
        <a href="<?php echo base_url('productos'); ?>" class="btn btn-soft-secondary d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">close</span>Cancelar</a>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>
