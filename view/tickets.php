<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "models/conexion.php"; 

session_start(); // aseguramos sesión

// Crear ticket
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["crear_ticket"])) {
    try {
        $dbh = Conexion::conectar();

        // valores iniciales
        $estado = "Abierto";
        $asignado = "Unassigned";

        if ($_SESSION['rol'] === 'admin') {
            $estado = $_POST["estado"];
            $asignado = $_POST["asignado_a"];
        }
        if ($_SESSION['rol'] === 'tecnico') {
            $estado = $_POST["estado"];
        }
        if ($_SESSION['rol'] === 'cliente') {
            // cliente no puede cambiar estado ni asignado
            $estado = "Abierto";
            $asignado = "Unassigned";
        }

        $stmt = $dbh->prepare("INSERT INTO tickets (asunto, descripcion, estado, prioridad, solicitante, asignado_a) 
                               VALUES (:asunto, :descripcion, :estado, :prioridad, :solicitante, :asignado_a)");

        $stmt->bindParam(":asunto", $_POST["asunto"]);
        $stmt->bindParam(":descripcion", $_POST["descripcion"]);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":prioridad", $_POST["prioridad"]);
        $stmt->bindParam(":solicitante", $_SESSION['nombre']);
        $stmt->bindParam(":asignado_a", $asignado);

        $stmt->execute();
        header("Location: index.php?axn=tickets&success=1");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Editar ticket (solo admin o técnico)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar_ticket"])) {
    try {
        $dbh = Conexion::conectar();

        $id = $_POST["id"];
        $prioridad = $_POST["prioridad"];
        $estado = $_POST["estado"];
        $asignado = $_POST["asignado_a"] ?? null;

        if ($_SESSION['rol'] === 'admin') {
            $stmt = $dbh->prepare("UPDATE tickets 
                                   SET estado = :estado, prioridad = :prioridad, asignado_a = :asignado, actualizado = NOW()
                                   WHERE id = :id");
            $stmt->bindParam(":estado", $estado);
            $stmt->bindParam(":prioridad", $prioridad);
            $stmt->bindParam(":asignado", $asignado);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
        }

        if ($_SESSION['rol'] === 'tecnico') {
            $stmt = $dbh->prepare("UPDATE tickets 
                                   SET estado = :estado, prioridad = :prioridad, actualizado = NOW()
                                   WHERE id = :id AND asignado_a = :tecnico");
            $stmt->bindParam(":estado", $estado);
            $stmt->bindParam(":prioridad", $prioridad);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":tecnico", $_SESSION['nombre']);
            $stmt->execute();
        }

        header("Location: index.php?axn=tickets&updated=1");
        exit;

    } catch (PDOException $e) {
        echo "Error al editar ticket: " . $e->getMessage();
    }
}

// Eliminar ticket (solo cliente, solo sus tickets)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_ticket"]) && $_SESSION['rol'] === 'cliente') {
    try {
        $dbh = Conexion::conectar();
        $id = $_POST["id"];

        // validar que el ticket pertenece al cliente
        $stmt = $dbh->prepare("DELETE FROM tickets WHERE id = :id AND solicitante = :cliente");
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":cliente", $_SESSION['nombre']);
        $stmt->execute();

        header("Location: index.php?axn=tickets&deleted=1");
        exit;
    } catch (PDOException $e) {
        echo "Error al eliminar ticket: " . $e->getMessage();
    }
}

// Listar tickets según rol

$dbh = Conexion::conectar();

// evita warnings
$tickets  = [];
$tecnicos = [];

