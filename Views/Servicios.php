<?php
session_start();
require 'Models/db.php';

$logueado = isset($_SESSION['usuario']);
$rol      = $logueado ? $_SESSION['rol'] : null;

$login_error = '';
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $login_error = 'Usuario o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Registro de Estudiantes</title>
  <link rel="stylesheet" type="text/css" href="css/servicios.css">
  <link rel="stylesheet" type="text/css" href="css/nav.css">
  <link rel="stylesheet" type="text/css" href="jquery/themes/default/easyui.css">
  <link rel="stylesheet" type="text/css" href="jquery/themes/icon.css">
  <link rel="stylesheet" type="text/css" href="jquery/themes/color.css">
  <link rel="stylesheet" type="text/css" href="jquery/demo/demo.css">
  <script type="text/javascript" src="jquery/jquery.min.js"></script>
  <script type="text/javascript" src="jquery/jquery.easyui.min.js"></script>

  <style>
  :root {
    --rojo-uta: #a50000;
    --rojo-uta-oscuro: #7a0000;
    --rojo-uta-claro: #fbeaea;
  }

  * {
    box-sizing: border-box;
  }

  body {
    background: radial-gradient(circle at top, #ffffff 0%, #fbeaea 40%, #ffffff 100%);
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    min-height: 100vh;
    color: #333;
  }

  .page-wrapper {
    width: 100%;
    margin: 1.5rem 0 2.5rem;
    padding: 0 0 2rem;
  }

  .page-header {
    text-align: center;
    margin-bottom: 1.5rem;
  }

  .page-header h1 {
    margin: 0;
    font-size: 2rem;
    color: var(--rojo-uta);
    letter-spacing: 0.03em;
  }

  .page-header p {
    margin: 0.4rem 0 0;
    font-size: 0.95rem;
    color: #6b6b6b;
  }

  .panel {
    max-width: 1100px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.98);
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    padding: 1.4rem 1.4rem 1.8rem;
    border: 1px solid rgba(165, 0, 0, 0.10);
  }

  .panel-header {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    padding-bottom: 0.8rem;
  }

  .panel-header-left,
  .panel-header-right {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
  }

  .panel-header-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--rojo-uta);
    margin-right: 0.5rem;
  }

  .easyui-datagrid,
  .datagrid-view {
    background: transparent !important;
    border-radius: 10px;
  }

  .datagrid-header-row {
    background: var(--rojo-uta) !important;
    color: #fff !important;
    font-weight: 600;
  }

  .datagrid-header,
  .datagrid-htable {
    border-radius: 8px 8px 0 0;
    overflow: hidden;
  }

  .datagrid-row-alt {
    background: var(--rojo-uta-claro) !important;
  }

  .datagrid-row-selected {
    background: #ffd6d6 !important;
  }

  .datagrid-cell {
    font-size: 0.9rem;
  }

  .easyui-linkbutton {
    background: var(--rojo-uta) !important;
    color: #fff !important;
    border-radius: 6px !important;
    border: none !important;
    font-weight: 600;
    margin-right: 4px;
    padding: 4px 10px !important;
    font-size: 0.86rem;
    transition: background 0.2s, transform 0.1s;
  }

  .easyui-linkbutton:hover {
    background: var(--rojo-uta-oscuro) !important;
    color: #fff !important;
    transform: translateY(-1px);
  }

  #txtBuscarCedula,
  #comboReportes {
    height: 32px;
  }

  .easyui-dialog {
    border-radius: 12px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18) !important;
  }

  .easyui-dialog h3 {
    color: var(--rojo-uta);
    text-align: center;
    margin-bottom: 1rem;
  }

  .easyui-dialog input,
  .easyui-dialog select {
    border-radius: 6px !important;
    padding: 6px 10px !important;
  }

  /* Estilos para las pestañas */
  .tabs-title {
    font-size: 0.95rem !important;
    font-weight: 600 !important;
    padding: 8px 15px !important;
  }

  .tabs-selected .tabs-title {
    color: var(--rojo-uta) !important;
    border-bottom: 3px solid var(--rojo-uta) !important;
  }

  .tabs-header {
    border-bottom: 2px solid var(--rojo-uta-claro) !important;
    background: #fff !important;
  }

  .tabs-panels {
    background: #fff !important;
    border: none !important;
  }

  .panel-body {
    overflow: visible !important;
  }

  /* Ajustar altura de inputs en EasyUI */
  .textbox .textbox-text {
    height: 30px !important;
    line-height: 30px !important;
  }

  .combo .combo-text {
    height: 30px !important;
    line-height: 30px !important;
  }

  footer {
    text-align: center;
    color: #fff;
    font-size: 0.95em;
    margin-top: 1rem;
    padding: 0.8rem 0 1rem;
    background: var(--rojo-uta);
  }

  .login-wrapper {
    max-width: 420px;
    margin: 2rem auto 0;
  }

  .login-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.8rem 2rem 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    border-top: 4px solid var(--rojo-uta);
  }

  .login-card h2 {
    margin: 0 0 0.5rem;
    text-align: center;
    color: var(--rojo-uta);
  }

  .login-card p {
    margin: 0 0 1rem;
    text-align: center;
    font-size: 0.9rem;
    color: #555;
  }

  .login-card label {
    font-size: 0.86rem;
    font-weight: 600;
    color: #444;
  }

  .login-card input {
    width: 100%;
    padding: 7px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin-top: 2px;
    margin-bottom: 10px;
    font-size: 0.9rem;
  }

  .login-error {
    color: #b30000;
    background: #ffe3e3;
    border-radius: 6px;
    padding: 6px 10px;
    margin-bottom: 0.8rem;
    font-size: 0.85rem;
  }

  @media (max-width: 768px) {
    .panel {
      margin: 0 0.5rem;
      padding: 1rem;
    }

    .page-header h1 {
      font-size: 1.6rem;
    }
  }
  </style>
