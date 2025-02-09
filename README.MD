# Proyecto Empleados API

API REST para gestión de empleados desarrollada con PHP y MySQL.

## Configuración

1. Clonar el repositorio
2. Copiar .env.example a .env y configurar las variables de entorno
3. Ejecutar `composer install`

## Endpoints

- GET / - Obtener todos los empleados
- GET /?consultar={id} - Obtener empleado por ID
- GET /?insertar - Crear nuevo empleado
- GET /?actualizar={id} - Actualizar empleado
- GET /?borrar={id} - Eliminar empleado