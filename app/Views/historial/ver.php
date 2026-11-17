<div>
    <a href="<?php echo base_url('historial'); ?>" class="btn btn-soft-secondary btn-sm mb-3 d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">arrow_back</span>Volver</a>

    <h3 class="mb-3">Comanda N.º <?php echo $comanda['id']; ?> - Mesa <?php echo $comanda['mesa']; ?></h3>
    <p>Pedido N.º <?php echo $comanda['id_pedido']; ?> - Estado actual: <strong><?php echo str_replace('_', ' ', $comanda['estado']); ?></strong></p>

    <h5 class="mb-2 mt-4">Productos</h5>
    <div class="card mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalle as $linea) { ?>
                        <tr>
                            <td><?php echo esc($linea['nombre_producto']); ?></td>
                            <td><?php echo $linea['cantidad']; ?></td>
                            <td>$<?php echo number_format($linea['precio_unitario'], 2, ',', '.'); ?></td>
                            <td><?php echo esc($linea['observaciones']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <h5 class="mb-2 mt-4">Recorrido de la comanda</h5>
    <div class="card mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha y hora</th>
                        <th>Estado anterior</th>
                        <th>Estado nuevo</th>
                        <th>Usuario</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cambios as $cambio) { ?>
                        <tr>
                            <td><?php echo $cambio['fecha_hora']; ?></td>
                            <td><?php echo $cambio['estado_anterior'] == '' ? '-' : str_replace('_', ' ', $cambio['estado_anterior']); ?></td>
                            <td><?php echo str_replace('_', ' ', $cambio['estado_nuevo']); ?></td>
                            <td><?php echo esc($cambio['username']); ?></td>
                            <td><?php echo esc($cambio['observacion']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
