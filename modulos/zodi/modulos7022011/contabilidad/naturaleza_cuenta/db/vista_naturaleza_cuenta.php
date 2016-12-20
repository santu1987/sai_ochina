<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
$("#contabilidad_naturaleza_cuenta_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/naturaleza_cuenta/db/grid_naturaleza_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipos de Comprobantes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq= $("#contabilidad_naturaleza_cuenta_nombre_consulta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/naturaleza_cuenta/db/sql_grid_naturaleza_cuenta.php?busq="+busq,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#contabilidad_naturaleza_cuenta_nombre_consulta").keypress(
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
					var busq= $("#contabilidad_naturaleza_cuenta_nombre_consulta").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/naturaleza_cuenta/db/sql_grid_naturaleza_cuenta.php?busq="+busq,page:1}).trigger("reloadGrid");					
				url="modulos/contabilidad/naturaleza_cuenta/db/sql_grid_naturaleza_cuenta.php?busq="+busq;
				alert(url);
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
			url:'modulos/contabilidad/naturaleza_cuenta/db/sql_grid_naturaleza_cuenta.php?nd='+nd,
			datatype: "json",
			colNames:['Id','C&oacute;digo','Descripci&oacute;n','Comentario'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
				{name:'descripcion',index:'descripcion', width:120,sortable:false,resizable:false},
				{name:'comentario',index:'comentario', width:200,sortable:false,resizable:false}
			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#contabilidad_naturaleza_cuenta_db_id').val(ret.id);
				$('#contabilidad_naturaleza_cuenta_db_codigo').val(ret.codigo);
				$('#contabilidad_naturaleza_cuenta_db_descripcion').val(ret.descripcion);
				$('#contabilidad_naturaleza_cuenta_db_comentario').val(ret.comentario);
				$('#contabilidad_naturaleza_cuenta_db_btn_eliminar').show()
				$('#contabilidad_naturaleza_cuenta_db_btn_actualizar').show()
				$('#contabilidad_naturaleza_cuenta_db_btn_guardar').hide()
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
			sortname: 'codigo',
			viewrecords: true,
			sortorder: "asc"					
		});							
	}
});

$("#contabilidad_naturaleza_cuenta_db_btn_eliminar").click(function() {
	if($('#form_contabilidad_db_naturaleza_cuenta').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/naturaleza_cuenta/db/sql.eliminar.php",
			data:dataForm('form_contabilidad_db_naturaleza_cuenta'),
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

$("#contabilidad_naturaleza_cuenta_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_contabilidad_db_naturaleza_cuenta').jVal())
	{
		$.ajax (
		{
			url: "modulos/contabilidad/naturaleza_cuenta/db/sql.actualizar.php",
			data:dataForm('form_contabilidad_db_naturaleza_cuenta'),
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

$("#contabilidad_naturaleza_cuenta_db_btn_guardar").click(function() {
	if($('#form_contabilidad_db_naturaleza_cuenta').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/naturaleza_cuenta/db/sql.registrar.php",
			data:dataForm('form_contabilidad_db_naturaleza_cuenta'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_contabilidad_db_naturaleza_cuenta');
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
	clearForm('form_contabilidad_db_naturaleza_cuenta');
	$('#contabilidad_naturaleza_cuenta_db_btn_eliminar').hide()
	$('#contabilidad_naturaleza_cuenta_db_btn_actualizar').hide()
	$('#contabilidad_naturaleza_cuenta_db_btn_guardar').show()
	$('#contabilidad_naturaleza_cuenta_db_btn_eliminar').hide()
}

$("#contabilidad_naturaleza_cuenta_db_btn_cancelar").click(function() {
	limpiar();
});
function consulta_automatica_naturaleza()
{
	$.ajax({
			url:"modulos/contabilidad/naturaleza_cuenta/db/sql_grid_naturaleza_cuenta_cod.php",
            data:dataForm('form_contabilidad_db_naturaleza_cuenta'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
			
					getObj('contabilidad_naturaleza_cuenta_db_id').value=recordset[0];
					getObj('contabilidad_naturaleza_cuenta_db_codigo').value=recordset[1];
					getObj('contabilidad_naturaleza_cuenta_db_descripcion').value=recordset[2];
					getObj('contabilidad_naturaleza_cuenta_db_comentario').value=recordset[3];
				
				}
				else
				{
					/*getObj('contabilidad_auxiliares_db_id_cuenta').value="";
					getObj('contabilidad_auxiliares_db_cuenta_contable').value="";
					getObj('contabilidad_auxiliares_db_nombre').value="";
					getObj('contabilidad_auxiliares_db_comentario').value="";*/
				}
				
			 }
		});	 	 
}


</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#contabilidad_naturaleza_cuenta_db_codigo').alpha({allow:''});
$('#contabilidad_naturaleza_cuenta_db_descripcion').alpha({allow:' áéíóúÄÉÍÓÚ'});

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
	<img id="contabilidad_naturaleza_cuenta_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="contabilidad_naturaleza_cuenta_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="contabilidad_naturaleza_cuenta_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="contabilidad_naturaleza_cuenta_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
	<img id="contabilidad_naturaleza_cuenta_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
<form method="post" id="form_contabilidad_db_naturaleza_cuenta" name="form_contabilidad_db_naturaleza_cuenta">
	<input   type="hidden" name="contabilidad_naturaleza_cuenta_db_id"  id="contabilidad_naturaleza_cuenta_db_id" />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Naturaleza Cuenta</th>
	</tr>
	<tr>
		<th>C&oacute;digo:</th>
		 <td>
            <input type="text" name="contabilidad_naturaleza_cuenta_db_codigo" id="contabilidad_naturaleza_cuenta_db_codigo"  size='6' maxlength="4" onblur="consulta_automatica_naturaleza()" onchange="consulta_automatica_naturaleza()"
            message="Introduzca C&oacute;digo. Ejem: A,B,C..." 
                jval="{valid:/^[a-zA-Z]{1,4}$/, message:'A&ntilde;o Invalido', styleType:'cover'}"
                jvalkey="{valid:/[a-zA-Z]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
	<tr>
		<th>Descripci&oacute;n:		</th>	
	    <td>	
            <input name="contabilidad_naturaleza_cuenta_db_descripcion" type="text" id="contabilidad_naturaleza_cuenta_db_descripcion"   value="" size="40" maxlength="40" 
            				message="Introduzca la Descripci&oacute;n de la Naturaleza. Ejem: 'Pasivo' " 
                            jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,40}$/,message:'Codigo Invalido', styleType:'cover'}"
                            jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	   </td>
   </tr>       
	<tr>
		<th>Comentarios:</th>
		<td>
        	<textarea  name="contabilidad_naturaleza_cuenta_db_comentario" cols="60" id="contabilidad_naturaleza_cuenta_db_comentario" message="Introduzca una Observaci&oacute;n. Ejem:'Esta comprobante es ...' " style="width:422px"></textarea>		
       	</td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
	</table>	
</form>