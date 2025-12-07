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
  <link rel="stylesheet" type="text/css" href="css/nosotros.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
  <header>
    <img src="imagenes/descarga.png" height="auto" width="100%">
  </header>

  <?php include 'nav.php'; ?>

  <div class="page-wrapper">
    <div class="page-header">
      <div class="page-header-top">
        <h1>Sistema de Gestión Académica</h1>
        <a href="Models/logout.php" class="logout-btn">
          <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
      </div>
      <p>
        Bienvenido <strong><?php echo htmlspecialchars($usuario); ?></strong> · Rol:
        <strong><?php echo htmlspecialchars($rol); ?></strong>
      </p>
    </div>

    <!-- Tabs de navegación -->
    <ul class="nav nav-tabs mb-3" id="mainTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="estudiantes-tab" data-bs-toggle="tab" data-bs-target="#estudiantes" type="button" role="tab">
          <i class="bi bi-people"></i> Estudiantes
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="cursos-tab" data-bs-toggle="tab" data-bs-target="#cursos" type="button" role="tab">
          <i class="bi bi-book"></i> Cursos
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="inscripciones-tab" data-bs-toggle="tab" data-bs-target="#inscripciones" type="button" role="tab">
          <i class="bi bi-journal-check"></i> Inscripciones
        </button>
      </li>
    </ul>

    <!-- Contenido de las tabs -->
    <div class="tab-content" id="mainTabsContent">
      <!-- TAB ESTUDIANTES -->
      <div class="tab-pane fade show active" id="estudiantes" role="tabpanel">
        <div class="card card-uta mb-4">
          <div class="card-header bg-white">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label mb-2 fw-bold">
                <i class="bi bi-search text-danger"></i> Búsqueda Rápida
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                  <i class="bi bi-person-badge"></i>
                </span>
                <input type="text" id="txtBuscarCedula" class="form-control border-start-0" placeholder="Ingrese número de cédula...">
                <button class="btn btn-uta" id="btnBuscar">
                  <i class="bi bi-search"></i> Buscar
                </button>
                <button class="btn btn-outline-danger" id="btnLimpiar" title="Limpiar búsqueda">
                  <i class="bi bi-x-circle"></i>
                </button>
              </div>
              <div class="form-text text-muted">
                <i class="bi bi-info-circle"></i> Busca estudiantes por número de cédula
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label mb-2 fw-bold">
                <i class="bi bi-file-earmark-pdf text-danger"></i> Reportes PDF
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light">
                  <i class="bi bi-filetype-pdf"></i>
                </span>
                <select id="comboReportes" class="form-select">
                  <option value="">Seleccione tipo de reporte...</option>
                  <option value="reporteFPDF">📄 Reporte General (Todos los estudiantes)</option>
                  <option value="reporteCedulaFPDF">👤 Reporte Individual (Por cédula)</option>
                </select>
                <button class="btn btn-danger" id="btnReporte">
                  <i class="bi bi-download"></i> Generar
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h5 class="card-title mb-1">
                <i class="bi bi-people-fill text-danger"></i> Listado de Estudiantes
              </h5>
              <p class="text-muted mb-0 small">
                <i class="bi bi-info-circle"></i> Haz clic en una fila para seleccionar un estudiante
              </p>
            </div>
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-success" id="btnNuevo" title="Agregar nuevo estudiante">
                <i class="bi bi-plus-circle-fill"></i> Nuevo
              </button>
              <button type="button" class="btn btn-primary" id="btnEditar" title="Editar estudiante seleccionado">
                <i class="bi bi-pencil-square"></i> Editar
              </button>
              <button type="button" class="btn btn-danger" id="btnEliminar" title="Eliminar estudiante seleccionado">
                <i class="bi bi-trash-fill"></i> Eliminar
              </button>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" id="tablaEstudiantes">
              <thead class="table-dark">
                <tr>
                  <th scope="col"><i class="bi bi-card-text"></i> Cédula</th>
                  <th scope="col"><i class="bi bi-person"></i> Nombre</th>
                  <th scope="col"><i class="bi bi-person-badge"></i> Apellido</th>
                  <th scope="col"><i class="bi bi-geo-alt"></i> Dirección</th>
                  <th scope="col"><i class="bi bi-telephone"></i> Teléfono</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <div class="mt-2 text-muted small">
            <i class="bi bi-info-circle-fill"></i> Total de estudiantes: <span id="totalEstudiantes" class="badge bg-danger">0</span>
          </div>
        </div>
      </div>
      </div>

      <!-- TAB CURSOS -->
      <div class="tab-pane fade" id="cursos" role="tabpanel">
        <div class="card card-uta mb-4">
          <div class="card-header bg-white">
            <div class="row g-3 align-items-end">
              <div class="col-md-6">
                <label class="form-label mb-1 fw-bold">
                  <i class="bi bi-search text-danger"></i> Búsqueda de cursos
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="bi bi-book"></i>
                  </span>
                  <input type="text" id="txtBuscarCurso" class="form-control" placeholder="Buscar curso por nombre...">
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label mb-1 fw-bold">
                  <i class="bi bi-file-earmark-pdf text-danger"></i> Reportes (FPDF)
                </label>
                <div class="input-group">
                  <select id="comboReportesCursos" class="form-select">
                    <option value="">Seleccione un reporte...</option>
                    <option value="reporteEstudiantesPorCurso">👥 Estudiantes por curso</option>
                  </select>
                  <button class="btn btn-danger" id="btnReporteCurso">
                    <i class="bi bi-download"></i> Generar
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h5 class="card-title mb-1">
                  <i class="bi bi-book-fill text-danger"></i> Catálogo de Cursos
                </h5>
                <p class="text-muted mb-0 small">
                  <i class="bi bi-info-circle"></i> Selecciona un curso para ver opciones
                </p>
              </div>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-success" id="btnNuevoCurso" title="Crear nuevo curso">
                  <i class="bi bi-plus-circle-fill"></i> Nuevo
                </button>
                <button type="button" class="btn btn-primary" id="btnEditarCurso" title="Editar curso">
                  <i class="bi bi-pencil-square"></i> Editar
                </button>
                <button type="button" class="btn btn-danger" id="btnEliminarCurso" title="Eliminar curso">
                  <i class="bi bi-trash-fill"></i> Eliminar
                </button>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover table-striped align-middle mb-0" id="tablaCursos">
                <thead class="table-dark">
                  <tr>
                    <th scope="col"><i class="bi bi-hash"></i> ID</th>
                    <th scope="col"><i class="bi bi-book-half"></i> Nombre</th>
                    <th scope="col"><i class="bi bi-calendar-event"></i> Fecha Creación</th>
                    <th scope="col"><i class="bi bi-people"></i> Total Estudiantes</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class="mt-2 text-muted small">
              <i class="bi bi-info-circle-fill"></i> Total de cursos: <span id="totalCursos" class="badge bg-danger">0</span>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB INSCRIPCIONES -->
      <div class="tab-pane fade" id="inscripciones" role="tabpanel">
        <div class="card card-uta mb-4">
          <div class="card-header bg-white">
            <div class="row g-3 align-items-end">
              <div class="col-md-12">
                <label class="form-label mb-2 fw-bold">
                  <i class="bi bi-person-check text-danger"></i> Seleccionar Estudiante
                </label>
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-light">
                    <i class="bi bi-mortarboard"></i>
                  </span>
                  <select id="comboEstudiante" class="form-select">
                    <option value="">-- Seleccione un estudiante para gestionar inscripciones --</option>
                  </select>
                </div>
                <div class="form-text text-muted">
                  <i class="bi bi-info-circle"></i> Selecciona un estudiante para ver sus cursos inscritos y disponibles
                </div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row g-4">
              <!-- Cursos Inscritos -->
              <div class="col-md-6">
                <div class="card border-success h-100">
                  <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                      <i class="bi bi-check-circle-fill"></i> Cursos Inscritos
                    </h5>
                  </div>
                  <div class="card-body">
                    <p class="text-muted small mb-3">
                      <i class="bi bi-info-circle"></i> Selecciona un curso inscrito para desinscribir
                    </p>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                      <table class="table table-hover table-sm align-middle mb-0" id="tablaInscritos">
                        <thead class="table-success sticky-top">
                          <tr>
                            <th scope="col"><i class="bi bi-book"></i> Curso</th>
                            <th scope="col"><i class="bi bi-calendar"></i> Fecha</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="2" class="text-center text-muted">
                              <i class="bi bi-inbox"></i> Selecciona un estudiante
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="card-footer bg-white border-top">
                    <button type="button" class="btn btn-danger w-100" id="btnDesinscribir">
                      <i class="bi bi-dash-circle-fill"></i> Desinscribir del Curso Seleccionado
                    </button>
                  </div>
                </div>
              </div>

              <!-- Cursos Disponibles -->
              <div class="col-md-6">
                <div class="card border-primary h-100">
                  <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                      <i class="bi bi-bookmark-plus-fill"></i> Cursos Disponibles
                    </h5>
                  </div>
                  <div class="card-body">
                    <p class="text-muted small mb-3">
                      <i class="bi bi-info-circle"></i> Selecciona un curso disponible para inscribir
                    </p>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                      <table class="table table-hover table-sm align-middle mb-0" id="tablaDisponibles">
                        <thead class="table-primary sticky-top">
                          <tr>
                            <th scope="col"><i class="bi bi-book-half"></i> Curso Disponible</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="text-center text-muted">
                              <i class="bi bi-inbox"></i> Selecciona un estudiante
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="card-footer bg-white border-top">
                    <button type="button" class="btn btn-success w-100" id="btnInscribir">
                      <i class="bi bi-plus-circle-fill"></i> Inscribir en Curso Seleccionado
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Usuario -->
  <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #a50000 0%, #7a0000 100%);">
          <h5 class="modal-title" id="modalUsuarioLabel">
            <i class="bi bi-person-plus-fill"></i> Nuevo Estudiante
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formUsuario">
            <input type="hidden" id="modo" value="nuevo">
            <input type="hidden" id="cedulaVieja">

            <div class="row g-3">
              <div class="col-md-6">
                <label for="cedula" class="form-label fw-bold">
                  <i class="bi bi-card-text text-danger"></i> Cédula
                </label>
                <input type="text" class="form-control form-control-lg" id="cedula" name="cedula" placeholder="Ej: 1234567890" required>
              </div>
              <div class="col-md-6">
                <label for="telefono" class="form-label fw-bold">
                  <i class="bi bi-telephone text-danger"></i> Teléfono
                </label>
                <input type="text" class="form-control form-control-lg" id="telefono" name="telefono" placeholder="Ej: 0987654321" required>
              </div>
              <div class="col-md-6">
                <label for="nombre" class="form-label fw-bold">
                  <i class="bi bi-person text-danger"></i> Nombre
                </label>
                <input type="text" class="form-control form-control-lg" id="nombre" name="nombre" placeholder="Ingrese el nombre" required>
              </div>
              <div class="col-md-6">
                <label for="apellido" class="form-label fw-bold">
                  <i class="bi bi-person-badge text-danger"></i> Apellido
                </label>
                <input type="text" class="form-control form-control-lg" id="apellido" name="apellido" placeholder="Ingrese el apellido" required>
              </div>
              <div class="col-md-12">
                <label for="direccion" class="form-label fw-bold">
                  <i class="bi bi-geo-alt text-danger"></i> Dirección
  <!-- Modal Curso -->
  <div class="modal fade" id="modalCurso" tabindex="-1" aria-labelledby="modalCursoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #a50000 0%, #7a0000 100%);">
          <h5 class="modal-title" id="modalCursoLabel">
            <i class="bi bi-book-fill"></i> Nuevo Curso
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formCurso">
            <input type="hidden" id="modoCurso" value="nuevo">
            <input type="hidden" id="idCurso">
            <div class="mb-3">
              <label for="nombreCurso" class="form-label fw-bold">
                <i class="bi bi-book-half text-danger"></i> Nombre del Curso
              </label>
              <input type="text" class="form-control form-control-lg" id="nombreCurso" name="nombre" placeholder="Ej: Programación Web" required>
              <div class="form-text">
                <i class="bi bi-info-circle"></i> Ingrese un nombre descriptivo para el curso
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancelar
          </button>
          <button type="button" class="btn btn-uta btn-lg" id="btnGuardarCurso">
            <i class="bi bi-save"></i> Guardar Curso
          </button>
        </div>
      </div>
    </div>
  </div>  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formCurso">
            <input type="hidden" id="modoCurso" value="nuevo">
            <input type="hidden" id="idCurso">
            <div class="mb-3">
              <label for="nombreCurso" class="form-label">Nombre del Curso</label>
              <input type="text" class="form-control" id="nombreCurso" name="nombre" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-uta" id="btnGuardarCurso">Guardar</button>
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
    let cursoSeleccionado = null;
    let cursoInscritoSeleccionado = null;
    let cursoDisponibleSeleccionado = null;
    let modalUsuario = null;
    let modalCurso = null;
    let searchTimeout = null;

    $(document).ready(function () {
      modalUsuario = new bootstrap.Modal(document.getElementById('modalUsuario'));
      modalCurso = new bootstrap.Modal(document.getElementById('modalCurso'));

      if (ROL !== 'admin') {
        $('#btnNuevo, #btnEditar, #btnEliminar').hide();
        $('#btnNuevoCurso, #btnEditarCurso, #btnEliminarCurso').hide();
      }

      cargarEstudiantes();
      cargarCursos();
      cargarComboEstudiantes();

      // Evento para cargar datos al cambiar de pestaña
      $('#cursos-tab').on('click', function () {
        cargarCursos();
      });

      $('#inscripciones-tab').on('click', function () {
        cargarComboEstudiantes();
      });

      // ==================== ESTUDIANTES ====================
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
            cargarComboEstudiantes();
          }
        }, 'json');
      });

      $('#btnReporte').on('click', function () {
        verReporte();
      });

      // ==================== CURSOS ====================
      $('#txtBuscarCurso').on('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
          cargarCursos();
        }, 300);
      });

      $('#btnReporteCurso').on('click', function () {
        verReporteCurso();
      });

      $('#btnNuevoCurso').on('click', function () {
        $('#modoCurso').val('nuevo');
        $('#idCurso').val('');
        $('#formCurso')[0].reset();
        $('#modalCursoLabel').text('Nuevo Curso');
        modalCurso.show();
      });

      $('#btnEditarCurso').on('click', function () {
        if (!cursoSeleccionado) {
          alert('Seleccione un curso de la tabla.');
          return;
        }
        $('#modoCurso').val('editar');
        $('#idCurso').val(cursoSeleccionado.id);
        $('#nombreCurso').val(cursoSeleccionado.nombre);
        $('#modalCursoLabel').text('Editar Curso');
        modalCurso.show();
      });

      $('#btnGuardarCurso').on('click', function () {
        const modo = $('#modoCurso').val();
        const nombre = $('#nombreCurso').val().trim();
        if (nombre === '') {
          alert('Ingrese el nombre del curso.');
          return;
        }

        let url = '';
        if (modo === 'nuevo') {
          url = 'Models/save_curso.php';
        } else {
          const id = $('#idCurso').val();
          url = 'Models/update_curso.php?id=' + id;
        }

        $.post(url, { nombre: nombre }, function (resp) {
          let data = resp;
          if (typeof resp === 'string') {
            try { data = JSON.parse(resp); } catch (e) {
              alert('Respuesta inválida del servidor.');
              return;
            }
          }

          if (data && data.success) {
            alert(modo === 'nuevo' ? 'Curso creado correctamente.' : 'Curso actualizado correctamente.');
            modalCurso.hide();
            cargarCursos();
            cursoSeleccionado = null;
          } else {
            alert((data && data.errorMsg) ? data.errorMsg : 'No se pudo guardar el curso.');
          }
        }, 'json');
      });

      $('#btnEliminarCurso').on('click', function () {
        if (!cursoSeleccionado) {
          alert('Seleccione un curso de la tabla.');
          return;
        }
        if (confirm('¿Está seguro de eliminar este curso?')) {
          $.post('Models/destroy_curso.php', { id: cursoSeleccionado.id }, function (resp) {
            let data = resp;
            if (typeof resp === 'string') {
              try { data = JSON.parse(resp); } catch (e) {
                alert('Respuesta inválida del servidor.');
                return;
              }
            }
            if (data && data.success) {
              alert('Curso eliminado correctamente.');
              cargarCursos();
              cursoSeleccionado = null;
            } else {
              alert((data && data.errorMsg) ? data.errorMsg : 'No se pudo eliminar el curso.');
            }
          }, 'json');
        }
      });

      // ==================== INSCRIPCIONES ====================
      $('#comboEstudiante').on('change', function () {
        const estudianteId = $(this).val();
        if (estudianteId) {
          cargarInscripciones(estudianteId);
        } else {
          $('#tablaInscritos tbody').empty();
          $('#tablaDisponibles tbody').empty();
        }
      });

      $('#btnInscribir').on('click', function () {
        if (!cursoDisponibleSeleccionado) {
          alert('Seleccione un curso disponible.');
          return;
        }

        const estudianteId = $('#comboEstudiante').val();
        if (!estudianteId) {
          alert('Seleccione un estudiante primero.');
          return;
        }

        $.post('Models/inscribir_curso.php', {
          estudiante_id: estudianteId,
          curso_id: cursoDisponibleSeleccionado.id
        }, function (resp) {
          let data = resp;
          if (typeof resp === 'string') {
            try { data = JSON.parse(resp); } catch (e) {
              alert('Respuesta inválida del servidor.');
              return;
            }
          }

          if (data && data.success) {
            alert('Estudiante inscrito correctamente.');
            cargarInscripciones(estudianteId);
            cursoDisponibleSeleccionado = null;
          } else {
            alert((data && data.errorMsg) ? data.errorMsg : 'No se pudo inscribir al estudiante.');
          }
        }, 'json');
      });

      $('#btnDesinscribir').on('click', function () {
        if (!cursoInscritoSeleccionado) {
          alert('Seleccione un curso inscrito.');
          return;
        }

        const estudianteId = $('#comboEstudiante').val();
        if (!estudianteId) {
          alert('Seleccione un estudiante primero.');
          return;
        }

        if (confirm('¿Está seguro de desinscribir al estudiante de este curso?')) {
          $.post('Models/desinscribir_curso.php', {
            estudiante_id: estudianteId,
            curso_id: cursoInscritoSeleccionado.id
          }, function (resp) {
            let data = resp;
            if (typeof resp === 'string') {
              try { data = JSON.parse(resp); } catch (e) {
                alert('Respuesta inválida del servidor.');
                return;
              }
            }

            if (data && data.success) {
              alert('Estudiante desinscrito correctamente.');
              cargarInscripciones(estudianteId);
              cursoInscritoSeleccionado = null;
            } else {
              alert((data && data.errorMsg) ? data.errorMsg : 'No se pudo desinscribir al estudiante.');
            }
          }, 'json');
        }
      });
    });

    // ==================== FUNCIONES ====================
    function cargarEstudiantes(cedula = '') {
      const params = {
        page: 1,
        rows: 1000
      };
      if (cedula !== '') {
        params.cedula = cedula;
      }

      $.post('Models/get_users.php', params, function (data) {
        if (!data || !data.rows) return;
        const tbody = $('#tablaEstudiantes tbody');
        tbody.empty();
        estudianteSeleccionado = null;

        // Actualizar badge de total
        $('#totalEstudiantes').text(data.rows.length);

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

    function cargarCursos() {
      const nombre = $('#txtBuscarCurso').val().trim();
      const params = {
        page: 1,
        rows: 1000
      };
      if (nombre !== '') {
        params.nombre = nombre;
      }

      console.log('Cargando cursos con params:', params);

      $.post('Models/get_cursos.php', params, function (data) {
        console.log('Respuesta get_cursos.php:', data);
        
        if (!data || !data.rows) {
          console.error('No hay datos o no hay rows:', data);
          return;
        }
        
        const tbody = $('#tablaCursos tbody');
        tbody.empty();
        cursoSeleccionado = null;

        // Actualizar badge de total
        $('#totalCursos').text(data.rows.length);

        console.log('Número de cursos recibidos:', data.rows.length);

        data.rows.forEach(function (curso) {
          const tr = $('<tr></tr>');
          tr.append('<td>' + curso.id + '</td>');
          tr.append('<td>' + curso.nombre + '</td>');
          tr.append('<td>' + curso.created_at + '</td>');
          tr.append('<td>' + curso.total_estudiantes + '</td>');

          tr.on('click', function () {
            $('#tablaCursos tbody tr').removeClass('table-row-selected');
            $(this).addClass('table-row-selected');
            cursoSeleccionado = curso;
          });

          tbody.append(tr);
        });
        
        console.log('Cursos cargados en la tabla');
      }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error al cargar cursos:', textStatus, errorThrown);
        console.error('Respuesta del servidor:', jqXHR.responseText);
      });
    }

    function cargarComboEstudiantes() {
      $.post('Models/get_users.php', { page: 1, rows: 1000 }, function (data) {
        if (!data || !data.rows) return;
        const combo = $('#comboEstudiante');
        combo.empty();
        combo.append('<option value="">Seleccione un estudiante...</option>');

        data.rows.forEach(function (est) {
          const option = $('<option></option>');
          option.val(est.id);
          option.text(est.nombre + ' ' + est.apellido + ' (' + est.cedula + ')');
          combo.append(option);
        });
      }, 'json');
    }

    function cargarInscripciones(estudianteId) {
      $.post('Models/get_inscripciones.php', { estudiante_id: estudianteId }, function (data) {
        if (!data || !data.success) return;

        // Cursos Inscritos
        const tbodyInscritos = $('#tablaInscritos tbody');
        tbodyInscritos.empty();
        cursoInscritoSeleccionado = null;

        data.inscritos.forEach(function (curso) {
          const tr = $('<tr></tr>');
          tr.append('<td>' + curso.nombre + '</td>');
          tr.append('<td>' + curso.fecha_inscripcion + '</td>');

          tr.on('click', function () {
            $('#tablaInscritos tbody tr').removeClass('table-row-selected');
            $(this).addClass('table-row-selected');
            cursoInscritoSeleccionado = curso;
          });

          tbodyInscritos.append(tr);
        });

        // Cursos Disponibles
        const tbodyDisponibles = $('#tablaDisponibles tbody');
        tbodyDisponibles.empty();
        cursoDisponibleSeleccionado = null;

        data.disponibles.forEach(function (curso) {
          const tr = $('<tr></tr>');
          tr.append('<td>' + curso.nombre + '</td>');

          tr.on('click', function () {
            $('#tablaDisponibles tbody tr').removeClass('table-row-selected');
            $(this).addClass('table-row-selected');
            cursoDisponibleSeleccionado = curso;
          });

          tbodyDisponibles.append(tr);
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

    function verReporteCurso() {
      const reporte = $('#comboReportesCursos').val();
      const curso = cursoSeleccionado;

      if (reporte === "reporteEstudiantesPorCurso") {
        if (curso) {
          const cursoId = encodeURIComponent(curso.id);
          window.open('Reportes/ConFPDF/ReporteEstudiantesPorCurso.php?curso_id=' + cursoId, '_blank');
        } else {
          alert('Seleccione un curso de la tabla para generar el reporte.');
        }
      } else {
        alert('Seleccione un tipo de reporte.');
      }
    }
  </script>
</body>
</html>
