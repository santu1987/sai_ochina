<?php
session_start();
$anio_ant= date("Y-m-d H:i:s");
$anio_ant=$anio_ant-1;
?>
<script type="text/javascript" language="JavaScript"> 
$("#contabilidad_btn_cerrar_anual").click(function() {
		setBarraEstado(mensaje[esperando_respuesta]);
	
	//alert("entro");
	$.ajax (
		{
			url:'modulos/contabilidad/parametros_contabilidad/db/sql_cierre_anual.php',
			data:dataForm('form_contabilidad_cierre_anual'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
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