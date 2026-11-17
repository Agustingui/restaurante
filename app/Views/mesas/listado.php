<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"><?php echo $titulo; ?></h3>
        <a href="<?php echo base_url('mesas/nuevo'); ?>" class="btn btn-primary d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">add</span>Nueva mesa</a>
    </div>

    <div class="card mb-4">
        <div class="table-responsive p-3">
            <table id="tabla" class="table table-hover align-middle mb-0 w-100">
                <thead class="table-light">
                    <tr>
                        <th>Id</th>
                        <th>Número</th>
                        <th>Capacidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mesas as $mesa) { ?>
                        <tr>
                            <td><?php echo $mesa['id']; ?></td>
                            <td><?php echo esc($mesa['numero']); ?></td>
                            <td><?php echo esc($mesa['capacidad']); ?></td>
                            <td>
                                <a href="<?php echo base_url('mesas/editar/' . $mesa['id']); ?>" class="btn btn-soft-secondary btn-sm d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">edit</span>Editar</a>
                                <a href="<?php echo base_url('mesas/eliminar/' . $mesa['id']); ?>" class="btn btn-soft-danger btn-sm d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">delete</span>Borrar</a>
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