</head>

<body>
  <header>
    <img src="imagenes/descarga.png" height="auto" width="100%">
  </header>

  <?php include 'nav.php'; ?>

  <?php if (!$logueado): ?>

    <div class="page-wrapper">
      <div class="page-header">
        <h1>Acceso al sistema</h1>
        <p>Inicie sesión como Administrador o Secretaria para continuar.</p>
      </div>

      <div class="login-wrapper">
        <div class="login-card">
          <h2>Iniciar sesión</h2>
          <p>Ingrese sus credenciales para acceder a Servicios.</p>

          <?php if ($login_error !== ''): ?>
            <div class="login-error"><?php echo htmlspecialchars($login_error); ?></div>
          <?php endif; ?>

          <form method="post" action="Models/login.php">
            <input type="hidden" name="redirect" value="<?php echo isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'Servicios'; ?>">

            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" autocomplete="off">

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena">

            <button type="submit" class="easyui-linkbutton" style="width:100%;margin-top:8px;">
              Entrar
            </button>
          </form>

        </div>
      </div>
    </div>

  <?php else: ?>

    <div class="page-wrapper">
      <div class="page-header">
        <h1>Sistema de Gestión Académica</h1>
        <p>
          Bienvenido <?php echo htmlspecialchars($_SESSION['usuario']); ?> · Rol:
          <strong><?php echo htmlspecialchars($rol); ?></strong>
        </p>
        <div style="margin-top:8px;">
          <a href="Models/logout.php" class="easyui-linkbutton" iconCls="icon-cancel">
            Cerrar sesión
          </a>
        </div>
      </div>

      <div class="panel">
        <!-- Pestañas de navegación -->
        <div class="easyui-tabs" style="height:550px" data-options="tabPosition:'top',plain:true,narrow:true">
          
          <!-- PESTAÑA 1: ESTUDIANTES -->
          <div title="📚 Estudiantes" style="padding:15px">
            <!-- Controles superiores -->
            <div style="margin-bottom:15px;padding:12px;background:#fafafa;border-radius:8px;border:1px solid #e0e0e0;">
              <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
                <!-- Búsqueda -->
                <div style="flex:1;min-width:250px;">
                  <label style="display:block;margin-bottom:4px;font-size:0.85rem;font-weight:600;color:#666;">Búsqueda rápida</label>
                  <div style="display:flex;gap:5px;">
                    <input id="txtBuscarCedula" class="easyui-textbox" prompt="Buscar por cédula..." style="width:180px;">
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="buscarCedula()">Buscar</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="recargarTabla()">Limpiar</a>
                  </div>
                </div>

                <!-- Reportes -->
                <div style="flex:1;min-width:300px;">
                  <label style="display:block;margin-bottom:4px;font-size:0.85rem;font-weight:600;color:#666;">Reportes</label>
                  <div style="display:flex;gap:5px;">
                    <select id="comboReportes" class="easyui-combobox" style="width:220px">
                      <option value="">Seleccione un reporte...</option>
                      <option value="reporteFPDF">Reporte general PDF (FPDF)</option>
                      <option value="reporteCedulaFPDF">Reporte por cédula PDF (FPDF)</option>
                    </select>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" onclick="verReporte()">Ver reporte</a>
                  </div>
                </div>
              </div>
            </div>

            <table id="dg" title="Listado de estudiantes" class="easyui-datagrid" style="width:100%;height:360px"
              url="Models/get_users.php"
              method="post"
              toolbar="#toolbar" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true">
              <thead>
                <tr>
                  <th field="cedula" width="50">Cédula</th>
                  <th field="nombre" width="80">Nombre</th>
                  <th field="apellido" width="50">Apellido</th>
                  <th field="direccion" width="50">Dirección</th>
                  <th field="telefono" width="50">Teléfono</th>
                </tr>
              </thead>
            </table>

            <?php if ($rol === 'admin'): ?>
              <div id="toolbar" style="padding:8px 0 0;">
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="false" onclick="newUser()">Nuevo</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="false" onclick="editUser()">Editar</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="false" onclick="destroyUser()">Eliminar</a>
              </div>
            <?php endif; ?>
          </div>

          <!-- PESTAÑA 2: CURSOS -->
          <div title="📖 Cursos" style="padding:15px">
            <div style="margin-bottom:15px;">
              <h3 style="color:var(--rojo-uta);margin:0 0 5px 0;font-size:1.1rem;">Gestión de Cursos</h3>
              <p style="margin:0;color:#666;font-size:0.85rem;">Administre los cursos disponibles en el sistema</p>
            </div>

            <table id="dgCursos" title="Listado de cursos disponibles" class="easyui-datagrid" style="width:100%;height:390px"
              url="Models/get_cursos.php"
              method="post"
              toolbar="#toolbarCursos" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true">
              <thead>
                <tr>
                  <th field="id" width="20">ID</th>
                  <th field="nombre" width="80">Nombre del Curso</th>
                  <th field="total_estudiantes" width="30">Estudiantes Inscritos</th>
                  <th field="created_at" width="40">Fecha de Creación</th>
                </tr>
              </thead>
            </table>

            <?php if ($rol === 'admin'): ?>
              <div id="toolbarCursos" style="padding:8px 0 0;">
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="false" onclick="newCurso()">Nuevo Curso</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="false" onclick="editCurso()">Editar</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="false" onclick="destroyCurso()">Eliminar</a>
              </div>
            <?php endif; ?>
          </div>

          <!-- PESTAÑA 3: INSCRIPCIONES -->
          <div title="✏️ Inscripciones" style="padding:15px">
            <div style="margin-bottom:15px;">
              <h3 style="color:var(--rojo-uta);margin:0 0 5px 0;font-size:1.1rem;">Gestionar Inscripciones</h3>
              <p style="margin:0;color:#666;font-size:0.85rem;">Seleccione un estudiante y asigne o remueva cursos</p>
            </div>

            <div style="margin-bottom:20px;padding:15px;background:#fafafa;border-radius:8px;border:1px solid #e0e0e0;">
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#444;font-size:0.9rem;">Seleccionar Estudiante:</label>
              <select id="comboEstudiantes" class="easyui-combobox" style="width:100%;max-width:500px;">
              </select>
            </div>

            <div id="inscripcionesContainer" style="display:none;">
              <div style="display:flex;gap:15px;flex-wrap:wrap;">
                <!-- Cursos disponibles -->
                <div style="flex:1;min-width:300px;">
                  <h4 style="color:var(--rojo-uta);margin:0 0 10px 0;font-size:1rem;font-weight:600;">📚 Cursos Disponibles</h4>
                  <div id="cursosDisponibles" style="max-height:280px;overflow-y:auto;border:1px solid #ddd;border-radius:8px;padding:10px;background:#fff;">
                    <!-- Se llenará dinámicamente -->
                  </div>
                </div>

                <!-- Cursos inscritos -->
                <div style="flex:1;min-width:300px;">
                  <h4 style="color:var(--rojo-uta);margin:0 0 10px 0;font-size:1rem;font-weight:600;">✅ Cursos Inscritos</h4>
                  <div id="cursosInscritos" style="max-height:280px;overflow-y:auto;border:1px solid #ddd;border-radius:8px;padding:10px;background:#fff;">
                    <!-- Se llenará dinámicamente -->
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Dialog para Estudiantes -->
    <div id="dlg" class="easyui-dialog" style="width:400px"
      data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
      <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
        <h3>Información del Usuario</h3>
        <div style="margin-bottom:10px">
          <input name="cedula" class="easyui-textbox" required="true" label="Cédula:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
          <input name="nombre" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
          <input name="apellido" class="easyui-textbox" required="true" label="Apellido:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
          <input name="direccion" class="easyui-textbox" required="true" label="Dirección:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
          <input name="telefono" class="easyui-textbox" required="true" label="Teléfono:" style="width:100%">
        </div>
      </form>
    </div>

    <div id="dlg-buttons">
      <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()"
        style="width:90px">Guardar</a>
      <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel"
        onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
    </div>

    <!-- Dialog para Cursos -->
    <div id="dlgCurso" class="easyui-dialog" style="width:400px"
      data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-curso'">
      <form id="fmCurso" method="post" novalidate style="margin:0;padding:20px 50px">
        <h3>Información del Curso</h3>
        <input type="hidden" name="id" id="cursoId">
        <div style="margin-bottom:10px">
          <input name="nombre" class="easyui-textbox" required="true" label="Nombre del Curso:" style="width:100%">
        </div>
      </form>
    </div>

    <div id="dlg-buttons-curso">
      <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveCurso()"
        style="width:90px">Guardar</a>
      <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel"
        onclick="javascript:$('#dlgCurso').dialog('close')" style="width:90px">Cancelar</a>
    </div>

  <?php endif; ?>

  <script type="text/javascript">
    // ========== INICIALIZACIÓN ==========
    $(document).ready(function() {
      // Inicializar el combobox de estudiantes
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

    var url;
    function newUser() {
      $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo Usuario');
      $('#fm').form('clear');
      url = 'Models/save_user.php';
    }

    function editUser() {
      var row = $('#dg').datagrid('getSelected');
      if (row) {
        $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar Usuario');
        $('#fm').form('load', row);
        url = 'Models/update_user.php?cedulaVieja=' + row.cedula;
      }
    }

    function saveUser() {
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
            // Ir a la primera página y recargar
            $('#dg').datagrid('load', {});
            $('#dg').datagrid('getPager').pagination({pageNumber: 1});
            // Actualizar combo de estudiantes si está cargado
            $('#comboEstudiantes').combobox('reload');
          }
        }
      });
    }

    function destroyUser() {
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
    var urlCurso;
    function newCurso() {
      $('#dlgCurso').dialog('open').dialog('center').dialog('setTitle', 'Nuevo Curso');
      $('#fmCurso').form('clear');
      $('#cursoId').val('');
      urlCurso = 'Models/save_curso.php';
    }

    function editCurso() {
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

    // ========== FUNCIONES PARA INSCRIPCIONES ==========
    var estudianteSeleccionadoId = null;

    function cargarCursosEstudiante(estudianteId) {
      estudianteSeleccionadoId = estudianteId;
      $('#inscripcionesContainer').show();

      // Mostrar loading
      $('#cursosDisponibles').html('<p style="text-align:center;padding:20px;">Cargando cursos...</p>');
      $('#cursosInscritos').html('<p style="text-align:center;padding:20px;">Cargando cursos...</p>');

      // Cargar cursos inscritos y disponibles
      $.post('Models/get_inscripciones.php', { estudiante_id: estudianteId }, function(data) {
        if(data && data.success) {
          mostrarCursosDisponibles(data.disponibles || []);
          mostrarCursosInscritos(data.inscritos || []);
        } else {
          $.messager.alert('Error', 'No se pudieron cargar los cursos: ' + (data.errorMsg || 'Error desconocido'));
          $('#cursosDisponibles').html('<p style="color:red;text-align:center;padding:20px;">Error al cargar cursos</p>');
          $('#cursosInscritos').html('<p style="color:red;text-align:center;padding:20px;">Error al cargar cursos</p>');
        }
      }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
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
          html += '<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="inscribirCurso(' + curso.id + ')">Inscribir</a>';
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
          html += '<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="desinscribirCurso(' + curso.id + ')">Desinscribir</a>';
          html += '</div>';
        });
      }
      $('#cursosInscritos').html(html);
      $.parser.parse($('#cursosInscritos'));
    }

    function inscribirCurso(cursoId) {
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
          $('#dgCursos').datagrid('reload'); // Actualizar contador de estudiantes
        } else {
          $.messager.show({ title: 'Error', msg: data.errorMsg || 'No se pudo inscribir.' });
        }
      }, 'json');
    }

    function desinscribirCurso(cursoId) {
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
              $('#dgCursos').datagrid('reload'); // Actualizar contador de estudiantes
            } else {
              $.messager.show({ title: 'Error', msg: data.errorMsg || 'No se pudo desinscribir.' });
            }
          }, 'json');
        }
      });
    }
  </script>

  <footer>
    <p>© 2025 Universidad Técnica de Ambato · FISEI</p>
  </footer>

</body>
</html>
