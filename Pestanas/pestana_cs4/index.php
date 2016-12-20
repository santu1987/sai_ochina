<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="paginapesta.php" method="post" name="form1" target="_self" id="form1">
  <div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab" tabindex="0" onclick="document.form1.icono.style.display='none'">Ficha 1</li>
      <li class="TabbedPanelsTab" tabindex="0" id="pesta" onclick="mostrar_boton();">Ficha 2 </li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
        <p>nombre:
          <input type="text" name="nombre" id="nombre" />
        </p>
        <p>apellido:
          <input type="text" name="apellido" id="apellido" />
        </p>
      </div>
      <div class="TabbedPanelsContent">
        <div align="center">
          <table width="200" border="0">
            <tr>
              <td width="55">Pais:</td>
              <td width="99"><select name="pais" id="pais">
                <option value="Argentina">Argentina</option>
                <option value="Brasil">Brasil</option>
                <option value="Colombia">Colombia</option>
                <option value="Venezuela">Venezuela</option>
              </select></td>
              <td width="53">Estado:</td>
              <td width="440"><select name="estado" id="estado">
                <option value="Amazonas">Amazonas</option>
                <option value="Dist. capital">Distrito Capital</option>
                <option value="Vargas">Vargas</option>
                <option value="Carabobo">Carabobo</option>
              </select></td>
            </tr>
            <tr>
              <td>Direccion</td>
              <td colspan="3"><input name="direccion" type="text" id="direccion" size="100" /></td>
            </tr>
            <tr>
              <td>Residencia:</td>
              <td colspan="3"><table width="200">
                <tr>
                  <td><label>
                    <input type="radio" name="Radio" value="Si" id="RadioGroup1_0" />
                    Si</label>                    <label>
                       &nbsp;
                       <input type="radio" name="Radio" value="No" id="RadioGroup1_1" />
                    No</label></td>
                </tr>
              </table></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div align="center"><img name="icono" id="icono" src="AddressBook.png" width="42" height="42" border="0" onclick="document.form1.submit();" style="cursor:pointer; display:none"/>
  </div>
</form>
<p>&nbsp;</p>
<p><br />
</p>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
function mostrar_boton(){
	document.form1.icono.style.display='';
}
</script>
</body>
</html>