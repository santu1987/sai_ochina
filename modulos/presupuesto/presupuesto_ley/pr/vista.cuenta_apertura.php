<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script type="text/javascript" language="JavaScript">   
/* Marcaras de edicion de campos de entrada --> */
(function($){
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
</script>
<script language="javascript" type="text/javascript">
var lastsel,idd,monto;
//***************************************************************************************************************
$("#aperturar_cuenta_pr_btn_guardar").click(function() {
	if($("#form_pr_aperturar").jVal())
	{	
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/presupuesto_ley/pr/sql.presupuesto_ley.php",
			data:dataForm('form_pr_aperturar'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			html = html.split("*");
				if (html[0]=="Registrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/filetypes.png />LA OPERACION SE REGISTRO CON EXITO</p></div>",true,true);
					clearForm('form_pr_aperturar');
				}
				else if (html[0]=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					//setBarraEstado(mensaje[registro_existe]);
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					//setBarraEstado(html);
				}
			}
		});
	}
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$("#aperturar_cuenta_pr_btn_consultar_unidad").click(function() {
//alert('aqui');
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		//**************
	$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/db/grid_unidad_ejecutora_para.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
			
				dialog=new Boxy(data,{ title: 'Consulta Emergente de unidad', modal: true,center:false,x:0,y:0,show:false});
				//dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad', modal: true,center:false,x:0,y:0,show:false });								
				dialog_reload=function gridReload(){ //alert('aqui');
					var busq_nombre= jQuery("#ante_presupuesto_ley_pr_unidad").val(); 
					var aperturar_cuenta_pr_ano = jQuery("#aperturar_cuenta_pr_ano").val(); 
					
				}
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#ante_presupuesto_ley_pr_unidad").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				//
				//
				//
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
							var busq_nombre= jQuery("#ante_presupuesto_ley_pr_unidad").val();
							var aperturar_cuenta_pr_ano = jQuery("#aperturar_cuenta_pr_ano").val(); 
							
							
						}
			}
		});

	/*$.post("modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.unidad.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Unidad'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								var aperturar_cuenta_pr_ano = jQuery("#aperturar_cuenta_pr_ano").val(); 
									getObj('aperturar_cuenta_pr_id_unidad').value = ret.id;
									getObj('aperturar_cuenta_pr_codigo_unidad').value = ret.codigo;
									getObj('aperturar_cuenta_pr_nombre_unidad').value = ret.nombre;
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
								sortname: 'codigo_unidad',
								viewrecords: true,
								sortorder: "asc"
							});
						}

});
//***********************************************************************************************************************
//***********************************************************************************************************************
function consulta_automatica_unidad_ejecutora()
{
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/pr/sql_grid_unidad_ejecutora.php",
            data:dataForm('form_pr_aperturar'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('aperturar_cuenta_pr_id_unidad').value = recordset[0];
				getObj('aperturar_cuenta_pr_nombre_unidad').value=recordset[1];
				var aperturar_cuenta_pr_ano = jQuery("#aperturar_cuenta_pr_ano").val(); 
				//alert('modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?unidad='+recordset[0]);
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('aperturar_cuenta_pr_id_unidad').value = "";
				getObj('aperturar_cuenta_pr_nombre_unidad').value="";		
				}
			 }
		});	 	 
}
//***********************************************************************************************************************
$("#aperturar_cuenta_pr_btn_consultar_accion_c").click(function() {
if(getObj('aperturar_cuenta_pr_id_proyecto').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.accion_central.php?nd='+nd+'&ano='+getObj('aperturar_cuenta_pr_ano').value,
								datatype: "json",
								colNames:['id','Codigo', 'Accion Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:30,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								var aperturar_cuenta_pr_ano = jQuery("#aperturar_cuenta_pr_ano").val(); 
									getObj('aperturar_cuenta_pr_id_accion_c').value = ret.id;
									getObj('aperturar_cuenta_pr_codigo_accion_central').value = ret.codigo;
									getObj('aperturar_cuenta_pr_nombre_accion_central').value = ret.denominacion;
									getObj('aperturar_cuenta_pr_nombre_proyecto').value="  NO APLICA ESTA OPCION  ";
									getObj('aperturar_cuenta_pr_codigo_proyecto').value ="0000" ;
									getObj('aperturar_cuenta_pr_codigo_proyecto').disabled="disabled" ;
									getObj('aperturar_cuenta_pr_id_proyecto').value="";
									//jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?proyecto='+ret.id,page:1}).trigger("reloadGrid");
									dialog.hideAndUnload();
									//alert('modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?central='+ret.id);
									//jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?central='+ret.id,page:1}).trigger("reloadGrid");

					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'denominacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
// ----------------------------------------------------------------------------------
function consulta_automatica_accion_central()
{ 
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/pr/sql_grid_accion_cental_codigo.php",
            data:dataForm('form_pr_aperturar'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('aperturar_cuenta_pr_id_accion_c').value = recordset[0];
				getObj('aperturar_cuenta_pr_nombre_accion_central').value=recordset[1];
				getObj('aperturar_cuenta_pr_id_proyecto').value="";
				getObj('aperturar_cuenta_pr_nombre_proyecto').value="  NO APLICA ESTA OPCION  ";
				getObj('aperturar_cuenta_pr_codigo_proyecto').value ="0000" ;
				getObj('aperturar_cuenta_pr_codigo_proyecto').disabled="disabled" ;
				var aperturar_cuenta_pr_ano = jQuery("#aperturar_cuenta_pr_ano").val();
				}
				else
			 {  
			   	getObj('aperturar_cuenta_pr_id_accion_c').value ="";
			    getObj('aperturar_cuenta_pr_nombre_accion_central').value="";
				getObj('aperturar_cuenta_pr_id_proyecto').value="";
				getObj('aperturar_cuenta_pr_nombre_proyecto').value="";
				getObj('aperturar_cuenta_pr_codigo_proyecto').value ="" ;
				getObj('aperturar_cuenta_pr_codigo_proyecto').disabled="" ;
				}
			 }
		});	 	 
}
//***********************************************************************************************************************
$("#aperturar_cuenta_pr_btn_consultar_proyecto").click(function() {
if(getObj('aperturar_cuenta_pr_id_accion_c').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.proyecto.php?nd='+nd+'&ano='+getObj('aperturar_cuenta_pr_ano').value,
								datatype: "json",
								colNames:['id','Codigo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:30,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								var aperturar_cuenta_pr_ano = jQuery("#aperturar_cuenta_pr_ano").val();
									getObj('aperturar_cuenta_pr_id_proyecto').value = ret.id;
									getObj('aperturar_cuenta_pr_codigo_proyecto').value = ret.codigo;
									getObj('aperturar_cuenta_pr_nombre_proyecto').value = ret.denominacion;
									getObj('aperturar_cuenta_pr_nombre_accion_central').value="  NO APLICA ESTA OPCION  ";
									getObj('aperturar_cuenta_pr_codigo_accion_central').value ="0000" ;
									getObj('aperturar_cuenta_pr_codigo_accion_central').disabled="disabled" ;
									//alert('modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?proyecto='+ret.id+'&unidad='+getObj('aperturar_cuenta_pr_id_unidad').value);
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
								sortname: 'id_proyecto',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	}

});
// ----------------------------------------------------------------------------------

function consulta_automatica_proyecto()
{
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/pr/sql_grid_proyecto_codigo.php",
            data:dataForm('form_pr_aperturar'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('aperturar_cuenta_pr_id_proyecto').value = recordset[0];
				getObj('aperturar_cuenta_pr_nombre_proyecto').value=recordset[1];
				getObj('aperturar_cuenta_pr_id_accion_c').value="";
				getObj('aperturar_cuenta_pr_nombre_accion_central').value="  NO APLICA ESTA OPCION  ";
				getObj('aperturar_cuenta_pr_codigo_accion_central').value ="0000" ;
				getObj('aperturar_cuenta_pr_codigo_accion_central').disabled="disabled" ;
				var aperturar_cuenta_pr_ano = jQuery("#aperturar_cuenta_pr_ano").val();
				}
				else
			 {  
			   	getObj('aperturar_cuenta_pr_id_proyecto').value ="";
			    getObj('aperturar_cuenta_pr_nombre_proyecto').value="";
				getObj('aperturar_cuenta_pr_nombre_accion_central').value="";
				getObj('aperturar_cuenta_pr_codigo_accion_central').value ="" ;
				getObj('aperturar_cuenta_pr_id_accion_c').value ="";
				getObj('aperturar_cuenta_pr_codigo_accion_central').disabled="" ;
				}
			 }
		});	 	 
}
//***********************************************************************************************************************
function consulta_automatica_especifica_aprobado()
{
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_accion_especifica.php",
            data:dataForm('form_pr_aperturar'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('aperturar_cuenta_pr_id_accion_e').value = recordset[0];
				getObj('aperturar_cuenta_pr_nombre_accion_especifica').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('aperturar_cuenta_pr_id_accion_e').value ="";
			    getObj('aperturar_cuenta_pr_nombre_accion_especifica').value="";
				}
			 }
		});	 	 
}
// -----
$("#aperturar_cuenta_pr_btn_consultar_accion_e").click(function() {
if(getObj('aperturar_cuenta_pr_id_accion_c').value !="" || getObj('aperturar_cuenta_pr_id_proyecto').value !="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.accion_especifica.php?nd='+nd+"&proyecto="+getObj('aperturar_cuenta_pr_id_proyecto').value+"&accion_central="+getObj('aperturar_cuenta_pr_id_accion_c').value,
								datatype: "json",
								colNames:['id','Codigo', 'Accion Especifica'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('aperturar_cuenta_pr_id_accion_e').value = ret.id;
									getObj('aperturar_cuenta_pr_codigo_accion_especifica').value = ret.codigo;
									getObj('aperturar_cuenta_pr_nombre_accion_especifica').value = ret.denominacion;
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
								sortname: 'id_accion_especifica',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	}
});
//***********************************************************************************************************************

function consulta_automatica_partida_numero_aprobado() 
{
//alert('aqui');
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/pr/sql.partida.php",
            data:dataForm('form_pr_aperturar'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('aperturar_cuenta_pr_partida').value = recordset[0];
				getObj('aperturar_cuenta_pr_nombre_partida').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('aperturar_cuenta_pr_partida').value = "";
				getObj('aperturar_cuenta_pr_nombre_partida').value="";		
				}
			 }
		});	
}
//***********************************************************************************************************************

$("#aperturar_cuenta_pr_btn_cancelar").click(function() {

	clearForm('form_pr_aperturar');
} );
//***********************************************************************************************************************
// -----------------------------------------------------------------------------------------------------------------------------------

$('#aperturar_cuenta_pr_codigo_unidad').change(consulta_automatica_unidad_ejecutora);
$('#aperturar_cuenta_pr_codigo_accion_central').change(consulta_automatica_accion_central);
$('#aperturar_cuenta_pr_codigo_proyecto').change(consulta_automatica_proyecto);
$('#aperturar_cuenta_pr_partida').change(consulta_automatica_partida_numero_aprobado);
$('#aperturar_cuenta_pr_codigo_accion_especifica').change(consulta_automatica_especifica_aprobado);

//***********************************************************************************************************************
//alert('llego');
</script>
<div id="botonera">
	<img id="aperturar_cuenta_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<!--<img id="aperturar_cuenta_pr_btn_pdf" class="btn_imprimir"src="imagenes/null.gif"  />		
	<img id="aperturar_cuenta_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>-->
	<img id="aperturar_cuenta_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>
<form name="form_pr_aperturar" id="form_pr_aperturar">
<input type="hidden" name="aperturar_cuenta_pr_cot_select" id="aperturar_cuenta_pr_cot_select" />
	<table  class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Aperturar	Cuenta</th>
		</tr>
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select name="aperturar_cuenta_pr_ano" id="aperturar_cuenta_pr_ano" >
					<option value="2011">2011</option>
				</select>
			</td>
		</tr>
		<tr>
			<th colspan="2" bgcolor="#4c7595">&nbsp;</th>
		</tr>
		<tr>
			<th>Unidad Solicitante :</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" size="6" maxlength="6" name="aperturar_cuenta_pr_codigo_unidad" id="aperturar_cuenta_pr_codigo_unidad" ></td>
						<td><input type="text" size="90" maxlength="100" name="aperturar_cuenta_pr_nombre_unidad" id="aperturar_cuenta_pr_nombre_unidad" readonly></td>
						<td>
							<img id="aperturar_cuenta_pr_btn_consultar_unidad" class="btn_consulta_emergente"  src="imagenes/null.gif" />
							<input type="hidden" name="aperturar_cuenta_pr_id_unidad" id="aperturar_cuenta_pr_id_unidad">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Central:</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" size="6" maxlength="6" name="aperturar_cuenta_pr_codigo_accion_central" id="aperturar_cuenta_pr_codigo_accion_central" ></td>
						<td><input type="text" size="90" maxlength="100" name="aperturar_cuenta_pr_nombre_accion_central" id="aperturar_cuenta_pr_nombre_accion_central" readonly></td>
						<td><img class="btn_consulta_emergente" id="aperturar_cuenta_pr_btn_consultar_accion_c" src="imagenes/null.gif" />
							<input type="hidden" name="aperturar_cuenta_pr_id_accion_c" id="aperturar_cuenta_pr_id_accion_c">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Proyecto:</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" size="6" maxlength="6" name="aperturar_cuenta_pr_codigo_proyecto" id="aperturar_cuenta_pr_codigo_proyecto" ></td>
						<td><input type="text" size="90" maxlength="100" name="aperturar_cuenta_pr_nombre_proyecto" id="aperturar_cuenta_pr_nombre_proyecto" readonly></td>
						<td><img class="btn_consulta_emergente" id="aperturar_cuenta_pr_btn_consultar_proyecto" src="imagenes/null.gif" />
							<input type="hidden" name="aperturar_cuenta_pr_id_proyecto" id="aperturar_cuenta_pr_id_proyecto">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Espec&iacute;fica</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" size="6" maxlength="6" name="aperturar_cuenta_pr_codigo_accion_especifica" id="aperturar_cuenta_pr_codigo_accion_especifica" onchange="consulta_automatica_especifica_aprobado" onclick="consulta_automatica_especifica_aprobado"></td>
						<td><input type="text" size="90" maxlength="100" name="aperturar_cuenta_pr_nombre_accion_especifica" id="aperturar_cuenta_pr_nombre_accion_especifica" readonly></td>
						<td><img class="btn_consulta_emergente" id="aperturar_cuenta_pr_btn_consultar_accion_e" src="imagenes/null.gif" />
							<input type="hidden" name="aperturar_cuenta_pr_id_accion_e" id="aperturar_cuenta_pr_id_accion_e">
						</td>
						
						
				
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Partida :</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" size="12" maxlength="12" name="aperturar_cuenta_pr_partida" id="aperturar_cuenta_pr_partida" 
		onchange="consulta_automatica_partida_numero_aprobado" onclick="consulta_automatica_partida_numero_aprobado"	></td>
						<td><input type="text" size="83" maxlength="100" name="aperturar_cuenta_pr_nombre_partida" id="aperturar_cuenta_pr_nombre_partida" readonly></td>
						<td><!--<img class="btn_consulta_emergente" id="aperturar_cuenta_pr_btn_consultar_partida" src="imagenes/null.gif" />-->
							<input type="hidden" name="aperturar_cuenta_pr_id_partida" id="aperturar_cuenta_pr_id_partida">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>	

	</table>
</form>