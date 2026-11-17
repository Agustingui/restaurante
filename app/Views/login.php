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

        <div class="row g-0 min-vh-100">
            <div class="col-md-6 login-panel text-white d-flex align-items-center p-5">
                <div>
                    <div class="login-marca d-flex align-items-center justify-content-center mb-4">
                        <span class="material-symbols-outlined login-icono">restaurant</span>
                    </div>
                    <h1 class="fw-semibold mb-3">Comandas<br>del restaurante</h1>
                    <p class="mb-0 login-texto">Del salón a la cocina, sin papelitos.</p>
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center justify-content-center p-4">
                <div class="login-caja p-4">
                    <h4 class="mb-1">Iniciar sesión</h4>
                    <p class="text-muted mb-4">Ingresá con tu usuario y contraseña.</p>

                    <?php if (session()->getFlashdata('error')) { ?>
                        <div class="alert alert-danger"><?php echo esc(session()->getFlashdata('error')); ?></div>
                    <?php } ?>

                    <form action="<?php echo base_url('login/entrar'); ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="username" id="username" class="form-control" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-1">
                            <span class="material-symbols-outlined">login</span>Ingresar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </body>
</html>