if ($_SESSION['rol'] === 'admin') {
    // técnicos para el dropdown
    $stmt = $dbh->prepare("SELECT nombre FROM usuarios WHERE rol = 'tecnico'");
    $stmt->execute();
    $tecnicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // admin ve todos los tickets
    $tickets = $dbh->query("SELECT * FROM tickets ORDER BY actualizado DESC")->fetchAll(PDO::FETCH_ASSOC);

} elseif ($_SESSION['rol'] === 'tecnico') {
    // técnico ve todo, pero solo edita los asignados a él
    $tickets = $dbh->query("SELECT * FROM tickets ORDER BY actualizado DESC")->fetchAll(PDO::FETCH_ASSOC);

} elseif ($_SESSION['rol'] === 'cliente') {
    // cliente ve solo los suyos
    $stmt = $dbh->prepare("SELECT * FROM tickets WHERE solicitante = :cliente ORDER BY actualizado DESC");
    $stmt->bindParam(":cliente", $_SESSION['nombre']);
    $stmt->execute();
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>HELPDESK — Tickets</title>
  <link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="public/css/main.css">
</head>
<?php include "modules/header.php"; ?>
<?php include "modules/sidebar.php"; ?>

<body class="with-side-menu">

<div class="page-content">
  <div class="container-fluid">
    <section class="box-typical proj-page">
      <section class="proj-page-section proj-page-header tickets-header">
        <div class="title">Gestión de Tickets <i class="font-icon font-icon-pencil"></i></div>
      </section>

      <section class="proj-page-section">
        <div class="proj-page-main-controls">
          <div class="proj-page-main-controls-right">
            <?php if ($_SESSION['rol'] === 'cliente' || $_SESSION['rol'] === 'admin'): ?>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewTicket">
                + Nuevo Ticket
              </button>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <section class="proj-page-section">
        <div class="table-responsive">
<table class="table table-hover">
  <thead>
    <tr>
      <th>ID</th>
      <th>Asunto</th>
      <th>Estado</th>
      <th>Prioridad</th>
      <th>Solicitante</th>
      <th>Asignado</th>
      <th>Actualizado</th>
      <th>Acciones</th> <!-- columna fija -->
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tickets as $t): ?>
      <tr>
        <td><?= $t["id"] ?></td>
        <td><?= htmlspecialchars($t["asunto"]) ?></td>
        <td><?= htmlspecialchars($t["estado"]) ?></td>
        <td><?= htmlspecialchars($t["prioridad"]) ?></td>
        <td><?= htmlspecialchars($t["solicitante"]) ?></td>
        <td><?= htmlspecialchars($t["asignado_a"]) ?></td>
        <td><?= $t["actualizado"] ?></td>

        <td>
          <?php if ($_SESSION['rol'] === 'cliente'): ?>
            <!-- Botón VER (clientes) -->
            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalViewTicket"
              data-id="<?= $t["id"] ?>"
              data-asunto="<?= htmlspecialchars($t["asunto"], ENT_QUOTES) ?>"
              data-descripcion="<?= htmlspecialchars($t["descripcion"], ENT_QUOTES) ?>"
              data-estado="<?= $t["estado"] ?>"
              data-prioridad="<?= $t["prioridad"] ?>"
              data-asignado="<?= $t["asignado_a"] ?>">
              Ver
            </button>
                        <!-- Eliminar -->
            <form method="POST" action="index.php?axn=tickets" style="display:inline;">
              <input type="hidden" name="id" value="<?= $t["id"] ?>">
              <button type="submit" class="btn btn-sm btn-danger" name="eliminar_ticket"
                      onclick="return confirm('¿Seguro que deseas eliminar este ticket?');">
                Eliminar
              </button>
            </form>

          <?php elseif ($_SESSION['rol'] === 'admin' || 
                       ($_SESSION['rol'] === 'tecnico' && trim($t["asignado_a"]) === trim($_SESSION['nombre']))): ?>
            <!-- Botón EDITAR (admin/técnico asignado) -->
            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditTicket"
              data-id="<?= $t["id"] ?>"
              data-asunto="<?= htmlspecialchars($t["asunto"], ENT_QUOTES) ?>"
              data-descripcion="<?= htmlspecialchars($t["descripcion"], ENT_QUOTES) ?>"
              data-estado="<?= $t["estado"] ?>"
              data-prioridad="<?= $t["prioridad"] ?>"
              data-asignado="<?= $t["asignado_a"] ?>">
              Editar
            </button>
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

<!-- Modal Nuevo Ticket -->
<div class="modal fade" id="modalNewTicket" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <form class="modal-content" method="POST" action="index.php?axn=tickets">
      <div class="modal-header">
        <h5 class="modal-title">Crear Nuevo Ticket</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Asunto</label>
          <input type="text" class="form-control" name="asunto" required>
        </div>
        <div class="form-group">
          <label>Descripción</label>
          <textarea class="form-control" name="descripcion" rows="5"></textarea>
        </div>
        <div class="form-group">
          <label>Prioridad</label>
          <select class="form-control" name="prioridad">
            <option>Baja</option>
            <option>Media</option>
            <option>Alta</option>
          </select>
        </div>
        <?php if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'tecnico'): ?>
          <div class="form-group">
            <label>Estado</label>
            <select class="form-control" name="estado">
              <option>Abierto</option>
              <option>Pendiente</option>
              <option>Resuelto</option>
              <option>Cerrado</option>
            </select>
          </div>
        <?php endif; ?>
        <?php if ($_SESSION['rol'] === 'admin'): ?>
