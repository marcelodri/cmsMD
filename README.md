# Administrador de contenidos genérico

*Base para armar el gestor de contenidos de cualquier sitio web.*


## Uso

Está construido con CodeIgniter 2 http://www.codeigniter.com/userguide2/ y 
usando patrón de diseño MVC. 

Todo lo que quieras tocar lo vas a encontrar dentro de /cms/core/. Ahí los 
archivos que normalmente se editan son los modelos en /cms/core/models/ , los 
controladores /cms/core/controllers/ y las vistas en /cms/core/views/.

Todos los modelos en principio lo único que necesitan es que definas las tablas 
que se van a usar para cada controlador. El CMS está armado de con la idea de 
que cada controlador tenga su propio modelo. Los modelos heredan de 
/cms/core/core/MY_Model.php. Vas a ver que en ninguno se pone el campo id, 
implícitamente está en todos.

Con los controladores pasa algo parecido, todos heredan de 
/cms/core/core/MY_Controller.php. En los controladores normalmente se define 
solamente la configuración de cada campo que va a determinar si se muestra y 
cómo se muestra en las vistas. Para ver las opciones que tenés disponibles para 
los controladores hay un controlador, _plantilla.php, que te va a servir de 
referencia.

Las vistas también tienen su propia lógica. Los controladores por defecto tienen 
asignadas 2 vistas views/lista.php y views/form.php . La vista lista.php es la 
vista principal de cada controlador mientras que la vista form.php  es la que se 
usa cuando se edita o se agrega un ítem. 

Si querés editar alguna de estas vistas para alguno de los controladores lo que 
tenés que hacer es duplicar la vista y asignarle desde el controlador esa nueva 
vista. Como ejemplo tenés la vista admins_lista.php.

El JavaScript principal lo vas a encontrar en core/js/functions.js  y el CSS en 
core/css/theme_synapsis.css. Después normalmente no hace falta tocar ningún otro 
archivo. Y claro, en core/logs/ vas a encontrar los logs que se generen.

En cuanto a la base de datos los campos id y orden son obligatorios para todas 
las tablas, y el id autoincrementable.

Organizar todos los datos del usuario que pueden cambiar y repetirse dentro del sitio en api/config/empresa.php 


## Instalación

1. En el .htaccess: corroborar que el RewriteBase sea el correcto.
2. En config/config.php: 
	> Corroborar que el $config['controller_inicio'] exista.
	> Corroborar que $config['base_url'] esté bien.
	> Actualizar la clave de encriptación.
3. En config/constants.php: Corroborar que las rutas estén bien
4. En config/database.php: revisar los datos de conexión.
5. Correr los scripts SQL de /migrations para crear la BD y actualizar la password
del usuario superadmin, que es un hash creado con md5().
6. Correr `composer install` en /cms para instalar dependencias PHP con composer.
7. Correr `npm i` en /cms/core/js para instalar dependencias JS con NPM.
8. Cambiar los permisos de libs/codeigniter/logs, core/logs y resources (recursivo (aplicar a todos los ficheros y directorios) a 777.
9. Corroborar que resources/images, resources/files y resources/galleries estén vacías.
10. Actualizar las rutas en core\js\tinymce\plugins\jbimages\config.php