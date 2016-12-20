<? if (!$_SESSION) session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>

<script type="text/javascript">
var dialog;
$("#proveedor_db_btn_imprimir").click(function() {
//alert(getObj('covertir_numero_cotizacion').value);
	if(getObj('proveedor_db_codigo').value != ""){
		url="pdf.php?p=modulos/adquisiones/proveedor/rp/vista.lst.proveedor.php¿codigo="+getObj('proveedor_db_codigo').value 
		//alert(url);
		openTab("Ficha Proveedor",url);
	}
});	
			
/*--------------------------------------   GUARDAR ----------------------------------------------------*/
$("#proveedor_db_btn_guardar").click(function() {
		//verProps("opt_92");
	if($('#form_db_proveedor').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/proveedor/db/sql.proveedor.php",
			data:dataForm('form_db_proveedor'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				//dialog=new Boxy(html, { title: 'Consulta Emergente de Proveedor',modal: true,center:false,x:0,y:0});
				
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar_proveedor();
					
					getObj('rnc_db_fecha_rif').value = "<?php echo date("d-m-Y");?>";
					getObj('rnc_db_fecha').value = "<?php echo date("d-m-Y");?>";
					getObj('rnc_db_fecha_sol').value = "<?php echo date("d-m-Y");?>";
					//clearForm('form_db_proveedor');
					
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);

				}else
					{
					//alert(html);
					setBarraEstado(html);
				}
			}
		});
	}
});



///////////////////////////////////////
/*--------------------------------------   BUSCAR ----------------------------------------------------*/
/*--------------------------------------   ACTULIZAR ----------------------------------------------------*/

$("#proveedor_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_proveedor').jVal())
	{
		//check_modificado();	
		$.ajax (
		{
			url: "modulos/adquisiones/proveedor/db/sql.actualizar.php",
			data:dataForm('form_db_proveedor'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('proveedor_db_btn_actualizar').style.display='none';
					getObj('proveedor_db_btn_guardar').style.display='';
			       	getObj('proveedor_db_rif_check').checked='';
					getObj('proveedor_db_nit_check').checked='';
					getObj('proveedor_db_rnc_check').checked='';
					clearForm('form_db_proveedor');
				}
				else if (html=="NoActualizo")
				{
					getObj('proveedor_db_comentario').value=html;
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					alert(html);
					//setBarraEstado(html);
				}
			}
		});
	}
});

$("#proveedor_db_btn_cancelar").click(function() {
limpiar_proveedor();
	getObj('rnc_db_fecha_rif').value = "<?php echo date("d-m-Y");?>";
	getObj('rnc_db_fecha').value = "<?php echo date("d-m-Y");?>";
	getObj('rnc_db_fecha_sol').value = "<?php echo date("d-m-Y");?>";
	

});
function limpiar_proveedor(){

}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_proveedor()
{
	if (getObj('proveedor_db_codigo')!=" ")
	{
	$.ajax({
			url:"modulos/adquisiones/proveedor/db/sql_grid_codigo.php",
            data:dataForm('form_db_proveedor'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
					if(recordset!=" ")
				{
				recordset = recordset.split("*");
					getObj('proveedor_db_id').value = recordset[0];
					getObj('proveedor_db_nombre_prove').value = recordset[1];
					//getObj('proveedor_db_nombre_prove').disabled = true;
					getObj('proveedor_db_ramo').value =recordset[11];
					getObj('proveedor_db_direccion').value = recordset[2];
					getObj('proveedor_db_telefono').value=recordset[3];
					getObj('proveedor_db_fax').value = recordset[4];
					getObj('proveedor_db_tipo').value = recordset[14];
					getObj('proveedor_db_rif').value = recordset[15];
					getObj('proveedor_db_nit').value = recordset[6];
					getObj('proveedor_db_persona_contacto').value = recordset[7];
					getObj('proveedor_db_cargo_contacto').value =recordset[13];
					getObj('proveedor_db_email_contacto').value = recordset[8];
					getObj('proveedor_db_pagina_web').value = recordset[9];
					getObj('proveedor_db_rnc').value = recordset[10];
					getObj('proveedor_db_ramo').value = recordset[12];
					getObj('proveedor_db_comentario').value = recordset[12];
					getObj('proveedor_db_btn_actualizar').style.display='';
					getObj('proveedor_db_btn_guardar').style.display='none';
					getObj('proveedor_db_iva').value = recordset[16];
					getObj('proveedor_db_islr').value = recordset[17];
						
					 consulta_automatica_documento();
					 
					 
				 }
				 else
				 {
				 	getObj('proveedor_db_id').value ="";
					getObj('proveedor_db_nombre_prove').value ="";
					/*getObj('proveedor_db_nombre_prove').disabled = true;*/
					getObj('proveedor_db_direccion').value = "";
					getObj('proveedor_db_telefono').value="";
					getObj('proveedor_db_fax').value= "";
					getObj('proveedor_db_tipo').value = "";
					getObj('proveedor_db_rif').value ="";
					getObj('proveedor_db_nit').value ="";
					getObj('proveedor_db_persona_contacto').value = "";
					getObj('proveedor_db_cargo_contacto').value ="";
					getObj('proveedor_db_email_contacto').value = "";
					getObj('proveedor_db_pagina_web').value = "";
					getObj('proveedor_db_rnc').value = "";
					getObj('proveedor_db_ramo').value ="";
					getObj('proveedor_db_comentario').value ="";
					getObj('proveedor_db_btn_actualizar').style.display='none';
					getObj('proveedor_db_btn_guardar').style.display='';	
				 
				 }
			 }
		});	 	 
	}	
}$("#cuentas_por_pagar_db_btn_consultar_proveedor_ret").click(function() {
		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/cuentas_por_pagar/documentos/db/grid_pagar.php", { },
		//	$.post("/modulos/cuentas_por_pagar/documentos/db/grid_cuentasxpagar.php", { },
						function(data)
                        {					
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?nd='+nd,
								//url:'modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo','Proveedor','rif','ret_iva','ret_islr'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'rif',index:'rif', width:100,sortable:false,resizable:false}
											],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_por_pagar_db_proveedor_ret_id').value = ret.id_proveedor;
									getObj('cuentas_por_pagar_db_proveedor_ret_codigo').value = ret.codigo;
									getObj('cuentas_por_pagar_db_proveedor_ret_nombre').value = ret.nombre;
									rif=ret.rif;
									rif2 = rif.split("-");
									getObj('cuentas_por_pagar_db_proveedor_rif').value=rif2[0];
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
								sortname: 'id_proveedor',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	
});
//----------------------------------------------------------------------------------------------------

