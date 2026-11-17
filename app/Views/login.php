<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('css/estilos.css'); ?>" rel="stylesheet">
</head>
<body>


<div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
  <div class="login-caja p-4">

    <div class="text-center mb-4">
      <div class="login-marca d-inline-flex align-items-center justify-content-center mb-2">
        <span class="material-symbols-outlined login-icono">restaurant</span>
      </div>
      <h5 class="fw-semibold mb-1">Comandas</h5>
      <p class="text-muted small mb-0">Ingresá con tu usuario y contraseña</p>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
      <div class="alert alert-danger py-2 px-3 small" role="alert">
        <?= esc(session()->getFlashdata('error')); ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('login/entrar'); ?>" method="post">
      <?= csrf_field(); ?>

      <div class="mb-3">
        <label for="username" class="form-label fw-medium">Usuario</label>
        <input type="text" name="username" id="username" class="form-control" required autofocus>
      </div>

      <div class="mb-4">
        <label for="password" class="form-label fw-medium">Contraseña</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-1">
        <span class="material-symbols-outlined">login</span>
        <span>Ingresar</span>
      </button>
    </form>

  </div>
</div>
</body>
</html>
