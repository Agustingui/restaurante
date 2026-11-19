# Restaurante — Sistema de comandas digitales

Sistema web para gestionar los pedidos de un restaurante entre el salón y la cocina. El mozo abre un pedido en una mesa y envía comandas, la cocina las ve en pantalla y avanza su estado, y el administrador gestiona los datos del sistema y consulta auditoría, historial y reportes de ventas.

Proyecto para el Coloquio Final Integrador de **Programación III**.

## Tecnologías

- **Frontend:** HTML, CSS, Bootstrap 5, JavaScript y jQuery (AJAX con JSON)
- **Backend:** PHP 8.2+ con CodeIgniter 4
- **Base de datos:** MySQL / MariaDB

## Requisitos

- Tener instalado [XAMPP](https://www.apachefriends.org/) con PHP 8.2 o superior (con la extensión `intl` habilitada en `php.ini`).
- Tener instalado [Composer](https://getcomposer.org/).

## Instalación

1. Clonar el repositorio dentro de `htdocs` e instalar las dependencias:

   ```bash
   git clone https://github.com/Agustingui/restaurante.git
   cd restaurante
   composer install
   ```

2. Con Apache y MySQL iniciados en XAMPP, crear la base de datos ejecutando el script SQL:

   ```bash
   mysql -u root -p < database/restaurante.sql
   ```

   O de forma manual: entrar a `http://localhost/phpmyadmin`, ir a la pestaña _Importar_, elegir el archivo `database/restaurante.sql` y hacer clic en _Importar_.

3. Copiar el archivo `env` como `.env` y completar los datos de la base:

   ```ini
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost/restaurante/public/'
   database.default.hostname = localhost
   database.default.database = restaurante
   database.default.username = root
   database.default.password =
   ```

4. Abrir `http://localhost/restaurante/public/`.

## Uso

Ingresar con el usuario **`admin`** y la contraseña **`admin123`** (se recomienda modificar estas credenciales tras el primer inicio de sesión por seguridad). Desde **Usuarios → Nuevo** se pueden crear cuentas con rol Mozo y Cocina para probar el resto del sistema.

- **Administrador:** ABM de usuarios, mesas, categorías y productos (con baja lógica), auditoría, historial de comandas y reportes de ventas.
- **Mozo:** abre un pedido en una mesa, envía comandas a cocina, recibe un aviso cuando están listas, las entrega y cierra el pedido.
- **Cocina:** ve las comandas pendientes, que se actualizan solas, y avanza su estado con el mouse o con el teclado (`1`–`9` o flechas para seleccionar, `Enter` para avanzar).

## Créditos

- **Autor:** Agustin Solis
- **Asignatura:** Programación III
- **Carrera:** Análisis y Desarrollo de Software
- **Institución:** Instituto Superior Gaspar L. Benavento

## Licencia

Este proyecto está bajo la **Licencia MIT** — consulta el archivo [LICENSE](LICENSE) para más detalles. Puedes usar, copiar, modificar y distribuir el código libremente, siempre que se mantenga el aviso de derechos de autor original.
