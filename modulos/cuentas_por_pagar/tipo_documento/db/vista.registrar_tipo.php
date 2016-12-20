<?php
session_start();

?>
<script type='text/javascript'>
var dialog;

$("#cuentas_por_pagar_db_tipo_doc_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	var fecha_actual=new Date();
	if($('#').jVal())
	{
		$.ajax (
		{
			url:"modulos/cuentas_por_pagar/tipo_documento/db/sql.actualizar.php",
			data:dataForm('form_cuentas_por_pagar_db_tipo_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{ /*alert(html);
			setBarraEstado(html);*/
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					//	getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
						getObj('cuentas_por_pagar_db_tipo_doc_btn_actualizar').style.display='none';
						getObj('cuentas_por_pagar_db_tipo_doc_btn_guardar').style.display='';
						getObj('cuentas_por_pagar_db_tipo_doc_btn_consultar').style.display='';
						clearForm('form_cuentas_por_pagar_db_tipo_documento');
						}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado("");
					//	getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
						//getObj('tesoreria_firma_voucher_db_btn_actualizar').style.display='none';
						//getObj('tesoreria_firma_voucher_db_btn_guardar').style.display='';
						//getObj('tesoreria_firma_voucher_db_btn_consultar').style.display='';
						//clearForm('form_tesoreria_db_firmas_voucher');
						//getObj('tesoreria_frima_voucher_db_ayo').value=fecha_actual.getFullYear();
						
					//	getObj('tesoreria_frima_voucher_db_mes').value='01';	
					//	getObj('tesoreria_firmas_voucher_db_estatus').value='1';			
				}
				
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#cuentas_por_pagar_db_tipo_doc_btn_guardar").click(function() {
	var fecha_actual=new Date();
	if($('#form_cuentas_por_pagar_db_tipo_documento').jVal())
	{

		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/cuentas_por_pagar/tipo_documento/db/sql.registrar.php",
			data:dataForm('form_cuentas_por_pagar_db_tipo_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_cuentas_por_pagar_db_tipo_documento');
					
					
				}
				else if (html=="Noregistro")
				{
						setBarraEstado(mensaje[registro_existe],true,true);
					//	clearForm('form_tesoreria_db_firmas_voucher');
					//	getObj('tesoreria_frima_voucher_db_ayo').value=fecha_actual.getFullYear();
					//	getObj('tesoreria_frima_voucher_db_mes').value='01';
					//	getObj('tesoreria_firmas_voucher_db_estatus').value='1';						
							}
					else
					{
					alert(html);
					setBarraEstado(html);
					}
			
			}
		});
	}
});

