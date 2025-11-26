# Procedimientos del Proyecto

## 1. Instalación y Configuración Inicial
1. Clonar el repositorio.
2. Copiar `.env.example` a `.env` y ajustar variables (DB, SMTP, PayPal).
3. Ejecutar `docker-compose up -d`.
4. El contenedor `db` ejecutará `schema.sql` al iniciar, creando la base de datos y tablas.
5. Acceder a `http://localhost` para ver la página de bienvenida.

## 2. Despliegue
- En producción, usar un servidor Linux con Docker instalado.
- Configurar variables de entorno en el host o en un archivo `.env` seguro.
- Ejecutar `docker-compose -f docker-compose.yml up -d --build`.
- Configurar un proxy (NGINX) que redirija al contenedor `web` en el puerto 80.

## 3. Cron / Tarea Programada
- Dentro del contenedor `web` crear un cron job que ejecute cada día:
  ```bash
  php /var/www/html/app/Console/GenerateMonthlyCharges.php
  ```
- El script PHP llama al procedimiento almacenado `sp_generar_cargos_mensuales`.

## 4. Notificaciones por Correo
- Configurar SMTP en `.env` (ej. Gmail):
  ```env
  SMTP_HOST=smtp.gmail.com
  SMTP_PORT=587
  SMTP_USER=tuemail@gmail.com
  SMTP_PASS=tu_contraseña_app
  ```
- La clase `NotificationModel` envía correos usando PHPMailer.
- Se envían notificaciones automáticas:
  - Recordatorio de pago (3 días antes de la fecha límite).
  - Pago recibido.
  - Cargo vencido.

## 5. Webhook de PayPal
- Configurar URL de webhook en la cuenta sandbox de PayPal.
- Endpoint: `https://tu-dominio.com/paypal/webhook.php`.
- El webhook valida la firma y actualiza el estado del pago y crea la notificación correspondiente.