<div class="form-group">
  <label>Asignado a</label>
  <select class="form-control" name="asignado_a">
    <option value="Unassigned">Unassigned</option>
    <?php foreach ($tecnicos as $tec): ?>
      <option value="<?= htmlspecialchars($tec['nombre']) ?>"><?= htmlspecialchars($tec['nombre']) ?></option>
    <?php endforeach; ?>
  </select>
</div>

        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" name="crear_ticket">Crear</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Editar Ticket -->
<div class="modal fade" id="modalEditTicket" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form class="modal-content" method="POST" action="index.php?axn=tickets">
      <div class="modal-header">
        <h5 class="modal-title">Editar Ticket</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit-id">

        <!-- Detalles del ticket -->
        <div class="form-group">
          <label>Asunto</label>
          <input type="text" class="form-control" id="edit-asunto" readonly>
        </div>
        <div class="form-group">
          <label>Descripción</label>
          <textarea class="form-control" id="edit-descripcion" rows="4" readonly></textarea>
        </div>

        <!-- Campos editables -->
        <div class="form-group">
          <label>Estado</label>
          <select class="form-control" name="estado" id="edit-estado">
            <option>Abierto</option>
            <option>Pendiente</option>
            <option>Resuelto</option>
            <option>Cerrado</option>
          </select>
        </div>
        <div class="form-group">
          <label>Prioridad</label>
          <select class="form-control" name="prioridad" id="edit-prioridad">
            <option>Baja</option>
            <option>Media</option>
            <option>Alta</option>
          </select>
        </div>
        <?php if ($_SESSION['rol'] === 'admin'): ?>
        <div class="form-group">
          <label>Asignado a</label>
          <select class="form-control" name="asignado_a" id="edit-asignado">
            <option value="Unassigned">Unassigned</option>
            <?php foreach ($tecnicos as $tec): ?>
              <option value="<?= htmlspecialchars($tec['nombre']) ?>"><?= htmlspecialchars($tec['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="editar_ticket">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Ver Ticket (solo clientes) -->
<div class="modal fade" id="modalViewTicket" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles del Ticket</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <p><strong>Asunto:</strong> <span id="view-asunto"></span></p>
        <p><strong>Descripción:</strong></p>
        <p id="view-descripcion" class="border p-2 bg-light"></p>
        <p><strong>Estado:</strong> <span id="view-estado"></span></p>
        <p><strong>Prioridad:</strong> <span id="view-prioridad"></span></p>
        <p><strong>Asignado a:</strong> <span id="view-asignado"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<?php if ($_SESSION['rol'] === 'admin'): ?>
<div class="form-group">
  <label>Asignado a</label>
  <select class="form-control" name="asignado_a" id="edit-asignado">
    <option value="Unassigned">Unassigned</option>
    <?php foreach ($tecnicos as $tec): ?>
      <option value="<?= htmlspecialchars($tec['nombre']) ?>"><?= htmlspecialchars($tec['nombre']) ?></option>
    <?php endforeach; ?>
  </select>
</div>
<?php endif; ?>

<script src="public/js/lib/jquery/jquery.min.js"></script>
<script src="public/js/lib/bootstrap/bootstrap.min.js"></script>
<script>
$('#modalViewTicket').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // botón que abrió el modal

  // Rellenar los detalles en el modal de Ver
  $('#view-asunto').text(button.data('asunto'));
  $('#view-descripcion').text(button.data('descripcion'));
  $('#view-estado').text(button.data('estado'));
  $('#view-prioridad').text(button.data('prioridad'));
  $('#view-asignado').text(button.data('asignado'));
});



$('#modalEditTicket').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // botón que abrió el modal

  // rellenar los campos con los atributos "data-*"
  $('#edit-id').val(button.data('id'));
  $('#edit-asunto').val(button.data('asunto'));
  $('#edit-descripcion').val(button.data('descripcion'));
  $('#edit-estado').val(button.data('estado'));
  $('#edit-prioridad').val(button.data('prioridad'));

  // solo si existe el campo asignado (admin)
  if ($('#edit-asignado').length) {
    $('#edit-asignado').val(button.data('asignado'));
  }
});




</script>
</body>
</html>
