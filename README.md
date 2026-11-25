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

  ## Reto Adicional: Content Security Policy (CSP)
Se implementó una política de seguridad de contenido (CSP) en el archivo `index.html` para mitigar ataques XSS. La política restringe la ejecución de scripts únicamente al propio origen (`'self'`) y limita las conexiones de red exclusivamente a la API de Laravel (`http://127.0.0.1:8000`), bloqueando cualquier intento de inyección externa.