<?php
$colores = [
    'pendiente' => 'bg-warning-subtle text-warning-emphasis',
    'en_preparacion' => 'bg-info-subtle text-info-emphasis',
    'preparada' => 'bg-success-subtle text-success-emphasis',
    'entregada' => 'bg-secondary-subtle text-secondary-emphasis',
    'cancelada' => 'bg-danger-subtle text-danger-emphasis'
];
?>

<div>
    <a href="<?php echo base_url('mozo'); ?>" class="btn btn-soft-secondary btn-sm mb-3 d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">arrow_back</span>Volver a las mesas</a>

    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h3 class="mb-0">Mesa <?php echo $mesa['numero']; ?></h3>
            <div class="text-muted small">Capacidad: <?php echo $mesa['capacidad']; ?> personas</div>
        </div>

        <?php if ($pedido != null) { ?>
            <span class="chip">Pedido N.º <?php echo $pedido['id']; ?> · desde <?php echo $pedido['fecha_apertura']; ?></span>
        <?php } ?>
    </div>

    <?php if ($pedido == null) { ?>

        <div class="card">
            <div class="card-body text-center py-5">
                <p class="mb-3">La mesa está libre.</p>

                <form action="<?php echo base_url('mozo/abrir'); ?>" method="post">
                    <input type="hidden" name="id_mesa" value="<?php echo $mesa['id']; ?>">
                    <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">add</span>Abrir pedido</button>
                </form>
            </div>
        </div>

    <?php } else { ?>

        <div class="row g-3">

            <div class="col-xl-8">

                <div class="input-group mb-3">
                    <span class="input-group-text bg-white"><span class="material-symbols-outlined">search</span></span>
                    <input type="text" id="buscador" class="form-control" placeholder="Buscar producto en todo el menú">
                </div>

                <div class="nav nav-pills flex-nowrap gap-2 mb-3 categorias-scroll">
                    <?php foreach ($categorias as $categoria) { ?>
                        <?php
                        $hay = false;
                        foreach ($productos as $producto) {
                            if ($producto['id_categoria'] == $categoria['id']) {
                                $hay = true;
                            }
                        }
                        ?>
                        <?php if ($hay) { ?>
                            <button type="button" class="nav-link pastilla-categoria" data-categoria="<?php echo $categoria['id']; ?>"><?php echo esc($categoria['nombre']); ?></button>
                        <?php } ?>
                    <?php } ?>
                </div>

                <form action="<?php echo base_url('mozo/enviar'); ?>" method="post" id="formComanda">
                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['id']; ?>">

                    <div class="row g-2">
                        <?php foreach ($productos as $producto) { ?>
                            <div class="col-md-6 producto" data-categoria="<?php echo $producto['id_categoria']; ?>">
                                <div class="producto-item">
                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                        <div>
                                            <div class="fw-medium nombre"><?php echo esc($producto['nombre']); ?></div>
                                            <div class="text-muted small">$<?php echo number_format($producto['precio'], 2, ',', '.'); ?></div>
                                        </div>

                                        <div class="input-group input-group-sm stepper">
                                            <button type="button" class="btn btn-soft-secondary menos"><span class="material-symbols-outlined">remove</span></button>
                                            <input type="number" name="cantidad[<?php echo $producto['id']; ?>]" value="0" min="0" max="99" class="form-control text-center cantidad">
                                            <button type="button" class="btn btn-soft-secondary mas"><span class="material-symbols-outlined">add</span></button>
                                        </div>
                                    </div>

                                    <input type="text" name="observaciones[<?php echo $producto['id']; ?>]" maxlength="255" class="form-control form-control-sm mt-2 nota d-none" placeholder="Observaciones (ej: sin tomate)">
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <p id="sin-resultados" class="text-muted d-none">No se encontraron productos.</p>
                </form>
            </div>

            <div class="col-xl-4">

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Comanda en armado</span>
                        <span class="badge bg-secondary-subtle text-secondary-emphasis" id="total-items">0</span>
                    </div>

                    <div class="card-body">
                        <p id="resumen-vacio" class="text-muted mb-0">Todavía no agregaste productos.</p>
                        <ul class="list-unstyled mb-0" id="resumen"></ul>
                    </div>

                    <div class="card-footer">
                        <button type="submit" form="formComanda" id="btn-enviar" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2" disabled>
                            <span class="material-symbols-outlined">send</span>Enviar comanda a cocina
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header fw-semibold">Comandas de la mesa</div>

                    <div class="card-body">
                        <?php if (count($comandas) == 0) { ?>
                            <p class="text-muted mb-0">Todavía no se envió ninguna comanda.</p>
                        <?php } ?>

                        <?php foreach ($comandas as $comanda) { ?>
                            <div class="pb-3 mb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="fw-semibold">Comanda N.º <?php echo $comanda['id']; ?></span>
                                        <div class="text-muted small"><?php echo $comanda['fecha_creacion']; ?></div>
                                    </div>
                                    <span class="badge <?php echo $colores[$comanda['estado']]; ?>"><?php echo str_replace('_', ' ', $comanda['estado']); ?></span>
                                </div>

                                <?php foreach ($comanda['detalle'] as $linea) { ?>
                                    <div class="d-flex gap-2">
                                        <span class="fw-medium"><?php echo $linea['cantidad']; ?>×</span>
                                        <span>
                                            <?php echo esc($linea['nombre_producto']); ?>
                                            <?php if ($linea['observaciones'] != '') { ?>
                                                <span class="d-block text-muted small"><?php echo esc($linea['observaciones']); ?></span>
                                            <?php } ?>
                                        </span>
                                    </div>
                                <?php } ?>

                                <?php if ($comanda['estado'] == 'preparada') { ?>
                                    <form action="<?php echo base_url('mozo/entregar/' . $comanda['id']); ?>" method="post" class="mt-2">
                                        <button type="submit" class="btn btn-soft-success btn-sm d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">done_all</span>Registrar entrega</button>
                                    </form>
                                <?php } ?>

                                <?php if ($comanda['estado'] == 'pendiente') { ?>
                                    <form action="<?php echo base_url('mozo/cancelarcomanda/' . $comanda['id']); ?>" method="post" class="mt-2">
                                        <button type="submit" class="btn btn-soft-danger btn-sm d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">close</span>Cancelar comanda</button>
                                    </form>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="card-footer">
                        <form action="<?php echo base_url('mozo/cerrar'); ?>" method="post">
                            <input type="hidden" name="id_pedido" value="<?php echo $pedido['id']; ?>">
                            <button type="submit" class="btn btn-soft-secondary w-100 d-inline-flex align-items-center justify-content-center gap-1"><span class="material-symbols-outlined">lock</span>Cerrar pedido</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

        <script src="<?php echo base_url('js/comanda.js'); ?>"></script>

    <?php } ?>
</div>
