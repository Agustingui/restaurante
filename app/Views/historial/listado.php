<div>
    <h3 class="mb-3"><?php echo $titulo; ?></h3>

    <div class="card mb-4">
        <div class="table-responsive p-3">
        <table id="tabla" class="table table-hover align-middle mb-0 w-100">
        <thead class="table-light">
            <tr>
                <th>Comanda</th>
                <th>Pedido</th>
                <th>Mesa</th>
                <th>Mozo</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comandas as $comanda) { ?>
                <tr>
                    <td><?php echo $comanda['id']; ?></td>
                    <td><?php echo $comanda['id_pedido']; ?></td>
                    <td><?php echo $comanda['mesa']; ?></td>
                    <td><?php echo esc($comanda['mozo']); ?></td>
                    <td><?php echo str_replace('_', ' ', $comanda['estado']); ?></td>
                    <td><?php echo $comanda['fecha_creacion']; ?></td>
                    <td>
                        <a href="<?php echo base_url('historial/ver/' . $comanda['id']); ?>" class="btn btn-soft-secondary btn-sm d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">history</span>Ver historial</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="<?php echo base_url('js/tablas.js'); ?>"></script>
