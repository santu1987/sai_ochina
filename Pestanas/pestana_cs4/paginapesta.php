<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
<!--
body,td,th {
	color: #FFF;
	font-weight: bold;
}
-->
</style></head>

<body>
<table width="200" border="0">
  <tr>
    <td width="88" bgcolor="#000066">Nombre:</td>
    <td width="102" bgcolor="#3333FF"><?php echo $_POST['nombre'];?></td>
  </tr>
  <tr>
    <td bgcolor="#000066">Apellido:</td>
    <td bgcolor="#3333FF"><?php echo $_POST['apellido'];?></td>
  </tr>
  <tr>
    <td bgcolor="#000066">Direccion:</td>
    <td bgcolor="#3333FF"><?php echo $_POST['direccion'];?></td>
  </tr>
  <tr>
    <td bgcolor="#000066">Residenciado:</td>
    <td bgcolor="#3333FF"><?php echo $_POST['Radio'];?></td>
  </tr>
  <tr>
    <td bgcolor="#000066">Pais:</td>
    <td bgcolor="#3333FF"><?php echo $_POST['pais'];?></td>
  </tr>
  <tr>
    <td bgcolor="#000066">Estado:</td>
    <td bgcolor="#3333FF"><?php echo $_POST['estado'];?></td>
  </tr>
</table>
</body>
</html>