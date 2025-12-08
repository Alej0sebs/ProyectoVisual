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
</head>

<body>
  <header>
    <img src="imagenes/descarga.png" height="auto" width="100%">
  </header>

  <?php include 'nav.php'; ?>

  <?php if (!$logueado): ?>

    <div class="page-wrapper">
      <div class="login-wrapper">
        <div class="login-card">
          <h2>Iniciar sesión</h2>
          <p>Ingrese sus credenciales para acceder a <?php echo isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'Servicios'; ?>.</p>

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
        <div class="page-header-top">
          <h1>Sistema de Gestión Académica</h1>
          <div class="logout-btn">
            <a href="Models/logout.php" class="easyui-linkbutton" iconCls="icon-cancel">
              Cerrar sesión
            </a>
          </div>
        </div>
        <p>
          Bienvenido <?php echo htmlspecialchars($_SESSION['usuario']); ?> · Rol:
          <strong><?php echo htmlspecialchars($rol); ?></strong>
        </p>
      </div>

      <div class="panel">
        <div class="easyui-tabs" style="height:550px" data-options="tabPosition:'top',plain:true,narrow:true">
          
          <!-- PESTAÑA 1: ESTUDIANTES -->
          <div title="📚 Estudiantes" style="padding:15px">
            <div style="margin-bottom:15px;padding:12px;background:#fafafa;border-radius:8px;border:1px solid #e0e0e0;">
              <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
                <div style="flex:1;min-width:250px;">
                  <label style="display:block;margin-bottom:4px;font-size:0.85rem;font-weight:600;color:#666;">Búsqueda rápida</label>
                  <div style="display:flex;gap:5px;">
                    <input id="txtBuscarCedula" class="easyui-textbox" prompt="Buscar por cédula..." style="width:180px;">
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="buscarCedula()">Buscar</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="recargarTabla()">Limpiar</a>
                  </div>
                </div>

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

            <?php if ($rol === 'secretaria'): ?>
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

            <div style="margin-bottom:15px;padding:12px;background:#fafafa;border-radius:8px;border:1px solid #e0e0e0;">
              <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
                <div style="flex:1;min-width:250px;">
                  <label style="display:block;margin-bottom:4px;font-size:0.85rem;font-weight:600;color:#666;">🔍 Búsqueda de cursos</label>
                  <input id="txtBuscarCurso" class="easyui-textbox" prompt="Escribe para filtrar cursos..." style="width:100%;max-width:400px;">
                </div>
                <div style="flex:1;min-width:250px;">
                  <label style="display:block;margin-bottom:4px;font-size:0.85rem;font-weight:600;color:#666;">📄 Reportes</label>
                  <div style="display:flex;gap:5px;">
                    <select id="comboReportesCursos" class="easyui-combobox" panelHeight="auto" style="width:200px;">
                      <option value="">Seleccione reporte...</option>
                      <option value="reporteEstudiantesPorCurso">Estudiantes por curso</option>
                    </select>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" onclick="verReporteCurso()">Ver reporte</a>
                  </div>
                </div>
              </div>
            </div>

            <table id="dgCursos" title="Listado de cursos disponibles" class="easyui-datagrid" style="width:100%;height:330px"
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

            <?php if ($rol === 'secretaria'): ?>
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
                <div style="flex:1;min-width:300px;">
                  <h4 style="color:var(--rojo-uta);margin:0 0 10px 0;font-size:1rem;font-weight:600;">📚 Cursos Disponibles</h4>
                  <div id="cursosDisponibles" style="max-height:280px;overflow-y:auto;border:1px solid #ddd;border-radius:8px;padding:10px;background:#fff;">
                  </div>
                </div>

                <div style="flex:1;min-width:300px;">
                  <h4 style="color:var(--rojo-uta);margin:0 0 10px 0;font-size:1rem;font-weight:600;">✅ Cursos Inscritos</h4>
                  <div id="cursosInscritos" style="max-height:280px;overflow-y:auto;border:1px solid #ddd;border-radius:8px;padding:10px;background:#fff;">
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

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
    const ROL = '<?php echo htmlspecialchars($rol ?? "", ENT_QUOTES, "UTF-8"); ?>';
  </script>
  <script type="text/javascript" src="js/servicios.js"></script>

  <footer>
    <p>© 2025 Universidad Técnica de Ambato · FISEI</p>
  </footer>

</body>
</html>
