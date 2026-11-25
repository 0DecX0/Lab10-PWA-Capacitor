# Lab 10-12 - PWA y Contramedidas

## Seguridad en producción
Para desplegar esta aplicación en un entorno real, se deben aplicar las siguientes configuraciones:
* **Backend (Laravel):**
  * `APP_ENV=production`
  * `APP_DEBUG=false` (Evita filtrar trazas de SQL).
  * Configurar `allowed_origins` en `config/cors.php` con el dominio real (nunca `*`).
  * La expiración de tokens de Sanctum está configurada a 8 horas.
* **Frontend (Vue/PWA):**
  * `VITE_API_URL` debe apuntar a una URL con `https://`.
  * No incluir secretos en las variables `VITE_`.
  * En `capacitor.config.json`, asegurar que no haya `cleartext` ni bloque `server` expuesto.