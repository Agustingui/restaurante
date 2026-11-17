<?php
$rol = session()->get('rol');
$seccion = service('uri')->getSegment(1);

if ($rol == 'Administrador') {
    $menu = [
        'Gestión' => [['usuarios', 'Usuarios', 'group'], ['mesas', 'Mesas', 'table_restaurant'], ['categorias', 'Categorías', 'category'], ['productos', 'Productos', 'lunch_dining']],
        'Registros' => [['historial', 'Comandas', 'receipt_long'], ['reportes', 'Reportes', 'bar_chart'], ['auditoria', 'Auditoría', 'fact_check']]
    ];
} elseif ($rol == 'Mozo') {
    $menu = ['Salón' => [['mozo', 'Mesas', 'table_restaurant']]];
} else {
    $menu = ['Cocina' => [['cocina', 'Comandas en cocina', 'restaurant'], ['historial', 'Comandas anteriores', 'history']]];
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo isset($titulo) ? $titulo : 'Comandas'; ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url('css/estilos.css'); ?>" rel="stylesheet">
    </head>
    <body>

        <div class="d-flex">

            <aside class="sidebar offcanvas-md offcanvas-start" tabindex="-1" id="menu">
                <div class="offcanvas-header">
                    <a href="<?php echo base_url('inicio'); ?>" class="d-flex align-items-center gap-2 text-white text-decoration-none">
                        <span class="marca d-flex align-items-center justify-content-center"><span class="material-symbols-outlined">restaurant</span></span>
                        <span class="fw-semibold">Comandas</span>
                    </a>
                    <button type="button" class="btn-close btn-close-white d-md-none" data-bs-dismiss="offcanvas" data-bs-target="#menu"></button>
                </div>

                <div class="offcanvas-body">
                    <?php foreach ($menu as $grupo => $items) { ?>
                        <div class="etiqueta"><?php echo $grupo; ?></div>
                        <ul class="nav flex-column">
                            <?php foreach ($items as $item) { ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($seccion == $item[0]) { echo 'active'; } ?>" href="<?php echo base_url($item[0]); ?>"><span class="material-symbols-outlined"><?php echo $item[2]; ?></span><?php echo $item[1]; ?>
                                        <?php if ($rol == 'Mozo' && $item[0] == 'mozo') { ?>
                                            <span id="contador-listas" class="badge bg-success ms-auto d-none">0</span>
                                        <?php } ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>

                    <div class="mt-auto border-top border-secondary border-opacity-25 pt-3 px-2">
                        <div class="fw-medium"><?php echo esc(session()->get('nombre')); ?></div>
                        <div class="small mb-2 sidebar-rol"><?php echo esc($rol); ?></div>
                        <a href="<?php echo base_url('salir'); ?>" class="btn btn-sm btn-outline-light w-100 d-inline-flex align-items-center justify-content-center gap-1"><span class="material-symbols-outlined">logout</span>Cerrar sesión</a>
                    </div>
                </div>
            </aside>

            <div class="flex-grow-1 contenido">

                <header class="topbar d-flex align-items-center gap-3 px-3 px-md-4 py-2">
                    <button class="btn btn-soft-secondary btn-sm d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menu"><span class="material-symbols-outlined">menu</span></button>
                    <div>Hola, <strong><?php echo esc(session()->get('nombre')); ?></strong></div>
                    <div class="ms-auto text-end lh-sm">
                        <div id="reloj" class="fw-semibold fs-5"></div>
                        <div id="fecha" class="text-muted small"></div>
                    </div>
                </header>

                <main class="p-3 p-md-4">

                    <?php if (session()->getFlashdata('errores')) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errores') as $error) { ?>
                                    <li><?php echo esc($error); ?></li>
                                <?php } ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php } ?>

                    <?php if (session()->getFlashdata('mensaje')) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo esc(session()->getFlashdata('mensaje')); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php } ?>
