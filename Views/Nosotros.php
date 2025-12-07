<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?action=Servicios&redirect=Nosotros');
    exit;
}

$usuario = $_SESSION['usuario'];
$rol     = $_SESSION['rol'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nosotros - Registro de Estudiantes</title>

  <link rel="stylesheet" type="text/css" href="css/nav.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    :root {
      --rojo-uta: #a50000;
      --rojo-uta-oscuro: #7a0000;
      --rojo-uta-claro: #fbeaea;
    }

    body {
      background: linear-gradient(180deg, #ffe9e9 0%, #ffdada 100%);
      font-family: 'Segoe UI', Arial, sans-serif;
      margin: 0;
      min-height: 100vh;
    }

    .page-header-uta {
      text-align: center;
      margin-top: 1.8rem;
      margin-bottom: 1.5rem;
    }

    .page-header-uta h1 {
      margin: 0;
      font-size: 2.1rem;
      color: var(--rojo-uta);
      font-weight: 700;
    }

    .page-header-uta p {
      margin: 0.4rem 0 0;
      font-size: 0.95rem;
      color: #6b6b6b;
    }

    .badge-uta {
      background-color: var(--rojo-uta-claro);
      color: var(--rojo-uta);
      font-weight: 600;
    }

    .card-uta {
      border-radius: 14px;
      border: 1px solid rgba(165, 0, 0, 0.12);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
      background: #ffffff;
    }

    .btn-uta {
      background-color: var(--rojo-uta);
      color: #fff;
      font-weight: 600;
      border-radius: 6px;
      border: none;
    }

    .btn-uta:hover {
      background-color: var(--rojo-uta-oscuro);
      color: #fff;
    }

    table thead {
      background-color: var(--rojo-uta);
      color: #fff;
    }

    tbody tr.table-row-selected {
      background-color: #ffd6d6 !important;
    }

    footer {
      text-align: center;
      color: #fff;
      font-size: 0.95em;
      margin-top: 1.5rem;
      padding: 0.8rem 0 1rem;
      background: var(--rojo-uta);
    }
  </style>
</head>
<body>
  <header>
    <img src="imagenes/descarga.png" height="auto" width="100%">
  </header>

  <?php include 'nav.php'; ?>

  <main class="py-3">
    <div class="container">

      <div class="page-header-uta">
        <h1>Registro de Estudiantes</h1>
        <p>
          Bienvenido <strong><?php echo htmlspecialchars($usuario); ?></strong> · Rol:
          <strong><?php echo htmlspecialchars($rol); ?></strong>
        </p>
        <div class="mt-2">
          <a href="Models/logout.php" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
          </a>
        </div>
        <span class="badge rounded-pill badge-uta mt-2">
          UTA · FISEI · Sistema de Estudiantes
        </span>
      </div>

      <div class="card card-uta mb-4">
        <div class="card-header bg-white">
          <div class="row g-3 align-items-end">
            <div class="col-md-6">
              <label class="form-label mb-1 fw-semibold text-danger">
                Búsqueda rápida
              </label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="txtBuscarCedula" class="form-control" placeholder="Buscar por cédula...">
                <button class="btn btn-uta" id="btnBuscar"><i class="bi bi-search"></i> Buscar</button>
                <button class="btn btn-outline-secondary" id="btnLimpiar">
                  <i class="bi bi-arrow-clockwise"></i> Limpiar
                </button>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label mb-1 fw-semibold text-danger">
                Reportes (FPDF)
              </label>
              <div class="input-group input-group-sm">
                <select id="comboReportes" class="form-select">
                  <option value="">Seleccione un reporte...</option>
                  <option value="reporteFPDF">Reporte general PDF (FPDF)</option>
                  <option value="reporteCedulaFPDF">Reporte por cédula PDF (FPDF)</option>
                </select>
                <button class="btn btn-uta" id="btnReporte">
                  <i class="bi bi-file-earmark-pdf"></i> Ver reporte
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-title mb-0 text-danger">
              Listado de estudiantes
            </h5>
            <div>
              <button type="button" class="btn btn-uta btn-sm me-1" id="btnNuevo">
                <i class="bi bi-plus-circle"></i> Nuevo
              </button>
              <button type="button" class="btn btn-uta btn-sm me-1" id="btnEditar">
                <i class="bi bi-pencil-square"></i> Editar
              </button>
              <button type="button" class="btn btn-danger btn-sm" id="btnEliminar">
                <i class="bi bi-trash"></i> Eliminar
              </button>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaEstudiantes">
              <thead>
                <tr>
                  <th scope="col">Cédula</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Apellido</th>
                  <th scope="col">Dirección</th>
                  <th scope="col">Teléfono</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </main>

  <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="modalUsuarioLabel">Nuevo Usuario</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formUsuario">
            <input type="hidden" id="modo" value="nuevo">
            <input type="hidden" id="cedulaVieja">

            <div class="mb-3">
              <label for="cedula" class="form-label">Cédula</label>
              <input type="text" class="form-control" id="cedula" name="cedula" required>
            </div>
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
              <label for="apellido" class="form-label">Apellido</label>
              <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
              <label for="direccion" class="form-label">Dirección</label>
              <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="mb-3">
              <label for="telefono" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-uta" id="btnGuardarUsuario">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <footer>
    <p>© 2025 Universidad Técnica de Ambato · FISEI</p>
  </footer>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

  <script>
    const ROL = '<?php echo htmlspecialchars($rol, ENT_QUOTES, "UTF-8"); ?>';

    let estudianteSeleccionado = null;
    let modalUsuario = null;

    $(document).ready(function () {
      modalUsuario = new bootstrap.Modal(document.getElementById('modalUsuario'));

      if (ROL !== 'secretaria') {
        $('#btnNuevo, #btnEditar, #btnEliminar').hide();
      }

      cargarEstudiantes();

      $('#btnBuscar').on('click', function () {
        const ced = $('#txtBuscarCedula').val().trim();
        if (ced === '') {
          alert('Ingrese una cédula para buscar.');
          return;
        }
        cargarEstudiantes(ced);
      });

      $('#btnLimpiar').on('click', function () {
        $('#txtBuscarCedula').val('');
        cargarEstudiantes();
      });

      $('#btnNuevo').on('click', function () {
        $('#modo').val('nuevo');
        $('#cedulaVieja').val('');
        $('#formUsuario')[0].reset();
        $('#cedula').prop('disabled', false);
        $('#modalUsuarioLabel').text('Nuevo Usuario');
        modalUsuario.show();
      });

      $('#btnEditar').on('click', function () {
        if (!estudianteSeleccionado) {
          alert('Seleccione un estudiante de la tabla.');
          return;
        }
        $('#modo').val('editar');
        $('#cedulaVieja').val(estudianteSeleccionado.cedula);
        $('#cedula').val(estudianteSeleccionado.cedula).prop('disabled', true);
        $('#nombre').val(estudianteSeleccionado.nombre);
        $('#apellido').val(estudianteSeleccionado.apellido);
        $('#direccion').val(estudianteSeleccionado.direccion);
        $('#telefono').val(estudianteSeleccionado.telefono);
        $('#modalUsuarioLabel').text('Editar Usuario');
        modalUsuario.show();
      });

      $('#btnEliminar').on('click', function () {
        if (!estudianteSeleccionado) {
          alert('Seleccione un estudiante de la tabla.');
          return;
        }
        if (confirm('¿Está seguro de eliminar este usuario?')) {
          $.post('Models/destroy_user.php', { cedula: estudianteSeleccionado.cedula }, function (resp) {
            let data = resp;
            if (typeof resp === 'string') {
              try { data = JSON.parse(resp); } catch (e) {
                alert('Respuesta inválida del servidor.');
                return;
              }
            }
            if (data && data.success) {
              alert('Usuario eliminado correctamente.');
              cargarEstudiantes();
              estudianteSeleccionado = null;
            } else {
              alert((data && data.errorMsg) ? data.errorMsg : 'No se pudo eliminar el usuario.');
            }
          }, 'json');
        }
      });

      $('#btnGuardarUsuario').on('click', function () {
        const modo = $('#modo').val();
        const datos = $('#formUsuario').serialize();
        let url = '';

        if (modo === 'nuevo') {
          url = 'Models/save_user.php';
        } else {
          const cedVieja = encodeURIComponent($('#cedulaVieja').val());
          url = 'Models/update_user.php?cedulaVieja=' + cedVieja;
        }

        $.post(url, datos, function (resp) {
          let data = resp;
          if (typeof resp === 'string') {
            try { data = JSON.parse(resp); } catch (e) {
              alert('Respuesta inválida del servidor.');
              return;
            }
          }

          if (!data || data.success !== true) {
            alert((data && data.errorMsg) ? data.errorMsg : 'No se pudo guardar el estudiante.');
          } else {
            alert('Datos guardados correctamente.');
            modalUsuario.hide();
            cargarEstudiantes();
          }
        }, 'json');
      });

      $('#btnReporte').on('click', function () {
        verReporte();
      });
    });

    function cargarEstudiantes(cedula = '') {
      const params = {};
      if (cedula !== '') {
        params.cedula = cedula;
      }

      $.post('Models/get_users.php', params, function (data) {
        if (!data || !data.rows) return;
        const tbody = $('#tablaEstudiantes tbody');
        tbody.empty();
        estudianteSeleccionado = null;

        data.rows.forEach(function (est) {
          const tr = $('<tr></tr>');
          tr.append('<td>' + est.cedula + '</td>');
          tr.append('<td>' + est.nombre + '</td>');
          tr.append('<td>' + est.apellido + '</td>');
          tr.append('<td>' + est.direccion + '</td>');
          tr.append('<td>' + est.telefono + '</td>');

          tr.on('click', function () {
            $('#tablaEstudiantes tbody tr').removeClass('table-row-selected');
            $(this).addClass('table-row-selected');
            estudianteSeleccionado = est;
          });

          tbody.append(tr);
        });
      }, 'json');
    }

    function verReporte() {
      const reporte = $('#comboReportes').val();
      const row = estudianteSeleccionado;

      if (reporte === "reporteFPDF") {
        window.open('Reportes/ConFPDF/ReporteFPDF.php', '_blank');
      } else if (reporte === "reporteCedulaFPDF") {
        if (row) {
          const cedula = encodeURIComponent(row.cedula);
          window.open('Reportes/ConFPDF/ReporteFPDFCedula.php?cedula=' + cedula, '_blank');
        } else {
          alert('Seleccione un estudiante para el reporte por cédula.');
        }
      } else {
        alert('Seleccione un tipo de reporte.');
      }
    }
  </script>
</body>
</html>