$("#cuentas_por_pagar_db_tipo_doc_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/cuentas_por_pagar/tipo_documento/db/grid_tipo_doc.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Banco',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/cuentas_por_pagar/tipo_documento/db/sql_grid_tipo_documento.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Nombre','Siglas','Comentarios'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:70,sortable:false,resizable:false},
									{name:'siglas',index:'siglas', width:50,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true }
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_por_pagar_db_id_tipo').value=ret.id;
									getObj('cuentas_por_cobrar_db_tipo_documento').value=ret.nombre;
									getObj('cuentas_por_cobrar_db_siglas_documento').value=ret.siglas;
									getObj('cuentas_por_cobrar_db_tipo_documento_comentarios').value=ret.comentarios;
									getObj('cuentas_por_pagar_db_tipo_doc_btn_cancelar').style.display='';
									getObj('cuentas_por_pagar_db_tipo_doc_btn_actualizar').style.display='';
									getObj('cuentas_por_pagar_db_tipo_doc_btn_eliminar').style.display='';
									getObj('cuentas_por_pagar_db_tipo_doc_btn_guardar').style.display='none';									
									
									dialog.hideAndUnload();
									$('#form_cuentas_por_pagar_db_tipo_documento').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'estatus',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
									




						
// -----------------------------------------------------------------------------------------------------------------------------------
$("#cuentas_por_pagar_db_tipo_doc_btn_cancelar").click(function() {

	setBarraEstado("");
	clearForm('form_cuentas_por_pagar_db_tipo_documento');
	getObj('cuentas_por_pagar_db_tipo_doc_btn_actualizar').style.display='none';
	getObj('cuentas_por_pagar_db_tipo_doc_btn_eliminar').style.display='none';
	getObj('cuentas_por_pagar_db_tipo_doc_btn_guardar').style.display='';
	getObj('cuentas_por_pagar_db_tipo_doc_btn_consultar').style.display='';
									
});
///////////////////////////////////////////////
$("#cuentas_por_pagar_db_tipo_doc_btn_eliminar").click(function() {
	if(confirm("øDesea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url:'modulos/cuentas_por_pagar/tipo_documento/db/sql_eliminar.tipodocumentocxp.php',
			data:dataForm('form_cuentas_por_pagar_db_tipo_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
			//setBarraEstado(html);
				if (html=="Eliminado")
				{
				setBarraEstado(mensaje[eliminacion_exitosa],true,true);
				setBarraEstado("");
				setBarraEstado("");
					clearForm('form_cuentas_por_pagar_db_tipo_documento');
					getObj('cuentas_por_pagar_db_tipo_doc_btn_actualizar').style.display='none';
					getObj('cuentas_por_pagar_db_tipo_doc_btn_eliminar').style.display='none';
					getObj('cuentas_por_pagar_db_tipo_doc_btn_guardar').style.display='';
					getObj('cuentas_por_pagar_db_tipo_doc_btn_consultar').style.display='';
				
				
				}
				else
				if(html=="documento_tipo")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL TIPO DE DOCUMENTO CUENTA CON DOCUMENTOS RELACIONADOS</p></div>",true,true);
				
				}

				else
				{
					// setBarraEstado(html);
					setBarraEstado(mensaje[relacion_existe],true,true);
					
				}
			}
		});
	}
});

///////////////////////////////////////////////

</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>


$('#cuentas_por_cobrar_db_tipo_documento').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#cuentas_por_cobrar_db_siglas_documento').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
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
	<img id="cuentas_por_pagar_db_tipo_doc_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="cuentas_por_pagar_db_tipo_doc_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
   	<img id="cuentas_por_pagar_db_tipo_doc_btn_consultar" class="btn_consultar"src="imagenes/null.gif" />
	<img id="cuentas_por_pagar_db_tipo_doc_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="cuentas_por_pagar_db_tipo_doc_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
	</div>
<form method="post" id="form_cuentas_por_pagar_db_tipo_documento" name="form_cuentas_por_pagar_db_tipo_documento">
<input type="hidden"  id="cuentas_por_pagar_db_id_tipo" name="cuentas_por_pagar_db_id_tipo"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Tipo Documento </th>
	</tr>
	<tr>
		<th>Nombre:</th>
	    <td>
		<ul class="input_con_emergente">
		    	<input name="cuentas_por_cobrar_db_tipo_documento" type="text" id="cuentas_por_cobrar_db_tipo_documento"    size="40" maxlength="60" message="Ingrese el Nombre del Documento." 
							jval="{valid:/^[a-zA-Z·ÈÌÛ˙¡…Õ”⁄Ò ]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z·ÈÌÛ˙¡…Õ”⁄Ò]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		        <input type="hidden"  id="cuentas_por_cobrar_db_tipo_documento_id" name="cuentas_por_cobrar_db_tipo_documento_id"/>
		</td>
	</tr>
 
  <tr>
		<th>Siglas:</th>
	    <td>      
		        	    <input name="cuentas_por_cobrar_db_siglas_documento" type="text" id="cuentas_por_cobrar_db_siglas_documento"    size="10" maxlength="10" message="Ingrese las Siglas de Documento." 
							jval="{valid:/^[a-zA-Z·ÈÌÛ˙¡…Õ”⁄Ò]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z·ÈÌÛ˙¡…Õ”⁄Ò]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		</td>
   </tr>
  
		 <tr>
		<th>Comentarios:</th>
		<td><textarea  name="cuentas_por_cobrar_db_tipo_documento_comentarios" cols="60" id="cuentas_por_cobrar_db_tipo_documento_comentarios" message="Introduzca un comentario."></textarea>		</td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>  
<input  name="tesoreria_banco_cuenta_db_id" type="hidden" id="" />
</form>