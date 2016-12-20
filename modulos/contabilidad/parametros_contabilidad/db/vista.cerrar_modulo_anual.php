<?php
session_start();
$anio_ant= date("Y-m-d H:i:s");
$anio_ant=$anio_ant-1;
?>
<script type="text/javascript" language="JavaScript"> 
function cerrar_boxy(self)
{
			Boxy.get(self).hide();
}
$("#contabilidad_btn_cerrar_anual").click(function() {
		//setBarraEstado(mensaje[esperando_respuesta]);
	   //alert("entro");
	   
	/*Boxy.alert("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/ajax-loader2.gif'>REALIZANDO Proceso:porfavor espere</p></div>",null,null);*/
new Boxy("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/ajax-loader2.gif'>REALIZANDO Proceso:porfavor espere</p></div><div id='mensaje2'><p>.</p></div>",{title:"SAI-OCHINA",fixed:false});
	$.ajax (
		{
			url:'modulos/contabilidad/parametros_contabilidad/db/sql_cierre_anual.php',
			data:dataForm('form_contabilidad_cierre_anual'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
			cerrar_boxy(document.getElementById('mensaje'));
			cerrar_boxy(document.getElementById('mensaje2'));
			
				if (html=="Actualizado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />A&Ntilde;O CERRADO</p></div>",true,true);
				}
				else if (html=="NoActualizo")
				{//GIANNI
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACI&Oacute;N</p></div>",true,true);
			}
			else if (html=="no_ano")
				{//GIANNI
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACI&Oacute;N, HASTA LLEGAR AL FINAL DEL A&Ntilde;O</p></div>",true,true);
			}
			
			else
				{
					alert(html);
					setBarraEstado(html);
				}
				//Boxy.get(this).hide()
			}
		});
	
});

</script>
<div  id="botonera">
<img id="contabilidad_btn_cerrar_anual" src="imagenes/iconos/cerrar_orden_cxp.png" />
</div>
    <form name="form_contabilidad_cierre_anual" id="form_contabilidad_cierre_anual"  method="post">
<table class="cuerpo_formulario">
       <tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Cierre Anual </th>
	</tr>
        <tr>
        	<th style="border-top: 1px #BADBFC solid">A&ntilde;o</th>
			<td  style="border-top: 1px #BADBFC solid">
            	<select name="contabilidad_cierre_ano_anual">
                <option value="<?=$anio_ant;?>"><?=$anio_ant;?></option>
                	<?
							$anio_inicio=date("Y");
							$anio_fin=date("Y")+1;
							while($anio_inicio <= $anio_fin)
							{
							?>
							<option value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
							<?
								$anio_inicio++;
							}
					?>
                </select>
            </td>
        </tr>

     <tr>
        <td colspan="2" class="bottom_frame">&nbsp;</td>
     </tr>    
  </table>    
    </form>