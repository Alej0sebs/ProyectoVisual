/**
 * Sistema de Gestión Académica - Módulo Servicios
 * Gestión de Estudiantes, Cursos e Inscripciones con jQuery EasyUI
 */

// Variable global para URLs de formularios
var url;
var urlCurso;
var estudianteSeleccionadoId = null;

// ========== INICIALIZACIÓN ==========
$(document).ready(function() {
  $('#comboEstudiantes').combobox({
    url: 'Models/get_users.php',
    method: 'post',
    valueField: 'id',
    textField: 'nombre_completo',
    panelHeight: 'auto',
    editable: true,
    prompt: 'Seleccione un estudiante...',
    onLoadError: function() {
      $.messager.alert('Error', 'No se pudieron cargar los estudiantes.');
    },
    onSelect: function(rec) {
      if(rec && rec.id) {
        cargarCursosEstudiante(rec.id);
      }
    }
  });

  var timeoutBusqueda;
  $('#txtBuscarCurso').textbox({
    onChange: function(value) {
      clearTimeout(timeoutBusqueda);
      timeoutBusqueda = setTimeout(function() {
        var nombre = value.trim();
        if(nombre === '') {
          $('#dgCursos').datagrid('load', {});
        } else {
          $('#dgCursos').datagrid('load', { nombre: nombre });
        }
      }, 300);
    }
  });
});

// ========== FUNCIONES PARA ESTUDIANTES ==========

function buscarCedula(){
  var ced = $('#txtBuscarCedula').textbox('getValue').trim();
  if(ced === ''){
    $.messager.alert('Aviso','Ingrese una cédula para buscar.');
    return;
  }
  $('#dg').datagrid('load',{ cedula: ced });
}

function recargarTabla(){
  $('#txtBuscarCedula').textbox('setValue','');
  $('#dg').datagrid('load',{});
}

function verReporte() {
  var reporte = $('#comboReportes').combobox('getValue');
  var row = $('#dg').datagrid('getSelected');

  if (reporte === "reporteFPDF") {
    window.open('Reportes/ConFPDF/ReporteFPDF.php', '_blank');
  } else if (reporte === "reporteCedulaFPDF") {
    if (row) {
      var cedula = encodeURIComponent(row.cedula);
      window.open('Reportes/ConFPDF/ReporteFPDFCedula.php?cedula=' + cedula, '_blank');
    } else {
      $.messager.alert('Aviso', 'Por favor, seleccione un estudiante para el reporte por cédula.');
    }
  } else {
    $.messager.alert('Aviso', 'Seleccione un tipo de reporte.');
  }
}

function newUser() {
  if (typeof ROL === 'undefined' || ROL !== 'secretaria') {
    $.messager.alert('Acceso restringido', 'Solo la secretaria puede registrar estudiantes.');
    return;
  }
  $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo Usuario');
  $('#fm').form('clear');
  url = 'Models/save_user.php';
}

function editUser() {
  if (typeof ROL === 'undefined' || ROL !== 'secretaria') {
    $.messager.alert('Acceso restringido', 'Solo la secretaria puede editar estudiantes.');
    return;
  }
  var row = $('#dg').datagrid('getSelected');
  if (row) {
    $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar Usuario');
    $('#fm').form('load', row);
    url = 'Models/update_user.php?cedulaVieja=' + row.cedula;
  }
}

function saveUser() {
  if (typeof ROL === 'undefined' || ROL !== 'secretaria') {
    $.messager.alert('Acceso restringido', 'Solo la secretaria puede guardar cambios de estudiantes.');
    return;
  }
  $('#fm').form('submit', {
    url: url,
    iframe: false,
    onSubmit: function () {
      return $(this).form('validate');
    },
    success: function (result) {
      var data = result;
      if (typeof result === 'string') {
        try {
          data = JSON.parse(result);
        } catch(e) {
          $.messager.show({
            title: 'Error',
            msg: 'Respuesta inválida del servidor.'
          });
          return;
        }
      }
      if (!data || data.success !== true) {
        $.messager.show({
          title: 'Error',
          msg: data && data.errorMsg ? data.errorMsg : 'No se pudo guardar el estudiante.'
        });
      } else {
        $.messager.show({
          title: 'Éxito',
          msg: 'Estudiante guardado correctamente.',
          timeout: 2000
        });
        $('#dlg').dialog('close');
        $('#dg').datagrid('load', {});
        $('#dg').datagrid('getPager').pagination({pageNumber: 1});
        $('#comboEstudiantes').combobox('reload');
      }
    }
  });
}

function destroyUser() {
  if (typeof ROL === 'undefined' || ROL !== 'secretaria') {
    $.messager.alert('Acceso restringido', 'Solo la secretaria puede eliminar estudiantes.');
    return;
  }
  var row = $('#dg').datagrid('getSelected');
  if (row) {
    $.messager.confirm('Confirmar', '¿Estás seguro de eliminar este usuario?', function (r) {
      if (r) {
        $.post('Models/destroy_user.php', { cedula: row.cedula }, function (result) {
          var data = result;
          if (typeof result === 'string') {
            try { data = JSON.parse(result); } catch(e) {
              $.messager.show({
                title: 'Error',
                msg: 'Respuesta inválida del servidor.'
              });
              return;
            }
          }
          if (data && data.success) {
            $('#dg').datagrid('reload');
          } else {
            $.messager.show({
              title: 'Error',
              msg: data && data.errorMsg ? data.errorMsg : 'No se pudo eliminar el estudiante.'
            });
          }
        }, 'json');
      }
    });
  }
}

