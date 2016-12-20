<? if (!$_SESSION) session_start();
?>
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
?>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script>
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
var dialog;
//----------------------Actualizar--------------------------------
$("#escala_sueldo_db_btn_actualizar").click(function() {
	if ($('#form_db_escala_sueldo').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/escala_sueldo/db/sql.actualizar_escala_sueldo.php",
			data:dataForm('form_db_escala_sueldo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('escala_sueldo_db_btn_actualizar').style.display='';
					getObj('escala_sueldo_db_btn_guardar').style.display='none';
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Error fecha"){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La fecha del valor del impuesto \n tiene que ser mayor que la fecha actual </p></div>",true,true);
					}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});
// ******************************************************************************

$("#escala_sueldo_db_btn_cancelar").click(function() {
limpiar();
setBarraEstado("");
});
function limpiar(){
	var i;
	for(i=1;i<57;i++){
		var sueldo= "sueldo"+i;
		getObj(sueldo).value="0,00";
	}
	getObj('escala_sueldo_db_btn_actualizar').style.display='';
	getObj('escala_sueldo_db_btn_guardar').style.display='none';
}
//Validacion de los campos
$('#escala_sueldo_db_monto').numeric({allow:' ,.'});
$("input, select, textarea").bind("focus", function(){
	/////////////////////////////
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
//
</script>
<div id="botonera">
	<img id="escala_sueldo_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img style="display:none" id="escala_sueldo_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img id="escala_sueldo_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img style="display:none" id="escala_sueldo_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_escala_sueldo" id="form_db_escala_sueldo">
<input type="hidden" name="escala_sueldo_db_id_escala_sueldo" id="escala_sueldo_db_id_escala_sueldo"/>
<table width="521" class="cuerpo_formulario">
  <tr>
			<th class="titulo_frame">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Escala de Sueldos</th>
	</tr>
        <tr>
			<th>
            <table width="100%" class="cuerpo_form" border="0">
			  <tr>
			    <th width="22%" style="border-left:none"><div align="center">Sueldos/Niveles</div></th>
			    <th width="9%"><div align="center">
			      <p>MIN	I		    </p>
			    </div></th>
			    <th width="10%"><div align="center">II</div></th>
			    <th width="8%"><div align="center">III</div></th>
			    <th width="12%"><div align="center">PROM IV</div></th>
			    <th width="6%"><div align="center">V</div></th>
			    <th width="8%"><div align="center">VI</div></th>
			    <th width="25%"><div align="center">VII</div></th>
		      </tr>
              <tr>
			  <th style="border-left:none"><div align="center">1</div></th>
			  <?php
			  $c=0;
			  $j=2;
			   	$sql="	SELECT 
							monto,
							id_escala_sueldo
						FROM 
							escala_sueldos 
						ORDER BY id_escala_sueldo
						";
				$rs_monto =& $conn->Execute($sql);
				while (!$rs_monto->EOF){
					$row=$rs_monto->fields("monto");
					$punto= strpos($row,".");
					if($punto==0){
						$row=$row.".00";
					}
					if($punto!=0){
						$punto=$punto+1;
						$tam=strlen($row);
						$tam=$tam-$punto;
						if($tam==2){
							$row=$rs_monto->fields("monto");
						}
						if($tam<2){
							$row=$row."0";
						}
					}
					$id=$rs_monto->fields("id_escala_sueldo");
					$c++;
					
			  ?>
			    <td>
			      <div align="center">
			        <input onClick="alert(this.value)" name="sueldo<?php echo $c; ?>" type="text" id="sueldo<?php echo $c; ?>" size="4" alt="signed-decimal" style="text-align:right" value="<?php echo $row; ?>"/>
                    <input name="id_sueldo<?php echo $c; ?>" type="hidden" value="<?php echo $id; ?>"/>
		          </div>
			    </td>
                <?php 
					if ($c==7){
					echo "</tr>";
					echo "<th style='border-left:none'>
								<div align='center'>2</div></th>";
					}
					if ($c==14){
					echo "</tr>";
					echo "<th style='border-left:none'>
								<div align='center'>3</div></th>";
					}
					if ($c==21){
					echo "</tr>";
					echo "<th style='border-left:none'>
								<div align='center'>4</div></th>";
					}
					if ($c==28){
					echo "</tr>";
					echo "<th style='border-left:none'>
								<div align='center'>5</div></th>";
					}
					if ($c==35){
					echo "</tr>";
					echo "<th style='border-left:none'>
								<div align='center'>6</div></th>";
					}
					if ($c==42){
					echo "</tr>";
					echo "<th style='border-left:none'>
								<div align='center'>7</div></th>";
					}
					if ($c==49){
					echo "</tr>";
					echo "<th style='border-left:none'>
								<div align='center'>8</div></th>";
					}
				?>
                <?php
					$rs_monto->MoveNext();
					}
				?>
		      </tr>
		    </table></th>
	    </tr>
		<tr>
			<td class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>