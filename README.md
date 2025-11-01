# Trabajo de enfoque DWES

Este proyecto es mi trabajo de enfoque de la asignatura Desarrollo web entorno servidor.

Es una tienda online que vende zapatos de deporte.

# Casos de uso principales

- Crear, modificar o eliminar usuarios de tipo clientes y administrador.
- Crear, modificar o eliminar productos de la tienda (Usuario administrador).
- Navegación y visita de productos, permitiendo agregar al carrito de compras los productos que el usuario desee.
- Confirmación de compra (Usuario cliente).

# Preparar entorno ejecución

Para poder arrancar esta aplicación en local tienes que hacer lo siguiente.

Primero instalar XAMPP.

Siguiente, abrir el fichero de configuración `httpd-xampp.conf`, y añadir los siguientes valores.

````
SetEnv DB_HOST 'localhost'
SetEnv DB_NAME 'dwes'
SetEnv DB_USER 'root'
SetEnv DB_PASS ''
````

Estos son los detalles de conexión de la base de datos por defecto, si tuyos son diferentes, ajústalos según hace falta.

Ahora, tienes que iniciar el servidor Apache y MySQL desde XAMPP y abrir el PhpMyAdmin en esta url por defecto:

```
http://localhost/phpmyadmin
```

Ahora entras en la pestaña `Import`, en el apartado de `File to import`, eliges el fichero **schema.sql** en la raiz del proyecto.

Ahora vas al fondo de la página y das a `Import`.

# Información adicional

En el proyecto verás que tiene un package.json para gestionar paquetes con NPM.

El proyecto no utiliza ningún paquete externo, pero estoy utilizando `tailwindcss` por CDN, y si no tienes esta configuración, tu IDE no reconoce que estás usando tailwind, y no te da el autocompletado. 