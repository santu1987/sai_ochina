<? if (!$_SESSION) session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$Sql = "SELECT 
			id_calculo_rrhh,
			codigo,
			nombre
		FROM
			calculo_rrhh
		WHERE 
			calculo_rrhh.id_organismo = $_SESSION[id_organismo] 
		";
$row=& $conn->Execute($Sql);		
?>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script language="javascript">
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
//----------------- fin mascara edicion de campo -------------------------///
</script>
<script language="javascript" type="text/javascript">
//------------------ Marcaras de edicion de campos de entrada -----------------////

var dialog;
//----------------------------------------------------------------------------------------------------
function consulta_automatica(){
	if ($('#form_db_conceptos').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/conceptos/db/sql.consulta_automatica_check.php",
			data:dataForm('form_db_conceptos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				arreglo = html.split('*');
				tam = getObj('conceptos_db_tam').value;
				for(i=0; i<tam; i++){
					for(c=0; c<tam; c++){
						if(arreglo[i]==getObj('conceptos_db_valor'+c).value){
							getObj('conceptos_db_calculo'+c).checked='checked';
						}
					}
				}
				setBarraEstado("");	
			}
		});
	}
}

$("#conceptos_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/conceptos/db/vista.grid_conceptos_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Conceptos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#conceptos_db_descripcion_concepto").val(); 
					var busq_numero= jQuery("#conceptos_db_numero").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/conceptos/db/sql_conceptos_nombre.php?busq_nombre="+busq_nombre+"&busq_numero="+busq_numero,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#conceptos_db_descripcion").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#conceptos_db_numero").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#conceptos_db_descripcion").val();
							var busq_numero= jQuery("#conceptos_db_numero").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/conceptos/db/sql_conceptos_nombre.php?busq_nombre="+busq_nombre+"&busq_numero="+busq_numero,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/conceptos/db/sql_conceptos_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['Cod','Concepto','Limite Inf','Limite Sup','AD','Tipo_Concepto','Observacion','Estatus','Num'],
								colModel:[
									{name:'id_concepto',index:'id_concepto', width:50,sortable:false,resizable:false},
									{name:'descripcion',index:'descripcion', width:100,sortable:false,resizable:false},
									{name:'limite_inf',index:'limite_inf', width:100,sortable:false,resizable:false},
									{name:'limite_sup',index:'limite_sup', width:100,sortable:false,resizable:false},
									{name:'asignacion_deduccion',index:'asignacion_deduccion', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tipo_concepto',index:'tipo_concepto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'observacion',index:'observacion', width:100,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:100,sortable:false,resizable:false, hidden:true},
									{name:'num_orden',index:'num_orden', width:100,sortable:false,resizable:false, hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									for(i=0; i<getObj('conceptos_db_tam').value; i++){
										getObj('conceptos_db_calculo'+i).checked='';
									}
									getObj('conceptos_db_cod').value=ret.id_concepto;
									getObj('conceptos_db_id_concepto').value = ret.id_concepto;					
									getObj('conceptos_db_descripcion_concepto').value = ret.descripcion;
									getObj('conceptos_db_limite_inferior').value = ret.limite_inf;
									getObj('conceptos_db_limite_superior').value = ret.limite_sup;
									if(ret.asignacion_deduccion=='Asignacion'){
										getObj('conceptos_db_ad').selectedIndex = 0;
										getObj('conceptos_db_tr').style.display = '';
									}
									else{
										getObj('conceptos_db_ad').selectedIndex = 1;
										getObj('conceptos_db_tr').style.display = 'none';
										for(i=0; i<getObj('conceptos_db_tam').value; i++){
											getObj('conceptos_db_calculo'+i).checked='';
										}
									}
									
									if(ret.estatus==1 || ret.estatus!=2)
										getObj('conceptos_db_aplica').selectedIndex=0;
									if(ret.estatus==2)
										getObj('conceptos_db_aplica').selectedIndex=1;
									getObj('conceptos_db_id_tipo_concepto').value=ret.id_tipo_concepto;
										
									getObj('conceptos_db_comentario').value = ret.observacion;	
									getObj('conceptos_db_num_orden').value = ret.num_orden;
									getObj('conceptos_db_btn_guardar').style.display = 'none';
									getObj('conceptos_db_btn_actualizar').style.display = '';
									getObj('conceptos_db_btn_eliminar').style.display = '';
									consulta_automatica();
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#conceptos_db_descripcion").focus();
								$('#conceptos_db_descripcion').alpha({allow:' '});
								$('#conceptos_db_numero').numeric({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_concepto',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//

//
//
//----------------------------------------------------------------


$("#conceptos_db_btn_guardar").click(function() {										  
var err = 0;
	limite_inf = getObj('conceptos_db_limite_inferior').value;
	limite_inf = limite_inf.replace('.','');
	limite_inf = limite_inf.replace(',','.');
	limite_sup = getObj('conceptos_db_limite_superior').value;
	limite_sup = limite_sup.replace('.','');
	limite_sup = limite_sup.replace(',','.');
	limite_inf = parseFloat(limite_inf);
	limite_sup = parseFloat(limite_sup);
	if(limite_inf!='0.00' && limite_sup!='0.00' && limite_inf >= limite_sup){
		err = 1;
	}
	if(limite_inf=='0.00' && limite_sup!='0.00'){
		err = 1;
	}
	if(limite_inf!='0.00' && limite_sup=='0.00'){
		err = 1;
	}
	if(limite_inf=='0.00' && limite_sup=='0.00'){
		err = 0;
	}
	
	//
	if(err==1)
		err_limites();
	if(err==0){											  
	
		if ($('#form_db_conceptos').jVal()){											   
			setBarraEstado(mensaje[esperando_respuesta]);
			$.ajax (
			{
			url: "modulos/rrhh/conceptos/db/sql.registrar_conceptos.php",
				data:dataForm('form_db_conceptos'),
				type:'POST',
				cache: false,
				success: function(html)
				{
					if (html=="Registrado")
					{
						setBarraEstado(mensaje[registro_exitoso],true,true);
						getObj('conceptos_db_id_tipo_concepto').value='';
						getObj('conceptos_db_tipo_concepto').value='';
						getObj('conceptos_db_descripcion_concepto').value = '';
						getObj('conceptos_db_ad').selectedIndex = 0;
						getObj('conceptos_db_tipo_concepto').selectedIndex = 0;
						getObj('conceptos_db_aplica').selectedIndex = 0;
						getObj('conceptos_db_porcentaje').value = '00,00';
						getObj('conceptos_db_limite_inferior').value = '0,00';
						getObj('conceptos_db_limite_superior').value = '0,00';
						for(i=0; i<getObj('conceptos_db_tam').value; i++){
							getObj('conceptos_db_calculo'+i).checked='';
						}
						getObj('conceptos_db_comentario').value = '';
						getObj('conceptos_db_tr').style.display='';
	//					clearForm('form_db_valor_impuesto');
					}
					else if (html=="Existe")
					{
						setBarraEstado(mensaje[registro_existe],true,true);
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
	}
});

//----------------------------------------------------------------
//-----------------------Actualizar-------------------------------
$("#conceptos_db_btn_actualizar").click(function() {
var err = 0;
	limite_inf = getObj('conceptos_db_limite_inferior').value;
	limite_inf = limite_inf.replace('.','');
	limite_inf = limite_inf.replace(',','.');
	limite_sup = getObj('conceptos_db_limite_superior').value;
	limite_sup = limite_sup.replace('.','');
	limite_sup = limite_sup.replace(',','.');
	limite_inf = parseFloat(limite_inf);
	limite_sup = parseFloat(limite_sup);
	if(limite_inf!='0.00' && limite_sup!='0.00' && limite_inf >= limite_sup){
		err = 1;
	}
	if(limite_inf=='0.00' && limite_sup!='0.00'){
		err = 1;
	}
	if(limite_inf!='0.00' && limite_sup=='0.00'){
		err = 1;
	}
	if(limite_inf=='0.00' && limite_sup=='0.00'){
		err = 0;
	}
	
	//
	if(err==1)
		err_limites();
	if(err==0){
	if ($('#form_db_conceptos').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/conceptos/db/sql.actualizar_conceptos.php",
			data:dataForm('form_db_conceptos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('conceptos_db_id_tipo_concepto').value='';
					getObj('conceptos_db_tipo_concepto').value='';
					getObj('conceptos_db_id_concepto').value = '';
					getObj('conceptos_db_descripcion_concepto').value = '';
					getObj('conceptos_db_ad').selectedIndex = 0;
					getObj('conceptos_db_tipo_concepto').selectedIndex = 0;
					getObj('conceptos_db_aplica').selectedIndex = 0;
					getObj('conceptos_db_porcentaje').value = '00,00';
					getObj('conceptos_db_num_orden').value='';
					getObj('conceptos_db_limite_inferior').value = '0,00';
					getObj('conceptos_db_limite_superior').value = '0,00';
					for(i=0; i<getObj('conceptos_db_tam').value; i++){
						getObj('conceptos_db_calculo'+i).checked='';
					}
					getObj('conceptos_db_comentario').value = '';
					getObj('conceptos_db_cod').value='';
					getObj('conceptos_db_tr').style.display = '';
					getObj('conceptos_db_btn_actualizar').style.display = 'none';
					getObj('conceptos_db_btn_eliminar').style.display = 'none';
					getObj('conceptos_db_btn_guardar').style.display = '';
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
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
	}
});

//
//----------------------------------------------------------------
$("#conceptos_db_btn_eliminar").click(function() {
	if ($('#form_db_conceptos').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/conceptos/db/sql.eliminar_conceptos.php",
			data:dataForm('form_db_conceptos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('conceptos_db_id_tipo_concepto').value='';
					getObj('conceptos_db_tipo_concepto').value='';
					getObj('conceptos_db_id_concepto').value = '';
					getObj('conceptos_db_cod').value = '';
					getObj('conceptos_db_descripcion_concepto').value = '';
					getObj('conceptos_db_num_orden').value = '';
					getObj('conceptos_db_ad').selectedIndex = 0;
					getObj('conceptos_db_tipo_concepto').selectedIndex = 0;
					getObj('conceptos_db_aplica').selectedIndex = 0;
					getObj('conceptos_db_porcentaje').value = '00,00';
					getObj('conceptos_db_limite_inferior').value = '0,00';
					getObj('conceptos_db_limite_superior').value = '0,00';
					for(i=0; i<getObj('conceptos_db_tam').value; i++){
						getObj('conceptos_db_calculo'+i).checked='';
					}	
					getObj('conceptos_db_comentario').value = '';
					getObj('conceptos_db_btn_actualizar').style.display = 'none';
					getObj('conceptos_db_btn_eliminar').style.display = 'none';
					getObj('conceptos_db_btn_guardar').style.display = '';
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Relacion_Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
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

//
//
$("#conceptos_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/conceptos/db/vista.grid_tipo_concepto.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Concepto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#conceptos_db_descripcion").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/conceptos/db/sql_tipo_concepto.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#conceptos_db_descripcion").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#conceptos_db_descripcion").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/conceptos/db/sql_tipo_concepto.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/conceptos/db/sql_tipo_concepto.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Descripcion','Comentario'],
								colModel:[
									{name:'id_tipo_concepto',index:'id_tipo_concepto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'descripcion',index:'descripcion', width:100,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('conceptos_db_id_tipo_concepto').value=ret.id_tipo_concepto;
									getObj('conceptos_db_tipo_concepto').value=ret.descripcion;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#conceptos_db_descripcion").focus();
								$('#conceptos_db_descripcion').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_tipo_concepto',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
$("#conceptos_db_cod").change(function() {
	//setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/conceptos/db/sql_consulta_automatica.php",
			data:dataForm('form_db_conceptos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if(html!=''){
					arreglo = html.split('*');
					getObj('conceptos_db_id_concepto').value=arreglo[0];
					getObj('conceptos_db_descripcion_concepto').value=arreglo[1];
					getObj('conceptos_db_limite_inferior').value=arreglo[3];
					getObj('conceptos_db_limite_superior').value=arreglo[4];
					getObj('conceptos_db_comentario').value=arreglo[5];
					if(arreglo[6]==1 || arreglo[6]!=2)
						getObj('conceptos_db_aplica').selectedIndex=0;
					if(arreglo[6]==2)
						getObj('conceptos_db_aplica').selectedIndex=1;
						
					getObj('conceptos_db_num_orden').value=arreglo[7];
					getObj('conceptos_db_btn_guardar').style.display='none';
					getObj('conceptos_db_btn_eliminar').style.display='';
					getObj('conceptos_db_btn_actualizar').style.display='';
					consulta_automatica();
				}
				else if(html=='')
					limpiar();	
			}
		});
});
//
//
$("#conceptos_db_ad").change(function() {
	//setBarraEstado(mensaje[esperando_respuesta]);
		if(getObj('conceptos_db_ad').selectedIndex==0){
			getObj('conceptos_db_tr').style.display='';
		}
		if(getObj('conceptos_db_ad').selectedIndex==1){
			getObj('conceptos_db_tr').style.display='none';
			for(i=0; i<getObj('conceptos_db_tam').value; i++){
				getObj('conceptos_db_calculo'+i).checked='';
			}	
		}
});
//
//
//
// ******************************************************************************
$("#conceptos_db_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
	limpiar();
});
//
function limpiar(){
	getObj('conceptos_db_id_tipo_concepto').value='';
	getObj('conceptos_db_tipo_concepto').value='';
	getObj('conceptos_db_id_concepto').value = '';
	getObj('conceptos_db_descripcion_concepto').value = '';
	getObj('conceptos_db_ad').selectedIndex = 0;
	getObj('conceptos_db_tipo_concepto').selectedIndex = 0;
	getObj('conceptos_db_aplica').selectedIndex=0;
	getObj('conceptos_db_num_orden').value = '';
	getObj('conceptos_db_porcentaje').value = '00,00';
	getObj('conceptos_db_limite_inferior').value = '0,00';
	getObj('conceptos_db_limite_superior').value = '0,00';
	for(i=0; i<getObj('conceptos_db_tam').value; i++){
		getObj('conceptos_db_calculo'+i).checked='';
	}
	getObj('conceptos_db_comentario').value='';
	getObj('conceptos_db_cod').value='';
	getObj('conceptos_db_tr').style.display='';
	getObj('conceptos_db_btn_actualizar').style.display = 'none';
	getObj('conceptos_db_btn_eliminar').style.display = 'none';
	getObj('conceptos_db_btn_guardar').style.display = '';
	setBarraEstado("");
}
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

function err_limites(){
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL LIMITE SUPERIOR NO PUEDE SER MENOR AL LIMITE INFERIOR</p></div>",true,true);
}

//-------------------------------------------------------------------------------------------------------------------------------------------------


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
$('#conceptos_db_porcentaje').numeric({allow:','});
$('#conceptos_db_descripcion_concepto').alpha({allow:' '});
$('#conceptos_db_cod').numeric({allow:' '});
$('#conceptos_db_num_orden').numeric({allow:'  '});
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
	<img id="conceptos_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="conceptos_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="conceptos_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="conceptos_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="conceptos_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_conceptos" id="form_db_conceptos">
<input type="hidden" name="conceptos_db_id_concepto" id="conceptos_db_id_concepto" />
<input type="hidden" name="conceptos_db_id_tipo_concepto" id="conceptos_db_id_tipo_concepto"/>
<input type="hidden" name="conceptos_db_fechact" id="conceptos_db_fechact" value="<?php echo date("d-m-Y");?>"/>

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="4">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Conceptos			</th>
	</tr>
    	<tr>
			<th width="159">Cód</th>
		  <td colspan="3"><input name="conceptos_db_cod" type="text"  id="conceptos_db_cod" size="6" maxlength="6" />
		  </td>
		</tr>
    	<tr>
			<th width="159">Descripción</th>
		  <td colspan="3"><input name="conceptos_db_descripcion_concepto" type="text"  id="conceptos_db_descripcion_concepto" size="30" maxlength="60" message="Introduzca la Descripción del Concepto " jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute; ñÑ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute; ñÑ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		  </td>
		</tr>
        <tr>
			<th width="159">Nº Orden</th>
		  <td colspan="3"><input name="conceptos_db_num_orden" type="text"  id="conceptos_db_num_orden" size="6" maxlength="2" message="Introduzca el Numero en que se Ordenara los Conceptos " jval="{valid:/^[ 0-9]{1,60}$/, message:'Nº Invalido', styleType:'cover'}"
			jvalkey="{valid:/[ 0-9]/, cFunc:'alert', cArgs:['Numero: '+$(this).val()]}"/>
		  </td>
		</tr>
        <tr>
			<th>Tipo Concepto</th>
		  <td colspan="3"><select id="conceptos_db_ad" name="conceptos_db_ad" style=" width:25px">
          <option>Asignacion</option>
          <option>Deduccion</option>
          </select>
		  </td>
		</tr>
        <tr style="display:none">
			<th>Tipo Concepto</th>
		  <td colspan="3">
             <ul class="input_con_emergente">
				<li>
           <input name="conceptos_db_tipo_concepto" type="text"  id="conceptos_db_tipo_concepto" readonly="true"
           jval="{valid:/^[VEP- 0-9]{1,60s}$/, message:'Cedula Invalida', styleType:'cover'}"
			jvalkey="{valid:/[VEP - 0-9]/, cFunc:'alert', cArgs:['Cedula: '+$(this).val()]}"/>
           </li>
				<li id="conceptos_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr style="display:none">
			<th>Porcentaje</th>
		  <td colspan="3"><input  name="conceptos_db_porcentaje" type="text" id="conceptos_db_porcentaje" size="8" alt="signed-decimal-im" style="text-align:right" message="Introduzca el Porcentaje del Concepto" jval="{valid:/^[0-9,]{1,12}$/, message:'Porcentaje Invalido', styleType:'cover'}" jvalkey="{valid:/[0-9,]/, cFunc:'alert', cArgs:['Porcentaje: '+$(this).val()]}"/>		    <strong>(%)</strong></td>
		</tr>
        <tr>
			<th>Limite Inferior</th>
		  <td width="211"><input name="conceptos_db_limite_inferior" type="text"  id="conceptos_db_limite_inferior" maxlength="5" size="8" alt="signed-decimal" jval="{valid:/^[0-9.,]{1,60}$/, message:'Limite Inferior Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9.,]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		  </td>
		  <td width="132"><strong>&nbsp;Limite Superior </strong></td>
		  <td width="198"><input name="conceptos_db_limite_superior" type="text"  id="conceptos_db_limite_superior" maxlength="6" size="6" alt="signed-decimal" jval="{valid:/^[0-9.,]{1,60}$/, message:'Limite Superior Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9.,]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/></td>
		</tr>
        <tr>
			<th>Aplica fin de Mes</th>
		  <td colspan="3"><label>
		    <select name="conceptos_db_aplica" id="conceptos_db_aplica" class="selectcorto">
		      <option value="1">NO</option>
		      <option value="2">SI</option>
	        </select>
	      </label></td>
		</tr>
        <tr id="conceptos_db_tr">
			<th>Calculos</th>
		  <td colspan="3"><?php $i=0; while(!$row->EOF){?>
          <input type="checkbox" id="conceptos_db_calculo<?php echo $i;?>" name="conceptos_db_calculo<?php echo $i;?>" value="<?php echo $row->fields("id_calculo_rrhh");?>" /> <?php echo " ".$row->fields("nombre")." ";?>
          
          <input type="hidden" id="conceptos_db_valor<?php echo $i;?>" name="conceptos_db_valor<?php echo $i;?>" value="<?php echo $row->fields("id_calculo_rrhh");?>" />
			 <?php 
			 $row->MoveNext();
			 $i++;
			 }
		  ?>
          <input type="hidden" id="conceptos_db_tam" name="conceptos_db_tam" value="<?php echo $i;?>" />
          </td>
		</tr>
        <tr>
			<th>Observaci&oacute;n</th>
		  <td colspan="3"><label>
		    <textarea name="conceptos_db_comentario" id="conceptos_db_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'Este porcentaje es...'"></textarea>
		  </label></td>
		</tr>
		<tr>
			<td colspan="4" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>