<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<title>Prueba Pestañas</title>
<script src="jquery-1.1.3.1.pack.js" type="text/javascript"></script>
<script src="jquery.history_remote.pack.js" type="text/javascript"></script>
<script src="jquery.tabs.pack.js" type="text/javascript"></script>
<link rel="stylesheet" href="jquery.tabs.css" type="text/css" media="print, projection, screen">
<link rel="stylesheet" href="formularios.css" type="text/css">
</head>
<script type="text/javascript">
$(function() {
    $('#pestana').tabs();
 });
</script>
<body>
<div align="right"><img src="floppy.png" width="32" height="32" border="0" style="cursor:pointer" onClick="document.form1.submit();"></div>
<form action="prueba.php" method="post" name="form1" target="_self">
<div id="pestana">
<div>
  <ul class="tabs-nav">
    <li><a href="#pestana1"><span>Datos Basicos</span></a></li>
    <li><a href="#pestana2"><span>Dirección</span></a></li>
    <li><a href="#pestana3"><span>Otro</span></a></li>
    <li><a href="#pestana4"><span>Juegos</span></a></li>
  </ul>
</div>
<div>
  <div id="pestana1" class="tabs-container">
    <table width="200" border="0" class="cuerpo_formulario">
      <tr>
        <td>Nombre:</td>
        <td><input type="text" name="nombre" id="nombre"></td>
      </tr>
      <tr>
        <td>Apellido:</td>
        <td><input type="text" name="apellido" id="apellido"></td>
      </tr>
    </table>
  </div>
  <div id="pestana2" class="tabs-container">
    <table width="200" border="0" class="cuerpo_formulario">
      <tr>
        <td>Dirección: </td>
        <td><input type="text" name="direccion" id="direccion"></td>
      </tr>
      <tr>
        <td>Otro: </td>
        <td><input type="text" name="otro" id="otro"></td>
      </tr>
    </table>
  </div>
  <div id="pestana3" class="tabs-container">
    <table width="269" border="0" class="cuerpo_formulario">
      <tr>
        <td>Trabajo: </td>
        <td><input type="text" name="trabajo" id="trabajo"></td>
      </tr>
      <tr>
        <td>Sexo: </td>
        <td><label>
          <input type="radio" name="sexo" value="Masculino" id="sexo_0">
          Masculino</label>
          <label>
            <input type="radio" name="sexo" value="Femenino" id="sexo_1">
            Femenino</label></td>
      </tr>
    </table></div>
  <div id="pestana4" class="tabs-container">
    <table width="200" border="0" class="cuerpo_formulario">
      <tr>
        <td>Juego: </td>
        <td><input type="text" name="juego" id="juego"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </div>
</div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
</form>
</body>
</html>