/**
 * Sistema de Gestión Académica - Módulo Nosotros
 * Gestión de Estudiantes, Cursos e Inscripciones con Bootstrap
 */

// Variables globales
let estudianteSeleccionado = null;
let cursoSeleccionado = null;
let cursoInscritoSeleccionado = null;
let cursoDisponibleSeleccionado = null;
let modalUsuario = null;
let modalCurso = null;
let searchTimeout = null;

// Inicialización del documento
$(document).ready(function () {
  // Inicializar modales de Bootstrap
  modalUsuario = new bootstrap.Modal(document.getElementById('modalUsuario'));
  modalCurso = new bootstrap.Modal(document.getElementById('modalCurso'));

  // Ocultar botones de administración si no es admin
  // La secretaria tiene todo el CRUD; el admin solo consulta y reportes
if (window.ROL !== 'secretaria') {
  $('#btnNuevo, #btnEditar, #btnEliminar').hide();
  $('#btnNuevoCurso, #btnEditarCurso, #btnEliminarCurso').hide();
  $('#btnInscribir, #btnDesinscribir').hide();
}

  // Cargar datos iniciales
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

  // ==================== EVENTOS DE ESTUDIANTES ====================
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

  // ==================== EVENTOS DE CURSOS ====================
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

  // ==================== EVENTOS DE INSCRIPCIONES ====================
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

// ==================== FUNCIONES DE CARGA DE DATOS ====================

/**
 * Carga la lista de estudiantes con opción de filtrar por cédula
 * @param {string} cedula - Cédula para filtrar (opcional)
 */
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

/**
 * Carga la lista de cursos con opción de filtrar por nombre
 */
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

/**
 * Carga el combo de estudiantes para la pestaña de inscripciones
 */
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

/**
 * Carga las inscripciones de un estudiante (cursos inscritos y disponibles)
 * @param {number} estudianteId - ID del estudiante
 */
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

// ==================== FUNCIONES DE REPORTES ====================

/**
 * Genera reportes de estudiantes
 */
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

/**
 * Genera reportes de cursos
 */
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