// ========== FUNCIONES PARA CURSOS ==========

function newCurso() {
  if (typeof ROL === 'undefined' || ROL !== 'secretaria') {
    $.messager.alert('Acceso restringido', 'Solo la secretaria puede crear cursos.');
    return;
  }
  $('#dlgCurso').dialog('open').dialog('center').dialog('setTitle', 'Nuevo Curso');
  $('#fmCurso').form('clear');
  $('#cursoId').val('');
  urlCurso = 'Models/save_curso.php';
}

function editCurso() {
  if (typeof ROL === 'undefined' || ROL !== 'secretaria') {
    $.messager.alert('Acceso restringido', 'Solo la secretaria puede editar cursos.');
    return;
  }
  var row = $('#dgCursos').datagrid('getSelected');
  if (row) {
    $('#dlgCurso').dialog('open').dialog('center').dialog('setTitle', 'Editar Curso');
    $('#fmCurso').form('load', row);
    $('#cursoId').val(row.id);
    urlCurso = 'Models/update_curso.php?id=' + row.id;
  } else {
    $.messager.alert('Aviso', 'Por favor, seleccione un curso.');
  }
}

function saveCurso() {
  if (typeof ROL === 'undefined' || ROL !== 'secretaria') {
    $.messager.alert('Acceso restringido', 'Solo la secretaria puede guardar cambios de cursos.');
    return;
  }
  $('#fmCurso').form('submit', {
    url: urlCurso,
    iframe: false,
    onSubmit: function () {
      return $(this).form('validate');
    },
    success: function (result) {
      var data = result;
      if (typeof result === 'string') {
        try {
          data = JSON.parse(result);
        } catch(e) {
          $.messager.show({
            title: 'Error',
            msg: 'Respuesta inválida del servidor.'
          });
          return;
        }
      }
      if (!data || data.success !== true) {
        $.messager.show({
          title: 'Error',
          msg: data && data.errorMsg ? data.errorMsg : 'No se pudo guardar el curso.'
        });
      } else {
        $.messager.show({
          title: 'Éxito',
          msg: 'Curso guardado correctamente.'
        });
        $('#dlgCurso').dialog('close');
        $('#dgCursos').datagrid('reload');
      }
    }
  });
}

function destroyCurso() {
  if (typeof ROL === 'undefined' || ROL !== 'secretaria') {
    $.messager.alert('Acceso restringido', 'Solo la secretaria puede eliminar cursos.');
    return;
  }
  var row = $('#dgCursos').datagrid('getSelected');
  if (row) {
    $.messager.confirm('Confirmar', '¿Estás seguro de eliminar este curso? Se eliminarán todas las inscripciones asociadas.', function (r) {
      if (r) {
        $.post('Models/destroy_curso.php', { id: row.id }, function (result) {
          var data = result;
          if (typeof result === 'string') {
            try { data = JSON.parse(result); } catch(e) {
              $.messager.show({
                title: 'Error',
                msg: 'Respuesta inválida del servidor.'
              });
              return;
            }
          }
          if (data && data.success) {
            $.messager.show({
              title: 'Éxito',
              msg: 'Curso eliminado correctamente.'
            });
            $('#dgCursos').datagrid('reload');
          } else {
            $.messager.show({
              title: 'Error',
              msg: data && data.errorMsg ? data.errorMsg : 'No se pudo eliminar el curso.'
            });
          }
        }, 'json');
      }
    });
  } else {
    $.messager.alert('Aviso', 'Por favor, seleccione un curso.');
  }
}

function verReporteCurso() {
  var reporte = $('#comboReportesCursos').combobox('getValue');
  var row = $('#dgCursos').datagrid('getSelected');

  if (reporte === "reporteEstudiantesPorCurso") {
    if (row) {
      var cursoId = encodeURIComponent(row.id);
      window.open('Reportes/ConFPDF/ReporteEstudiantesPorCurso.php?curso_id=' + cursoId, '_blank');
    } else {
      $.messager.alert('Aviso', 'Por favor, seleccione un curso para el reporte.');
    }
  } else {
    $.messager.alert('Aviso', 'Seleccione un tipo de reporte.');
  }
}

// ========== FUNCIONES PARA INSCRIPCIONES ==========

