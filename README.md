# HelpDesk - Sistema de Tickets

Este es un sistema básico de **gestión de tickets de soporte** desarrollado en **PHP, MySQL y Bootstrap**.  
Permite a **clientes, técnicos y administradores** interactuar de acuerdo a sus roles.  

---

## Funcionalidades

### Cliente
- Crear nuevos tickets.
- Ver el estado de sus tickets.
- **Eliminar sus propios tickets** (único rol que puede hacerlo).

### Técnico
- Ver todos los tickets.
- Editar solo los tickets **asignados a él**.
- Cambiar estado y prioridad de sus tickets.

### Administrador
- Ver todos los tickets.
- Editar cualquier ticket.
- Asignar tickets a técnicos.
- Gestionar usuarios:
  - Crear **técnicos**.
  - Eliminar **técnicos y clientes**.
  - (No puede crear clientes ni administradores).

---

## Instalación

1. Clona este repositorio o copia los archivos en tu servidor web:
   ```bash
   git clone https://github.com/GennyC/MVC_HELPDESK.git
2. Crea una base de datos en MySQL:
   CREATE DATABASE helpdesk;
3. Importa la estructura inicial:

-- Tabla usuarios
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol ENUM('admin','tecnico','cliente') NOT NULL
);

-- Tabla tickets
CREATE TABLE tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  asunto VARCHAR(255) NOT NULL,
  descripcion TEXT,
  estado ENUM('Abierto','Pendiente','Resuelto','Cerrado') DEFAULT 'Abierto',
  prioridad ENUM('Baja','Media','Alta') DEFAULT 'Media',
  solicitante VARCHAR(100) NOT NULL,
  asignado_a VARCHAR(100) DEFAULT 'Unassigned',
  actualizado TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
4. Configura la conexión a la base de datos en:
  /models/conexion.php
5. Accede desde el navegador:
https://mvchelpdesk-7ac0773c54a5.herokuapp.com

## Servicios para el despliegue 

Servicios utilizados:
1. Heroku (Hosting): despliegue de la aplicación PHP.

2. JawsDB MySQL (Base de datos en la nube): addon de Heroku para la gestión de la base de datos.

3. Archivos clave para el despliegue:

- composer.json → requerido por Heroku para identificar el proyecto como aplicación PHP.

- composer.lock → asegura dependencias consistentes.

- Procfile → define el proceso web para que Heroku ejecute Apache:

- web: vendor/bin/heroku-php-apache2 .

## Dificultades Encontradas y Soluciones

1. Error No web processes running (H14)

- Problema: Heroku no ejecutaba la app porque no había Procfile.

- Solución: Crear el archivo Procfile en el directorio raíz con:

echo "web: vendor/bin/heroku-php-apache2 ." > Procfile

2. Error No 'composer.lock' found

- Problema: Heroku exige composer.json y composer.lock.

- Solución: Crear un composer.json básico y generar el composer.lock con:

composer update
git add composer.json composer.lock
git commit -m "Add composer files"

4. Error de conexión a la base de datos (JAWSDB_URL not set)

- Problema: la app intentaba conectarse a localhost.

- Solución: modificar /models/conexion.php para leer las credenciales de la variable de entorno JAWSDB_URL.

5. Error Base table or view not found (faltaban tablas en producción)

- Problema: La base de datos JawsDB estaba vacía.

- Solución: Conectarse a la DB en la nube y ejecutar el SQL de arriba:

mysql -h <host> -u <user> -p <dbname>

(host, user y db están en la variable JAWSDB_URL de Heroku).

## Estructura del Proyecto
helpdesk/
│── index.php          # Router principal
│── controllers/       # Controladores de lógica
│── models/            # Conexión y consultas a la BD
│── modules/           # Header, sidebar, layouts
│── public/            # CSS, JS, imágenes
│── usuarios.php       # Gestión de usuarios (solo admin)
│── tickets.php        # Gestión de tickets
│── composer.json      # Dependencias PHP (Heroku)
│── Procfile           # Proceso de ejecución en Heroku
