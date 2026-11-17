<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"><?php echo $titulo; ?></h3>
        <a href="<?php echo base_url('productos/nuevo'); ?>" class="btn btn-primary d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">add</span>Nuevo producto</a>
    </div>

    <div class="card mb-4">
        <div class="table-responsive p-3">
        <table id="tabla" class="table table-hover align-middle mb-0 w-100">
        <thead class="table-light">
            <tr>
                <th>Id</th>
                <th>Categoría</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Disponible</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto) { ?>
                <tr>
                    <td><?php echo $producto['id']; ?></td>
                    <td><?php echo esc($producto['categoria']); ?></td>
                    <td><?php echo esc($producto['nombre']); ?></td>
                    <td><?php echo esc($producto['descripcion']); ?></td>
                    <td>$<?php echo number_format($producto['precio'], 2, ',', '.'); ?></td>
                    <td><span class="badge <?php echo $producto['disponible'] == 1 ? 'bg-success' : 'bg-secondary'; ?>"><?php echo $producto['disponible'] == 1 ? 'Sí' : 'No'; ?></span></td>
                    <td>
                        <a href="<?php echo base_url('productos/editar/' . $producto['id']); ?>" class="btn btn-soft-secondary btn-sm d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">edit</span>Editar</a>
                        <a href="<?php echo base_url('productos/eliminar/' . $producto['id']); ?>" class="btn btn-soft-danger btn-sm d-inline-flex align-items-center gap-1"><span class="material-symbols-outlined">delete</span>Borrar</a>
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
