<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
//
//
$("#contabilidad_tipo_comprobante_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/tipo_comprobante/db/grid_tipo_comprobante.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipos de Comprobantes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq= $("#contabilidad_tipo_comprobante_nombre_consulta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/tipo_comprobante/db/sql_grid_tipo_comprobante.php?busq="+busq,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#contabilidad_tipo_comprobante_nombre_consulta").keypress(
					function(key)
					{
						if(key.keyCode==27) this.close();					
						dosearch();													
					}
				);
				$("#contabilidad_tipo_comprobante_cod").keypress(
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
					var busq= $("#contabilidad_tipo_comprobante_nombre_consulta").val();
					var cod= $("#contabilidad_tipo_comprobante_cod").val();
url="modulos/contabilidad/tipo_comprobante/db/sql_grid_tipo_comprobante.php?busq="+busq+"&cod="+cod;
//alert(url);
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/tipo_comprobante/db/sql_grid_tipo_comprobante.php?busq="+busq+"&cod="+cod,page:1}).trigger("reloadGrid");					
				}
			}
		}
	);
		
	function crear_grid()
	{		
		jQuery("#list_grid_"+nd).jqGrid
		({
			width:500,
			height:300,
			recordtext:"Registro(s)",
			loadtext: "Recuperando Información del Servidor",		
			url:'modulos/contabilidad/tipo_comprobante/db/sql_grid_tipo_comprobante.php?nd='+nd,
			datatype: "json",
			colNames:['','Codigo','Nombre','Comentario','nuemro_comprobante','numero_comprobante_integracion'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'codigo_tipo_comprobante',index:'codigo_tipo_comprobante', width:50,sortable:false,resizable:false},
				{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
				{name:'comentario',index:'comentario', width:200,sortable:false,resizable:false,hidden:true},
				{name:'n_comprobante',index:'n_comprobante', width:200,sortable:false,resizable:false,hidden:true},
				{name:'n_comprobante_int',index:'n_comprobante_int', width:200,sortable:false,resizable:false,hidden:true}


			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#contabilidad_tipo_comprobante_db_id').val(ret.id);
				$('#contabilidad_tipo_comprobante_db_codigo_comprobante').val(ret.codigo_tipo_comprobante);
				$('#contabilidad_tipo_comprobante_db_nombre').val(ret.nombre);
				$('#contabilidad_tipo_comprobante_db_comentario').val(ret.comentario);
				$('#contabilidad_tipo_comprobante_db_numero_comp').val(ret.n_comprobante);
				$('#contabilidad_tipo_comprobante_db_numero_comp_int').val(ret.n_comprobante_int);
				$('#contabilidad_tipo_comprobante_db_btn_eliminar').show()
				$('#contabilidad_tipo_comprobante_db_btn_actualizar').show()
				$('#contabilidad_tipo_comprobante_db_btn_guardar').hide()
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

$("#contabilidad_tipo_comprobante_db_btn_eliminar").click(function() {
	if($('#form_contabilidad_db_tipo_comprobante').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/tipo_comprobante/db/sql.eliminar.php",
			data:dataForm('form_contabilidad_db_tipo_comprobante'),
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

$("#contabilidad_tipo_comprobante_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_contabilidad_db_tipo_comprobante').jVal())
	{
		$.ajax (
		{
			url: "modulos/contabilidad/tipo_comprobante/db/sql.actualizar.php",
			data:dataForm('form_contabilidad_db_tipo_comprobante'),
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

$("#contabilidad_tipo_comprobante_db_btn_guardar").click(function() {
	if($('#form_contabilidad_db_tipo_comprobante').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/tipo_comprobante/db/sql.registrar.php",
			data:dataForm('form_contabilidad_db_tipo_comprobante'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_contabilidad_db_tipo_comprobante');
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
	clearForm('form_contabilidad_db_tipo_comprobante');
	$('#contabilidad_tipo_comprobante_db_btn_eliminar').hide()
	$('#contabilidad_tipo_comprobante_db_btn_actualizar').hide()
	$('#contabilidad_tipo_comprobante_db_btn_guardar').show()
}
function consulta_automatica_tipo_comp()
{
	$.ajax({
			url:"modulos/contabilidad/tipo_comprobante/db/sql_grid_tipo_cod.php",
            data:dataForm('form_contabilidad_db_tipo_comprobante'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
					recordset = recordset.split("*");
					getObj('contabilidad_tipo_comprobante_db_id').value=recordset[0];
					getObj('contabilidad_tipo_comprobante_db_nombre').value=recordset[2];
					getObj('contabilidad_tipo_comprobante_db_comentario').value=recordset[3];
					$('#contabilidad_tipo_comprobante_db_numero_comp').val(recordset[4]);
					$('#contabilidad_tipo_comprobante_db_numero_comp_int').val(recordset[5]);

					$('#contabilidad_tipo_comprobante_db_btn_eliminar').show();
					$('#contabilidad_tipo_comprobante_db_btn_actualizar').show()
					$('#contabilidad_tipo_comprobante_db_btn_guardar').hide()
				}
				else
				if(recordset=='vacio')
				{
					getObj('contabilidad_tipo_comprobante_db_id').value="";
					getObj('contabilidad_tipo_comprobante_db_nombre').value="";
					getObj('contabilidad_tipo_comprobante_db_comentario').value="";
				}
				
			 }
		});	 

}$("#contabilidad_tipo_comprobante_db_btn_cancelar").click(function() {
	limpiar();
});


</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#contabilidad_tipo_comprobante_db_nombre').alpha({allow:' áéíóúÄÉÍÓÚ. '});
$('#contabilidad_tipo_comprobante_db_codigo_comprobante').numeric({});

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
	<img id="contabilidad_tipo_comprobante_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="contabilidad_tipo_comprobante_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="contabilidad_tipo_comprobante_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="contabilidad_tipo_comprobante_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
	<img id="contabilidad_tipo_comprobante_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
<form method="post" id="form_contabilidad_db_tipo_comprobante" name="form_contabilidad_db_tipo_comprobante">
	<input   type="hidden" name="contabilidad_tipo_comprobante_db_id"  id="contabilidad_tipo_comprobante_db_id" />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Tipo Comprobante</th>
	</tr>
	<tr>
		<th>Codigo:</th>
		 <td>
            <input type="text" name="contabilidad_tipo_comprobante_db_codigo_comprobante" id="contabilidad_tipo_comprobante_db_codigo_comprobante"  size='6' maxlength="4" onblur="consulta_automatica_tipo_comp()" onchange="consulta_automatica_tipo_comp()"
            message="Introduzca la cuenta contable" 
                jval="{valid:/^[0-9]{1,4}$/, message:'Codigo Invalido', styleType:'cover'}"
                jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
	<tr>
		<th>Nombre:		</th>	
	    <td>	
            <input name="contabilidad_tipo_comprobante_db_nombre" type="text" id="contabilidad_tipo_comprobante_db_nombre"   value="" size="40" maxlength="40" message="Introduzca un Nombre del Tipo Comprobante. Ejem: 'Activos Reales' " 
                            jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.0-9]{1,40}$/,message:'Nombre Invalido', styleType:'cover'}"
                            jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	   </td>
   </tr>  
   <tr>
   		<th>
				N&uacute;mero de Comprobante:
		</th>
		<td>
				<input type="text" name="contabilidad_tipo_comprobante_db_numero_comp" id="contabilidad_tipo_comprobante_db_numero_comp"  /> 
				
		</td>
   </tr>
    <tr>
   		<th>
				N&uacute;mero de Comprobante integraci&oacute;n:
		</th>
		<td>
				<input type="text" name="contabilidad_tipo_comprobante_db_numero_comp_int" id="contabilidad_tipo_comprobante_db_numero_comp_int"  /> 
				
		</td>
   </tr>    
	<tr>
		<th>Comentarios:</th>
		<td>
        	<textarea  name="contabilidad_tipo_comprobante_db_comentario" cols="60" id="contabilidad_tipo_comprobante_db_comentario" message="Introduzca una Observación. Ejem:'Esta comprobante es ...' " style="width:422px"></textarea>		
       	</td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
	</table>	
</form>