//////////////////////////////////////
$('#proveedor_db_codigo').change(consulta_automatica_proveedor)
/*---------------------------------------------  validaciones ----------------------------------------------------------------------------*/

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});

</script>
<div id="botonera">
	<img id="proveedor_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" /><img id="proveedor_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="proveedor_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="proveedor_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>

<form name="form_db_proveedor" id="form_db_proveedor">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame"  colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Retenciones Proveedor</th>
		</tr>
		<tr>
		<th colspan="2" align="right">&nbsp;</th>
		</tr>	
		<tr>
		<th>Proveedor:</th>
		  <td>
		  <ul class="input_con_emergente">
				<li>
				  <input name="cuentas_por_pagar_db_proveedor_ret_codigo" type="text" id="cuentas_por_pagar_db_proveedor_ret_codigo"  maxlength="4"
				onchange="consulta_automatica_proveedor_cxp()" onClick="consulta_automatica_proveedor_cxp()"
				message="Introduzca un Codigo para el proveedor."  size="5"
						jval="{valid:/^[,.-_123456789]{1,6}$/,message:'Código Invalido', styleType:'cover'}"
						jvalkey="{valid:/^[,.-_123456789]{1,6}$/, cFunc:'alert', cArgs:['Código: '+$(this).val()]}" 
		 
				/>
				  <input name="cuentas_por_pagar_db_proveedor_ret_nombre" type="text" id="cuentas_por_pagar_db_proveedor_ret_nombre" size="45" maxlength="60" readonly
				message="Introduzca el nombre del Proveedor." />
				<input type="hidden" name="cuentas_por_pagar_db_proveedor_ret_id" id="cuentas_por_pagar_db_proveedor_ret_id" readonly />
				<input type="hidden" name="cuentas_por_pagar_db_proveedor_ret_rif" id="cuentas_por_pagar_db_proveedor_ret_rif" readonly />
				</li> 
					<li id="cuentas_por_pagar_db_btn_consultar_proveedor_ret" class="btn_consulta_emergente"></li>
	  </ul>	  </td>
		</tr>
		<tr>
			<th>Retencion % IVA</th>
			<td><input  type="text" id="cuentas_por_pagar_db_proveedor_ret_iva" name="cuentas_por_pagar_db_proveedor_ret_iva"  maxlength="6" size="6"  value="0,00" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)"
					jval="{valid:/^[0-9,]{1,12}$/, message:'Porcentaje Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9,]/, cFunc:'alert', cArgs:['Porcentaje: '+$(this).val()]}"/>			 </td>
		</tr>
		<tr>
			<th>Retencion ISLR</th>
			<td><input  type="text" id="cuentas_por_pagar_db_proveedor_ret_islr" name="cuentas_por_pagar_db_proveedor_ret_islr" maxlength="6" size="6"  value="0,00" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)"  
				jval="{valid:/^[0-9,]{1,12}$/, message:'Porcentaje Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9,]/, cFunc:'alert', cArgs:['Porcentaje: '+$(this).val()]}"/></td>
		</tr> 		 		
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="proveedor_db_id" id="proveedor_db_id" />
</form>