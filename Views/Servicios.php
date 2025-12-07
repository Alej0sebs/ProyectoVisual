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
        <h1>Registro de Estudiantes</h1>
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
        <div class="panel-header">
          <div class="panel-header-left">
            <span class="panel-header-title">Búsqueda rápida</span>
            <input id="txtBuscarCedula" class="easyui-textbox" prompt="Buscar por cédula..." style="width:220px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="buscarCedula()">Buscar</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="recargarTabla()">Limpiar</a>
          </div>

          <div class="panel-header-right">
            <span class="panel-header-title">Reportes</span>
            <select id="comboReportes" class="easyui-combobox" style="width:260px">
              <option value="">Seleccione un reporte...</option>
              <option value="reporteFPDF">Reporte general PDF (FPDF)</option>
              <option value="reporteCedulaFPDF">Reporte por cédula PDF (FPDF)</option>
            </select>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" onclick="verReporte()">Ver reporte</a>
          </div>
        </div>

        <table id="dg" title="Listado de estudiantes" class="easyui-datagrid" style="width:100%;height:320px"
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
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="false"
              onclick="editUser()">Editar</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="false"
              onclick="destroyUser()">Eliminar</a>
          </div>
        <?php endif; ?>
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

  <?php endif; ?>

  <script type="text/javascript">
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
            $('#dlg').dialog('close');
            $('#dg').datagrid('reload');
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
  </script>

  <footer>
    <p>© 2025 Universidad Técnica de Ambato · FISEI</p>
  </footer>

</body>
</html>
