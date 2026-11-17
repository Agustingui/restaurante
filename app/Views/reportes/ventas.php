<div>
    <h3 class="mb-1">Reporte de ventas</h3>
    <p class="text-muted small mb-3">Se cuentan las comandas no canceladas, con el precio que tenía cada producto al momento del pedido.</p>

    <?php if (count($errores) > 0) { ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errores as $error) { ?>
                    <li><?php echo esc($error); ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <div class="card mb-3">
        <div class="card-body">
            <form action="<?php echo base_url('reportes'); ?>" method="get" class="row g-2 align-items-end">
                <div class="col-sm-4 col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="desde" id="desde" class="form-control" value="<?php echo $desde; ?>">
                </div>

                <div class="col-sm-4 col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="hasta" id="hasta" class="form-control" value="<?php echo $hasta; ?>">
                </div>

                <div class="col-sm-4 col-md-3">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">search</span>Consultar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Total vendido</div>
                    <div class="fs-3 fw-semibold">$<?php echo number_format($total_vendido, 2, ',', '.'); ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Comandas</div>
                    <div class="fs-3 fw-semibold"><?php echo $total_comandas; ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Productos vendidos</div>
                    <div class="fs-3 fw-semibold"><?php echo $total_unidades; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header fw-semibold">Ventas por día</div>
                <div class="table-responsive p-3">
                    <table id="tabla-dias" class="table table-hover align-middle mb-0 w-100 tabla-datos">
                        <thead class="table-light">
                            <tr>
                                <th>Día</th>
                                <th>Comandas</th>
                                <th>Productos</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas_por_dia as $fila) { ?>
                                <tr>
                                    <td data-order="<?php echo $fila['dia']; ?>"><?php echo date('d/m/Y', strtotime($fila['dia'])); ?></td>
                                    <td><?php echo $fila['comandas']; ?></td>
                                    <td><?php echo $fila['unidades']; ?></td>
                                    <td data-order="<?php echo $fila['total']; ?>">$<?php echo number_format($fila['total'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header fw-semibold">Ranking de productos vendidos</div>
                <div class="table-responsive p-3">
                    <table id="tabla-ranking" class="table table-hover align-middle mb-0 w-100 tabla-datos">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Unidades</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ranking as $fila) { ?>
                                <tr>
                                    <td><?php echo esc($fila['nombre_producto']); ?></td>
                                    <td><?php echo $fila['unidades']; ?></td>
                                    <td data-order="<?php echo $fila['total']; ?>">$<?php echo number_format($fila['total'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="<?php echo base_url('js/tablas.js'); ?>"></script>
