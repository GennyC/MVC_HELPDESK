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
   git clone https://github.com/tuusuario/helpdesk.git
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
  http://localhost/helpdesk/index.php

## Estructura del Proyecto
helpdesk/
│── index.php          # Router principal
│── controllers/       # Controladores de lógica
│── models/            # Conexión y consultas a la BD
│── modules/           # Header, sidebar, layouts
│── public/            # CSS, JS, imágenes
│── usuarios.php       # Gestión de usuarios (solo admin)
│── tickets.php        # Gestión de tickets