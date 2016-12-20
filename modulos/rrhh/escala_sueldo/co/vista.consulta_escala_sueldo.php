<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql="SELECT *FROM escala_sueldos ORDER BY id_escala_sueldo";
$row=& $conn->Execute($sql);
$fila=0;
$col=0;
while (!$row->EOF){
	$rows[$fila][$col]=$row->fields("monto");
	$col++;
	if($col==7){
		$fila++;
		$col=0;
	}
	$row->MoveNext();
}  
?>
<table width="565" class="cuerpo_formulario" style="width:300px;">
	<tr>
		<th class="titulo_frame" colspan="11"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Consulta Escala Sueldos</th>
	</tr>
	<tr>	
		<th width="112" bgcolor="#BADBFC"><div align="left">
		  <p>SUELDO</p>
		  <p align="center">NIVELES  </p>
		</div></th>
		<th width="76" bgcolor="#BADBFC"><p align="center">MIN</p>
      <p align="center">I</p></th>
		<th width="55" bgcolor="#BADBFC"><div align="center">II</div></th>
		<th width="4" bgcolor="#BADBFC"><div align="center">III</div></th>
		<th width="4" bgcolor="#BADBFC"><p align="center">PROM</p>
	    <p align="center">IV</p></th>
		<th width="4" bgcolor="#BADBFC"><div align="center">V</div></th>
		<th width="4" bgcolor="#BADBFC"><div align="center">VI</div></th>
		<th width="5" bgcolor="#BADBFC"><div align="center">VII</div></th>
	</tr>
	<tr>
	  <th colspan="8" style="border-top:1px #6CF ridge; border-bottom:1px #6CF ridge"> 
      <div align="center">BACHILLERES</div></th>
  </tr>
	<tr>
	  <th bgcolor="#BADBFC"><div align="center">1</div></th>
	  <th><div align="center"><?php echo substr($rows[0][0],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[0][1],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[0][2],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[0][3],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[0][4],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[0][5],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[0][6],1,10); ?></div></th>
  </tr>
	<tr>
	  <th bgcolor="#BADBFC"><div align="center">2</div></th>
	  <th><div align="center"><?php echo substr($rows[1][0],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[1][1],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[1][2],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[1][3],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[1][4],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[1][5],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[1][6],1,10); ?></div></th>
  </tr>
	<tr>
	  <th bgcolor="#BADBFC"><div align="center">3</div></th>
	  <th><div align="center"><?php echo substr($rows[2][0],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[2][1],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[2][2],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[2][3],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[2][4],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[2][5],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[2][6],1,10); ?></div></th>
  </tr>
	<tr>
	  <th colspan="8" style="border-top:1px #6CF ridge; border-bottom:1px #6CF ridge">
      <div align="center">T&Eacute;CNICOS SUPERIORES UNIVERSITARIOS</div></th>
  </tr>
	<tr>
	  <th bgcolor="#BADBFC"><div align="center">4</div></th>
	  <th><div align="center"><?php echo substr($rows[3][0],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[3][1],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[3][2],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[3][3],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[3][4],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[3][5],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[3][6],1,10); ?></div></th>
  </tr>
	<tr>
	  <th bgcolor="#BADBFC"><div align="center">5</div></th>
	  <th><div align="center"><?php echo substr($rows[4][0],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[4][1],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[4][2],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[4][3],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[4][4],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[4][5],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[4][6],1,10); ?></div></th>
  </tr>
	<tr>
	  <th colspan="8" style="border-top:1px #6CF ridge; border-bottom:1px #6CF ridge">
      <div align="center">PROFESIONALES UNIVERSITARIOS</div></th>
  </tr>
	<tr>
	  <th bgcolor="#BADBFC">
      <div align="center">6</div></th>
	  <th><div align="center"><?php echo substr($rows[5][0],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[5][1],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[5][2],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[5][3],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[5][4],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[5][5],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[5][6],1,10); ?></div></th>
  </tr>
	<tr>
	  <th bgcolor="#BADBFC"><div align="center">7</div></th>
	  <th><div align="center"><?php echo substr($rows[6][0],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[6][1],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[6][2],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[6][3],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[6][4],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[6][5],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[6][6],1,10); ?></div></th>
  </tr>
	<tr>
	  <th bgcolor="#BADBFC"><div align="center">8</div></th>
	  <th><div align="center"><?php echo substr($rows[7][0],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[7][1],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[7][2],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[7][3],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[7][4],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[7][5],1,10); ?></div></th>
	  <th><div align="center"><?php echo substr($rows[7][6],1,10); ?></div></th>
  </tr>
	<tr>
	  <td colspan="8" class="bottom_frame">&nbsp;</td>
  </tr>
</table>
