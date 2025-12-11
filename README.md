# Trabajo de enfoque DWES

Este proyecto es mi trabajo de enfoque de la asignatura Desarrollo web entorno servidor.

Es una tienda online que vende zapatos de deporte.

# Casos de uso principales

- Crear, modificar o eliminar usuarios de tipo clientes y administrador.
- Crear, modificar o eliminar productos de la tienda (Usuario administrador).
- Navegación y visita de productos, permitiendo agregar al carrito de compras los productos que el usuario desee.
- Confirmación de compra (Usuario cliente).

# Como ejecutar en XAMPP

Para ejecutar esta aplicación web en xampp primero tienes que instalarlo.

Despues de eso, tienes que instalar composer tambien que luego lo vas a necesitar.

Siguiente, abre el fichero de configuración `httpd-xampp.conf`, y añade los siguientes valores.

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

Ahora, tienes que ir a la raiz del proyecto, preparar composer y inicializar el fichero autoload:

```cmd
composer install
composer dump-autoload -o
```

Y finalmente, si quieres que la aplicación esté a la raiz del servidor web, abre el fichero **httpd.conf** y cambia el DocumentRoot y Directory a la carpeta public:

```
DocumentRoot "C:/xampp/htdocs/dwes-trabajo-enfoque/public"
<Directory "C:/xampp/htdocs/dwes-trabajo-enfoque/public">
```

# Como ejecutar en Linux (Ubuntu)

Primero instala apache2, las extensiones de PHP y MySQL.

Dentro de la carpeta /var/Www/html clona el repositorio.

Luego inicializa el servidor de base de datos, entra en la linea de comandos SQL y puedes importar el fichero **schema.sql**:

```
sudo mysql_secure_installation

sudo mysql -u root -p

MYSQL> source /var/www/html/dwes-trabajo-enfoque/schema.sql
```

Y ahora en la CLI de mysql crea un usuario y dale permisos sobre esa base de datos:

```
MYSQL> CREATE USER 'username'@'localhost' IDENTIFIED BY 'your_password';
MYSQL> GRANT ALL PRIVILEGES ON dwes.* TO 'username'@'localhost';
MYSQL> FLUSH PRIVILEGES;
```

Ahora, crea un fichero VHost para tu aplicación, no te olvides de poner el DocumentRoot hasta la carpeta public y el Directory a lo mismo.

No te olvides de meter las variables de entorno dentro del VHost, cambiando las credenciales con las que has fijado del usuario SQL:

````
SetEnv DB_HOST 'localhost'
SetEnv DB_NAME 'dwes'
SetEnv DB_USER 'root'
SetEnv DB_PASS ''
````

Activalo:

```
sudo a2ensite misitio.conf
sudo systemctl restart apache2
```

Tras eso, prepara los permisos de la carpeta:

```
sudo chown -R www-data:www-data /var/www/html/dwes-trabajo-enfoque/
sudo chmod -R 755 /var/www/html/dwes-trabajo-enfoque/
```

Y finalmente, inicializa el proyecto composer y reinicia apache:

```
sudo -u www-data composer install
sudo -u www-data composer dump-autoload -o

sudo systemctl restart apache2 
```