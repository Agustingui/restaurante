<?php $con_pedido = count($ocupadas); ?>

<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h3 class="mb-0">Mesas</h3>

        <div class="d-flex gap-2">
            <span class="chip"><span class="punto punto-libre"></span> Libres <strong><?php echo count($mesas) - $con_pedido; ?></strong></span>
            <span class="chip"><span class="punto punto-ocupada"></span> Con pedido <strong><?php echo $con_pedido; ?></strong></span>
        </div>
    </div>

    <div class="row g-3">
        <?php foreach ($mesas as $mesa) { ?>
            <?php $ocupada = in_array($mesa['id'], $ocupadas); ?>
            <?php $cantidad_lista = isset($listas[$mesa['id']]) ? $listas[$mesa['id']] : 0; ?>
            <div class="col-6 col-md-4 col-xl-3">
                <a href="<?php echo base_url('mozo/mesa/' . $mesa['id']); ?>" class="text-decoration-none text-body d-block h-100">
                    <div class="card h-100 <?php echo $ocupada ? 'mesa-ocupada' : 'mesa-libre'; ?>">
                        <div class="aviso-mesa <?php if ($cantidad_lista == 0) { echo 'd-none'; } ?>" data-id-mesa="<?php echo $mesa['id']; ?>">
                            <span class="material-symbols-outlined">notifications_active</span>
                            <span>Listo para entregar</span>
                            <span class="aviso-cantidad"><?php if ($cantidad_lista > 1) { echo '(' . $cantidad_lista . ')'; } ?></span>
                        </div>
                        <div class="card-body text-center py-4">
                            <div class="text-muted small">Mesa</div>
                            <div class="mesa-numero"><?php echo $mesa['numero']; ?></div>
                            <div class="text-muted small d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined icono-chico">group</span>máx. <?php echo $mesa['capacidad']; ?> personas</div>
                        </div>
                        <div class="card-footer d-flex justify-content-between small">
                            <span>
                                <span class="punto <?php echo $ocupada ? 'punto-ocupada' : 'punto-libre'; ?>"></span>
                                <?php echo $ocupada ? 'Pedido abierto' : 'Libre'; ?>
                            </span>
                            <span class="text-primary d-inline-flex align-items-center"><?php echo $ocupada ? 'Continuar' : 'Abrir'; ?><span class="material-symbols-outlined icono-chico">chevron_right</span></span>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>
