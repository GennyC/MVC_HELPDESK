<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "models/conexion.php";
session_start();

// Solo admin puede entrar
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php?axn=home");
    exit;
}

$dbh = Conexion::conectar();

// Crear nuevo técnico
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["crear_tecnico"])) {
    $nombre   = trim($_POST["nombre"]);
    $email    = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $rol      = "tecnico"; // fijo en técnico

    $stmt = $dbh->prepare("INSERT INTO usuarios (nombre, email, password, rol) 
                           VALUES (:nombre, :email, :password, :rol)");
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":rol", $rol);
    $stmt->execute();

    header("Location: index.php?axn=usuarios&created=1");
    exit;
}

// Eliminar usuario (solo clientes o técnicos)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar_usuario"])) {
    $id = $_POST["id"];

    // Validamos que no sea admin
    $stmt = $dbh->prepare("SELECT rol FROM usuarios WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && in_array($user["rol"], ["cliente", "tecnico"])) {
        $stmt = $dbh->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }

    header("Location: index.php?axn=usuarios&deleted=1");
    exit;
}

// Listar usuarios
$usuarios = $dbh->query("SELECT id, nombre, email, rol FROM usuarios ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>HELPDESK — Usuarios</title>
  <link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="public/css/main.css">
</head>
<?php include "modules/header.php"; ?>
<?php include "modules/sidebar.php"; ?>

<body class="with-side-menu">

<div class="page-content">
  <div class="container-fluid">
    <section class="box-typical proj-page">
      <section class="proj-page-section proj-page-header tickets-header d-flex justify-content-between align-items-center">
        <div class="title">Gestión de Usuarios <i class="font-icon font-icon-users"></i></div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewTecnico">
          + Nuevo Técnico
        </button>
      </section>

      <section class="proj-page-section">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($usuarios as $u): ?>
              <tr>
                <td><?= $u["id"] ?></td>
                <td><?= htmlspecialchars($u["nombre"]) ?></td>
                <td><?= htmlspecialchars($u["email"]) ?></td>
                <td><?= htmlspecialchars($u["rol"]) ?></td>
                <td>
                  <?php if (in_array($u["rol"], ["cliente", "tecnico"])): ?>
                    <form method="POST" action="index.php?axn=usuarios" style="display:inline;">
                      <input type="hidden" name="id" value="<?= $u["id"] ?>">
                      <button type="submit" name="eliminar_usuario" class="btn btn-sm btn-danger"
                              onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                        Eliminar
                      </button>
                    </form>
                  <?php else: ?>
                    —
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    </section>
  </div>
</div>

<!-- Modal Nuevo Técnico -->
<div class="modal fade" id="modalNewTecnico" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form class="modal-content" method="POST" action="index.php?axn=usuarios">
      <div class="modal-header">
        <h5 class="modal-title">Crear Nuevo Técnico</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nombre</label>
          <input type="text" class="form-control" name="nombre" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
          <label>Contraseña</label>
          <input type="password" class="form-control" name="password" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="crear_tecnico">Crear</button>
      </div>
    </form>
  </div>
</div>

<script src="public/js/lib/jquery/jquery.min.js"></script>
<script src="public/js/lib/bootstrap/bootstrap.min.js"></script>
</body>
</html>
