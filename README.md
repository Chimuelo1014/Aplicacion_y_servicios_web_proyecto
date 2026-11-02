# AutoGestor · Compraventa de vehículos

Proyecto final de **Aplicaciones y Servicios Web** en Laravel 12. Es un sistema para gestionar el inventario
de una compraventa de vehículos: marcas, vehículos con fotos, estados de venta, informes PDF y una API REST con tokens.

## Requisitos

- PHP 8.2 o superior (el de XAMPP sirve) con las extensiones `pdo_mysql`, `gd`, `zip`, `fileinfo` y `mbstring`
- Composer 2
- MySQL o MariaDB (el de XAMPP sirve)
- Git

> No hace falta Laragon: XAMPP alcanza. Solo se usa su PHP y su MySQL.

## Instalación local

```bash
git clone https://github.com/Chimuelo1014/Aplicacion_y_servicios_web_proyecto.git
cd Aplicacion_y_servicios_web_proyecto
composer install
cp .env.example .env
php artisan key:generate
```

1. Enciende **MySQL** desde el panel de XAMPP.
2. Crea la base `compraventa_vehiculos` en phpMyAdmin (cotejamiento `utf8mb4_unicode_ci`).
3. Revisa los datos de conexión `DB_*` en `.env` (por defecto `root` sin contraseña).

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Abre http://127.0.0.1:8000

**Usuario de prueba** (lo crea el seeder): `admin@compraventa.test` / `Admin12345`

Para reiniciar la base con datos de ejemplo: `php artisan migrate:fresh --seed`

### Correos (olvidé mi contraseña)

En local `MAIL_MAILER=log`, así que el correo de recuperación no se envía de verdad: queda escrito en
`storage/logs/laravel.log`, y de ahí se copia el enlace. Para la demo pueden configurar
[Mailtrap](https://mailtrap.io) o un SMTP real en `.env`.

## Pruebas

```bash
php artisan test
```

Hay 17 tests que cubren auth, recuperación de contraseña, perfil con avatar, el CRUD de marcas y vehículos
con fotos, el PDF, la API con token y el servicio externo.

## Cómo se cumple cada requisito

| Requisito | Dónde está |
|---|---|
| Vistas heredadas de maestras | `resources/views/layouts/app.blade.php` (panel) y `layouts/guest.blade.php` (público y auth) |
| Landing page | `resources/views/welcome.blade.php`, ruta `/` |
| Registro y login personalizados | `app/Http/Controllers/Auth/AuthController.php`, `resources/views/auth/*` |
| Rutas protegidas | `routes/web.php`, con los middleware `guest`, `auth` y `throttle` |
| Migraciones | `database/migrations` (users + avatar, marcas, vehiculos, tokens) |
| Modelos y controladores | `app/Models/{User,Marca,Vehiculo}.php`, `app/Http/Controllers/*` |
| Olvidé mi contraseña | `Auth/PasswordResetController.php`, correo en español en `AppServiceProvider` |
| Dashboard con usuario activo y menú | `resources/views/dashboard.blade.php` y el menú lateral de `layouts/app` |
| Logout | botón "Cerrar sesión" (POST `/logout`) |
| DataTables de 2 tablas | `vehiculos/index` y `marcas/index` |
| Editar perfil | `/perfil`: datos, foto y contraseña |
| Chart | Dashboard: barras por marca y dona por estado (Chart.js) |
| CRUD sobre 2 tablas | `MarcaController` y `VehiculoController` |
| Ruta API | `routes/api.php`: `/api/vehiculos`, `/api/marcas`, `/api/login` |
| Informe PDF | `/vehiculos/reporte/pdf` (DomPDF), vista `reportes/vehiculos` |
| Subida de imágenes | Foto del vehículo, logo de la marca y avatar del usuario |
| Consumir REST (GET/POST/PUT/DELETE) | Pantalla **Cliente API** (`/cliente-api`), que usa `fetch` con Bearer token |
| Servicios web, tokens, cabeceras y autorización | Sanctum (tokens desde el perfil o `/api/login`) y consumo de la API pública NHTSA con `Http::withHeaders` (botón ✨ en el formulario de vehículo) |

## API REST

Base: `http://127.0.0.1:8000/api`. Las rutas protegidas necesitan la cabecera
`Authorization: Bearer {token}` y `Accept: application/json`.

| Método | Ruta | Auth | Descripción |
|---|---|---|---|
| POST | `/login` | — | `{email, password}` → `{token}` |
| GET | `/marcas` | — | Lista de marcas |
| GET | `/user` | ✔ | Usuario dueño del token |
| POST | `/logout` | ✔ | Revoca el token actual |
| GET | `/vehiculos?estado=&marca_id=&per_page=` | ✔ | Listado paginado |
| GET | `/vehiculos/{id}` | ✔ | Detalle |
| POST | `/vehiculos` | ✔ | Crear |
| PUT | `/vehiculos/{id}` | ✔ | Actualizar (campos parciales) |
| DELETE | `/vehiculos/{id}` | ✔ | Eliminar |

Ejemplo:

```bash
curl -X POST http://127.0.0.1:8000/api/login -H "Accept: application/json" \
     -d "email=admin@compraventa.test&password=Admin12345"

curl http://127.0.0.1:8000/api/vehiculos -H "Accept: application/json" \
     -H "Authorization: Bearer TU_TOKEN"
```

## Estructura principal

```
app/
  Http/Controllers/
    Auth/AuthController.php            login, registro, logout
    Auth/PasswordResetController.php   olvidé mi contraseña
    Api/AuthController.php             tokens API
    Api/VehiculoController.php         CRUD JSON
    DashboardController.php            indicadores y charts
    MarcaController.php                CRUD marcas
    VehiculoController.php             CRUD vehículos y PDF
    ProfileController.php              perfil, avatar y tokens
    ServicioExternoController.php      cliente API y NHTSA
  Models/ User, Marca, Vehiculo
resources/views/
  layouts/ app, guest              plantillas maestras
  auth/ dashboard profile marcas vehiculos reportes api
routes/ web.php, api.php
```

## Pendiente

- [ ] Despliegue en internet (Railway, Render o un hosting con PHP + MySQL)
- [ ] Configurar un SMTP real para el correo de recuperación en producción
- [ ] Ajustes visuales o funcionales que acuerde el equipo