function cargarCursosEstudiante(estudianteId) {
  estudianteSeleccionadoId = estudianteId;
  $('#inscripcionesContainer').show();

  $('#cursosDisponibles').html('<p style="text-align:center;padding:20px;">Cargando cursos...</p>');
  $('#cursosInscritos').html('<p style="text-align:center;padding:20px;">Cargando cursos...</p>');

  $.post('Models/get_inscripciones.php', { estudiante_id: estudianteId }, function(data) {
    if(data && data.success) {
      mostrarCursosDisponibles(data.disponibles || []);
      mostrarCursosInscritos(data.inscritos || []);
    } else {
      $.messager.alert('Error', 'No se pudieron cargar los cursos: ' + (data.errorMsg || 'Error desconocido'));
      $('#cursosDisponibles').html('<p style="color:red;text-align:center;padding:20px;">Error al cargar cursos</p>');
      $('#cursosInscritos').html('<p style="color:red;text-align:center;padding:20px;">Error al cargar cursos</p>');
    }
  }, 'json').fail(function(jqXHR, textStatus) {
    $.messager.alert('Error', 'Error de conexión: ' + textStatus);
    $('#cursosDisponibles').html('<p style="color:red;text-align:center;padding:20px;">Error de conexión</p>');
    $('#cursosInscritos').html('<p style="color:red;text-align:center;padding:20px;">Error de conexión</p>');
  });
}

function mostrarCursosDisponibles(cursos) {
  var html = '';
  if(cursos.length === 0) {
    html = '<p style="color:#999;text-align:center;padding:20px;">No hay cursos disponibles</p>';
  } else {
    cursos.forEach(function(curso) {
      html += '<div style="padding:10px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">';
      html += '<span>' + curso.nombre + '</span>';
      if (typeof ROL !== 'undefined' && ROL === 'secretaria') {
        html += '<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="inscribirCurso(' + curso.id + ')">Inscribir</a>';
      }
      html += '</div>';
    });
  }
  $('#cursosDisponibles').html(html);
  $.parser.parse($('#cursosDisponibles'));
}

function mostrarCursosInscritos(cursos) {
  var html = '';
  if(cursos.length === 0) {
    html = '<p style="color:#999;text-align:center;padding:20px;">No está inscrito en ningún curso</p>';
  } else {
    cursos.forEach(function(curso) {
      html += '<div style="padding:10px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">';
      html += '<div>';
      html += '<span style="font-weight:600;">' + curso.nombre + '</span><br>';
      html += '<small style="color:#666;">Inscrito: ' + curso.fecha_inscripcion + '</small>';
      html += '</div>';
      if (typeof ROL !== 'undefined' && ROL === 'secretaria') {
        html += '<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="desinscribirCurso(' + curso.id + ')">Desinscribir</a>';
      }
      html += '</div>';
    });
  }
  $('#cursosInscritos').html(html);
  $.parser.parse($('#cursosInscritos'));
}

function inscribirCurso(cursoId) {
  if (typeof ROL === 'undefined' || ROL !== 'secretaria') {
    $.messager.alert('Acceso restringido', 'Solo la secretaria puede inscribir estudiantes en cursos.');
    return;
  }

  if(!estudianteSeleccionadoId) {
    $.messager.alert('Error', 'No hay estudiante seleccionado.');
    return;
  }

  $.post('Models/inscribir_curso.php', {
    estudiante_id: estudianteSeleccionadoId,
    curso_id: cursoId
  }, function(result) {
    var data = result;
    if (typeof result === 'string') {
      try { data = JSON.parse(result); } catch(e) {
        $.messager.show({ title: 'Error', msg: 'Respuesta inválida del servidor.' });
        return;
      }
    }
    if (data && data.success) {
      $.messager.show({ title: 'Éxito', msg: 'Estudiante inscrito correctamente.' });
      cargarCursosEstudiante(estudianteSeleccionadoId);
      $('#dgCursos').datagrid('reload');
    } else {
      $.messager.show({ title: 'Error', msg: data.errorMsg || 'No se pudo inscribir.' });
    }
  }, 'json');
}

function desinscribirCurso(cursoId) {
  if (typeof ROL === 'undefined' || ROL !== 'secretaria') {
    $.messager.alert('Acceso restringido', 'Solo la secretaria puede desinscribir estudiantes.');
    return;
  }

  if(!estudianteSeleccionadoId) {
    $.messager.alert('Error', 'No hay estudiante seleccionado.');
    return;
  }

  $.messager.confirm('Confirmar', '¿Desea desinscribir al estudiante de este curso?', function(r) {
    if(r) {
      $.post('Models/desinscribir_curso.php', {
        estudiante_id: estudianteSeleccionadoId,
        curso_id: cursoId
      }, function(result) {
        var data = result;
        if (typeof result === 'string') {
          try { data = JSON.parse(result); } catch(e) {
            $.messager.show({ title: 'Error', msg: 'Respuesta inválida del servidor.' });
            return;
          }
        }
        if (data && data.success) {
          $.messager.show({ title: 'Éxito', msg: 'Estudiante desinscrito correctamente.' });
          cargarCursosEstudiante(estudianteSeleccionadoId);
          $('#dgCursos').datagrid('reload');
        } else {
          $.messager.show({ title: 'Error', msg: data.errorMsg || 'No se pudo desinscribir.' });
        }
      }, 'json');
    }
  });
}
