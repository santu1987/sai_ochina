<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
//
//
$("#contabilidad_numeracion_comprobante_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/numeracion_comprobante/db/grid_numeracion_comprobante.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipos de Comprobantes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq= $("#contabilidad_numeracion_comprobante_nombre_consulta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/numeracion_comprobante/db/sql_grid_numeracion_comprobante.php?busq="+busq,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#contabilidad_numeracion_comprobante_nombre_consulta").keypress(
					function(key)
					{
						if(key.keyCode==27) this.close();					
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
					var busq= $("#contabilidad_numeracion_comprobante_nombre_consulta").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/numeracion_comprobante/db/sql_grid_numeracion_comprobante.php?busq="+busq,page:1}).trigger("reloadGrid");
					url="modulos/contabilidad/numeracion_comprobante/db/sql_grid_numeracion_comprobante.php?busq="+busq;					
					//alert(url);
				}
			}
		}
	);
		
	function crear_grid()
	{		
		jQuery("#list_grid_"+nd).jqGrid
		({
			width:700,
			height:300,
			recordtext:"Registro(s)",
			loadtext: "Recuperando Información del Servidor",		
			url:'modulos/contabilidad/numeracion_comprobante/db/sql_grid_numeracion_comprobante.php?nd='+nd,
			datatype: "json",
			colNames:['Id','A&ntilde;o','N Comprobante','N Comprobante Int.','Comentario'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'ano',index:'ano', width:50,sortable:false,resizable:false},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false},
				{name:'numero_comprobante_integracion',index:'numero_comprobante_integracion', width:120,sortable:false,resizable:false},
				{name:'comentario',index:'comentario', width:200,sortable:false,resizable:false}
			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#contabilidad_numeracion_comprobante_db_id').val(ret.id);
				$('#contabilidad_numeracion_comprobante_db_ano').val(ret.ano);
				$('#contabilidad_numeracion_comprobante_db_numero_comprobante').val(ret.numero_comprobante);
				$('#contabilidad_numeracion_comprobante_db_numero_comprobante_integracion').val(ret.numero_comprobante_integracion);
				$('#contabilidad_numeracion_comprobante_db_comentario').val(ret.comentario);
				$('#contabilidad_numeracion_comprobante_db_btn_eliminar').show()
				$('#contabilidad_numeracion_comprobante_db_btn_actualizar').show()
				$('#contabilidad_numeracion_comprobante_db_btn_guardar').hide()
				dialog.hideAndUnload();
			},

			loadComplete:function (id){
				setBarraEstado("");
				dialog.center();
				dialog.show();
			}, 
			loadError:function(xhr,st,err){ 
				setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
			},										
			sortname: 'id_moneda',
			viewrecords: true,
			sortorder: "asc"					
		});							
	}
});

$("#contabilidad_numeracion_comprobante_db_btn_eliminar").click(function() {
	if($('#form_contabilidad_db_numeracion_comprobante').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/numeracion_comprobante/db/sql.eliminar.php",
			data:dataForm('form_contabilidad_db_numeracion_comprobante'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					limpiar();
				}
				else if (html=="ExisteRelacion")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else
				{
					setBarraEstado(html,true,true);					
				}			
			}
		});
	}
});

$("#contabilidad_numeracion_comprobante_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_contabilidad_db_numeracion_comprobante').jVal())
	{
		$.ajax (
		{
			url: "modulos/contabilidad/numeracion_comprobante/db/sql.actualizar.php",
			data:dataForm('form_contabilidad_db_numeracion_comprobante'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					limpiar();
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}				
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#contabilidad_numeracion_comprobante_db_btn_guardar").click(function() {
	if($('#form_contabilidad_db_numeracion_comprobante').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/numeracion_comprobante/db/sql.registrar.php",
			data:dataForm('form_contabilidad_db_numeracion_comprobante'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_contabilidad_db_numeracion_comprobante');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(html,true,true);					
				}			
			}
		});
	}
});


function limpiar()
{
	setBarraEstado("");
	clearForm('form_contabilidad_db_numeracion_comprobante');
	$('#contabilidad_numeracion_comprobante_db_btn_eliminar').hide()
	$('#contabilidad_numeracion_comprobante_db_btn_actualizar').hide()
	$('#contabilidad_numeracion_comprobante_db_btn_guardar').show()
}

$("#contabilidad_numeracion_comprobante_db_btn_cancelar").click(function() {
	limpiar();
});


</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#contabilidad_numeracion_comprobante_db_ano').numeric({});
$('#contabilidad_numeracion_comprobante_db_numero_comprobante').numeric({});
$('#contabilidad_numeracion_comprobante_db_numero_comprobante_integracion').numeric({});

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
	
</script>

<div id="botonera">
	<img id="contabilidad_numeracion_comprobante_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="contabilidad_numeracion_comprobante_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="contabilidad_numeracion_comprobante_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="contabilidad_numeracion_comprobante_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
	<img id="contabilidad_numeracion_comprobante_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
<form method="post" id="form_contabilidad_db_numeracion_comprobante" name="form_contabilidad_db_numeracion_comprobante">
	<input   type="hidden" name="contabilidad_numeracion_comprobante_db_id"  id="contabilidad_numeracion_comprobante_db_id" />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Numeraci&oacute;n Comprobante</th>
	</tr>
	<tr>
		<th>A&ntilde;o:</th>
		 <td>
            <input type="text" name="contabilidad_numeracion_comprobante_db_ano" id="contabilidad_numeracion_comprobante_db_ano"  size='6' maxlength="4"
            message="Introduzca el a&ntilde;o de la Numeraci&oacute;n. Ejem: 2009" 
                jval="{valid:/^[0-9]{1,4}$/, message:'A&ntilde;o Invalido', styleType:'cover'}"
                jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
	<tr>
		<th>Numeraci&oacute;n Comprobante:		</th>	
	    <td>	
            <input name="contabilidad_numeracion_comprobante_db_numero_comprobante" type="text" id="contabilidad_numeracion_comprobante_db_numero_comprobante"   value="" size="10" maxlength="6" 
            				message="Introduzca el Numero Inicial del Comprobante. Ejem: '999999' " 
                            jVal="{valid:/^[0-9]{1,6}$/,message:'Codigo Invalido', styleType:'cover'}"
                            jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	   </td>
   </tr>
	<tr>
		<th>Numeraci&oacute;n Comprobante Integraci&oacute;n:		</th>	
	    <td>	
            <input name="contabilidad_numeracion_comprobante_db_numero_comprobante_integracion" type="text" id="contabilidad_numeracion_comprobante_db_numero_comprobante_integracion"   value="" size="10" maxlength="6" 
            				message="Introduzca el Numero Inicial del Comprobante Integraci&oacute;n. Ejem: '999999' " 
                            jVal="{valid:/^[0-9]{1,6}$/,message:'Codigo Invalido', styleType:'cover'}"
                            jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	   </td>
   </tr>       
	<tr>
		<th>Comentarios:</th>
		<td>
        	<textarea  name="contabilidad_numeracion_comprobante_db_comentario" cols="60" id="contabilidad_numeracion_comprobante_db_comentario" message="Introduzca una Observación. Ejem:'Esta comprobante es ...' " style="width:422px"></textarea>		
       	</td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
	</table>	
</form>