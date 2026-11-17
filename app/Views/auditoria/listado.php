<div>
    <h3 class="mb-3"><?php echo $titulo; ?></h3>

    <div class="card mb-4">
        <div class="table-responsive p-3">
        <table id="tabla" class="table table-hover align-middle mb-0 w-100">
        <thead class="table-light">
            <tr>
                <th>Fecha y hora</th>
                <th>Usuario</th>
                <th>Entidad</th>
                <th>Registro</th>
                <th>Acción</th>
                <th>Datos anteriores</th>
                <th>Datos nuevos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $registro) { ?>
                <tr>
                    <td><?php echo $registro['fecha_hora']; ?></td>
                    <td><?php echo esc($registro['username']); ?></td>
                    <td><?php echo esc($registro['entidad']); ?></td>
                    <td><?php echo $registro['id_registro']; ?></td>
                    <td><?php echo $registro['accion']; ?></td>
                    <td><code><?php echo esc($registro['datos_anteriores']); ?></code></td>
                    <td><code><?php echo esc($registro['datos_nuevos']); ?></code></td>
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
