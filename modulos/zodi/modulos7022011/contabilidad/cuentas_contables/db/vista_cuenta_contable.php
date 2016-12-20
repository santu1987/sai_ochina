<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	$sql="SELECT naturaleza_cuenta.descripcion AS nombre,id FROM naturaleza_cuenta WHERE (id_organismo = ".$_SESSION["id_organismo"].") ORDER BY naturaleza_cuenta.id";
	$rs_tipos_doc =& $conn->Execute($sql);
	while (!$rs_tipos_doc->EOF) {
		$opt_naturaleza.="<option value='".$rs_tipos_doc->fields("id")."' >".$rs_tipos_doc->fields("nombre")."</option>";
		$rs_tipos_doc->MoveNext();
	}

?>
<script type='text/javascript'>
var dialog;
//
//
$("#contabilidad_cuentas_contables_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_cuenta_contable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_contable.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-cuenta-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload2,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-cuenta-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_contable.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_contable.php?busq_nom="+busq_nom;	
					//alert(url);
				}
				//
				$("#consulta-cuenta-contable-busqueda-nombre").keypress(
					function(key)
					{
						
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
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_contable.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_contable.php?busq_cuenta="+busq_cuenta;
                 //  alert(url);				
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
			url:'modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_contable.php?nd='+nd,
			datatype: "json",
			colNames:['','Cuenta','Nombre','Tipo','tipo','id_cuenta_suma','cuenta_suma','id_cuenta_presupuesto','cuenta_presupuesto','naturaleza_cuenta','requiere_auxiliar','requiere_proyecto','requiere_unidad_ejecutora','requiere_utf'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'cuenta',index:'cuenta', width:50,sortable:false,resizable:false},
				{name:'nombre',index:'nombre', width:250,sortable:false,resizable:false},
				{name:'tipo_cuenta',index:'tipo_cuenta', width:60,sortable:false,resizable:false},
				{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false,hidden:true},
				{name:'id_cuenta_suma',index:'id_cuenta_suma', width:100,sortable:false,resizable:false,hidden:true},
				{name:'cuenta_suma',index:'cuenta_suma', width:100,sortable:false,resizable:false,hidden:true},
				{name:'id_cuenta_presupuesto',index:'id_cuenta_presupuesto', width:100,sortable:false,resizable:false,hidden:true},
				{name:'cuenta_presupuesto',index:'cuenta_presupuesto', width:100,sortable:false,resizable:false,hidden:true},
				{name:'naturaleza_cuenta',index:'naturaleza_cuenta', width:100,sortable:false,resizable:false,hidden:true},
				{name:'requiere_auxiliar',index:'requiere_auxiliar', width:100,sortable:false,resizable:false,hidden:true},
				{name:'requiere_proyecto',index:'requiere_proyecto', width:100,sortable:false,resizable:false,hidden:true},
				{name:'requiere_unidad_ejecutora',index:'requiere_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
				{name:'requiere_utf',index:'requiere_utf', width:100,sortable:false,resizable:false,hidden:true}
			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#contabilidad_cuentas_contables_db_id').val(ret.id);
				$('#contabilidad_cuentas_contables_db_cuenta_contable').val(ret.cuenta);
				$('#contabilidad_cuentas_contables_db_nombre').val(ret.nombre);
				$("#contabilidad_cuentas_contables_db_tipo_cuenta").selectOptions(ret.tipo);
				contabilidad_cuentas_contables_db_tipo_cuenta_change();
				$("#contabilidad_cuentas_contables_db_id_cuenta_suma").val(ret.id_cuenta_suma);
				$("#contabilidad_cuentas_contables_db_cuenta_suma").val(ret.cuenta_suma);
				$("#contabilidad_cuentas_contables_db_id_cuenta_presupuesto").val(ret.id_cuenta_presupuesto);
				$("#contabilidad_cuentas_contables_db_cuenta_presupuesto").val(ret.cuenta_presupuesto);
				getObj('cuentas_contables_db_naturaleza').value=ret.naturaleza_cuenta;
				getObj('contabilidad_cuentas_contables_db_requiere_auxiliar').value=ret.requiere_auxiliar;
				getObj('contabilidad_cuentas_contables_db_requiere_unidad_ejecutora').value=ret.requiere_unidad_ejecutora;
				getObj('contabilidad_cuentas_contables_db_requiere_proyecto').value=ret.requiere_proyecto;
				getObj('contabilidad_cuentas_contables_db_requiere_utf').value=ret.requiere_utf;

				$('#contabilidad_cuentas_contables_db_btn_eliminar').show()
				getObj('contabilidad_cuentas_contables_db_btn_cancelar').style.display='';
				getObj('contabilidad_cuentas_contables_db_btn_actualizar').style.display='';
				getObj('contabilidad_cuentas_contables_db_btn_guardar').style.display='none';
				

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

$("#contabilidad_cuentas_contables_db_btn_eliminar").click(function() {
	if($('#form_contabilidad_db_cuentas_contables').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/cuentas_contables/db/sql.eliminar.php",
			data:dataForm('form_contabilidad_db_cuentas_contables'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					limpiar_cuenta_contable();
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



$("#contabilidad_cuentas_contables_db_btn_actualizar").click(function() {
		setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_contabilidad_db_auxiliares').jVal())
	{
		$.ajax (
		{
			url: "modulos/contabilidad/cuentas_contables/db/sql.actualizar.php",
			data:dataForm('form_contabilidad_db_cuentas_contables'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
				   limpiar_cuenta_contable();
				}
			/*	else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
					getObj('contabilidad_auxiliares_db_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_db_auxiliares');
				}*/
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#contabilidad_cuentas_contables_db_btn_guardar").click(function() {
	if($('#form_contabilidad_db_cuentas_contables').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/cuentas_contables/db/sql.registrar.php",
			data:dataForm('form_contabilidad_db_cuentas_contables'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar_cuenta_contable();
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					limpiar_cuenta_contable();
				}
				else
				{
					setBarraEstado(html,true,true);					
				}			
			}
		});
	}
});

$("#contabilidad_cuentas_contables_db_btn_consultar_cuenta_presupuesto").click(function() {
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/contabilidad/cuentas_contables/db/grid_cuenta_presupuesto.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/cuentas_contables/db/grid_cuenta_presupuesto.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta ', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("cuenta_contable_db-consultas-busqueda_partida").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_presupuesto.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#cuenta_contable_db-consultas-busqueda_partida").keypress(
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
					var busq_cuenta= $("#cuenta_contable_db-consultas-busqueda_partida").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_presupuesto.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_presupuesto.php?busq_cuenta="+busq_cuenta;
					//alert(url);
				}
			}
		}
	);						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_presupuesto.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Cuenta', 'Denominacion','Tipo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta',index:'cuenta', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#contabilidad_cuentas_contables_db_cuenta_presupuesto').val(ret.cuenta);
									$('#contabilidad_cuentas_contables_db_id_cuenta_presupuesto').val(ret.id);
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
								sortname: 'id_organismo',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#contabilidad_cuentas_contables_db_btn_consultar_cuenta_suma").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_cuenta_contable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-cuenta-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload2,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-cuenta-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom;	
					//alert(url);
				}
				//
				$("#consulta-cuenta-contable-busqueda-nombre").keypress(
					function(key)
					{
						
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
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta;
                 //  alert(url);				
				}
			}
		}
	);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_suma.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Cuenta', 'Denominacion','Tipo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#contabilidad_cuentas_contables_db_cuenta_suma').val(ret.cuenta_contable);
									$('#contabilidad_cuentas_contables_db_id_cuenta_suma').val(ret.id);
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
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#contabilidad_cuentas_contables_db_btn_consultar_moneda").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/contabilidad/cuentas_contables/db/grid_moneda.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Impuesto', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/contabilidad/cuentas_contables/db/sql_grid_moneda.php?nd='+nd,
								datatype: "json",
								colNames:['id_moneda','Codigo', 'Nombre','Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_moneda',index:'codigo_moneda', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#contabilidad_cuentas_contables_db_nombre_moneda').val(ret.nombre);
									$('#contabilidad_cuentas_contables_db_id_moneda').val(ret.id);
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
								sortname: 'id_organismo',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

function contabilidad_cuentas_contables_db_tipo_cuenta_change(){
	switch ($("#contabilidad_cuentas_contables_db_tipo_cuenta").val())
	{
		case 't':
			$('#contabilidad_cuentas_contables_db_cuenta_suma').attr('disabled','');
			$('#contabilidad_cuentas_contables_db_id_cuenta_suma').attr('disabled','');
			$('#contabilidad_cuentas_contables_db_fila_cuenta_suma').show();
		case 'd':
			$('#contabilidad_cuentas_contables_db_cuenta_suma').attr('disabled','');
			$('#contabilidad_cuentas_contables_db_id_cuenta_suma').attr('disabled','');
			$('#contabilidad_cuentas_contables_db_fila_cuenta_suma').show();
		break;
		default:			
			$('#contabilidad_cuentas_contables_db_fila_cuenta_suma').hide();
			$('#contabilidad_cuentas_contables_db_cuenta_suma').attr('disabled', 'disabled');
			$('#contabilidad_cuentas_contables_db_id_cuenta_suma').attr('disabled', 'disabled');
	}
};

$("#contabilidad_cuentas_contables_db_tipo_cuenta").change(contabilidad_cuentas_contables_db_tipo_cuenta_change);

$("#myselect2").selectedOptions()
function limpiar_cuenta_contable(){
setBarraEstado("");
	getObj('contabilidad_cuentas_contables_db_btn_guardar').style.display='';
	getObj('contabilidad_cuentas_contables_db_btn_eliminar').style.display='none';
	getObj('contabilidad_cuentas_contables_db_btn_actualizar').style.display='none';
	getObj('contabilidad_cuentas_contables_db_btn_consultar').style.display='';
	getObj('cuentas_contables_db_naturaleza').value=0;
	///
			$('#contabilidad_cuentas_contables_db_fila_cuenta_suma').hide();
			$('#contabilidad_cuentas_contables_db_cuenta_suma').attr('disabled', 'disabled');
			$('#contabilidad_cuentas_contables_db_id_cuenta_suma').attr('disabled', 'disabled');
			$("#contabilidad_cuentas_contables_db_tipo_cuenta").selectOptions("e");
///	
	clearForm('form_contabilidad_db_cuentas_contables');

}
$("#contabilidad_cuentas_contables_db_btn_cancelar").click(function() {
	limpiar_cuenta_contable();
});
function consulta_automatica_cuentas_contables()
{
	$.ajax({
			url:"modulos/contabilidad/cuentas_contables/db/sql_grid_cuenta_cont_cod.php",
            data:dataForm('form_contabilidad_db_cuentas_contables'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
				getObj('contabilidad_cuentas_contables_db_id').value=recordset[0];
				getObj('contabilidad_cuentas_contables_db_cuenta_contable').value=recordset[1];
				getObj('contabilidad_cuentas_contables_db_nombre').value=recordset[2];
				$("#contabilidad_cuentas_contables_db_tipo_cuenta").selectOptions(recordset[3]);
				contabilidad_cuentas_contables_db_tipo_cuenta_change();
				$("#contabilidad_cuentas_contables_db_id_cuenta_suma").val(recordset[5]);
				$("#contabilidad_cuentas_contables_db_cuenta_suma").val(recordset[6]);

			/*	getObj("contabilidad_cuentas_contables_db_id_cuenta_suma").value=recordset[5];
				getObj("contabilidad_cuentas_contables_db_cuenta_suma").value=recordset[6];*/
				getObj("contabilidad_cuentas_contables_db_id_cuenta_presupuesto").value=recordset[7];
				getObj("contabilidad_cuentas_contables_db_cuenta_presupuesto").value=recordset[8];
				getObj('cuentas_contables_db_naturaleza').value=recordset[9];
				getObj('contabilidad_cuentas_contables_db_requiere_auxiliar').value=recordset[10];
				getObj('contabilidad_cuentas_contables_db_requiere_unidad_ejecutora').value=recordset[11];
				getObj('contabilidad_cuentas_contables_db_requiere_proyecto').value=recordset[12];
				$('#contabilidad_cuentas_contables_db_btn_eliminar').show()
				getObj('contabilidad_cuentas_contables_db_btn_cancelar').style.display='';
				getObj('contabilidad_cuentas_contables_db_btn_actualizar').style.display='';
				getObj('contabilidad_cuentas_contables_db_btn_guardar').style.display='none';

				}
				else
				{
					valor=getObj('contabilidad_cuentas_contables_db_cuenta_contable').value;
					limpiar_cuenta_contable();
					getObj('contabilidad_cuentas_contables_db_cuenta_contable').value=valor;
				}
				
			 }
		});	 	 
}
///////////////////////////////////////////////////
function consulta_automatica_partidas()
{
	$.ajax({
			url:"modulos/contabilidad/cuentas_contables/db/sql_grid_partidas.php",
            data:dataForm('form_contabilidad_db_cuentas_contables'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
				//alert(recordset);
				getObj('contabilidad_cuentas_contables_db_id_cuenta_presupuesto').value=recordset[0];
				}
				else
				{
					getObj('contabilidad_cuentas_contables_db_id_cuenta_presupuesto').value="";
					getObj('contabilidad_cuentas_contables_db_cuenta_presupuesto').value="";

					/*getObj('contabilidad_auxiliares_db_id_cuenta').value="";
					getObj('contabilidad_auxiliares_db_cuenta_contable').value="";
					getObj('contabilidad_auxiliares_db_nombre').value="";
					getObj('contabilidad_auxiliares_db_comentario').value="";*/
				}
				
			 }
		});	 	 
}

///////////////////////////////////////////////////
</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#contabilidad_cuentas_contables_db_cuenta_contable').numeric({});
$('#contabilidad_cuentas_contables_db_cuenta_suma').numeric({});
//$('#contabilidad_cuentas_contables_db_nombre').alpha({allow:' áéíóúÄÉÍÓÚ'});

$('#tesoreria_moneda_db_fecha').numeric({allow:'/-'});
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
	<img id="contabilidad_cuentas_contables_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="contabilidad_cuentas_contables_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="contabilidad_cuentas_contables_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="contabilidad_cuentas_contables_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
	<img id="contabilidad_cuentas_contables_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>

<form method="post" id="form_contabilidad_db_cuentas_contables" name="form_contabilidad_db_cuentas_contables">
<input   type="hidden" name="contabilidad_cuentas_contables_db_id"  id="contabilidad_cuentas_contables_db_id" />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Cuentas Contables</th>
	</tr>
	<tr>
		<th>Cuenta:</th>
	  <td><input type="text" name="contabilidad_cuentas_contables_db_cuenta_contable" id="contabilidad_cuentas_contables_db_cuenta_contable"  style="width:18ex;" size='12' maxlength="12" onchange="consulta_automatica_cuentas_contables()" onblur="consulta_automatica_cuentas_contables()"
					message="Introduzca el N&uacute;mero Cuenta Contable" 
					jval="{valid:/^[0-9]{1,12}$/, message:'Numero de Cuenta Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
	</tr>
	<tr>
		<th>Nombre:</th>
		 <td>
   	<input type="text" name="contabilidad_cuentas_contables_db_nombre" id="contabilidad_cuentas_contables_db_nombre"  style="width:48ex;" size='40' maxlength="200"
					message="Introduzca la Denominaci&oacute;n de la Cuenta" 
					/>	</tr>
	<tr>
		<th>Tipo Cuenta:		</th>	
	    <td>	
	       <select name="contabilidad_cuentas_contables_db_tipo_cuenta" id="contabilidad_cuentas_contables_db_tipo_cuenta" style="width:90px; min-width:90px;">
                <option value="d">DETALLE</option>
                <option value="t">TOTAL</option>
                <option value="e" selected>ENCABEZADO</option>
            </select>        </td>
   </tr>
   <tr>
   		<th>Naturaleza de la Cuenta:</th>
		<td>
		<select  name="cuentas_contables_db_naturaleza" id="cuentas_contables_db_naturaleza">
							
							<option value="0">---- SELECCIONE -----</option>
							<?=strtoupper($opt_naturaleza);?>
				</select>   		</td>	
   </tr>
	<tr id="contabilidad_cuentas_contables_db_fila_cuenta_suma" style="display:none">
		<th>Cuenta Suma:		</th>	
	    <td>
            <table class="clear">
                <tr>
                    <td width="1%">	
                        <input disabled="disabled" name="contabilidad_cuentas_contables_db_cuenta_suma" type="text" id="contabilidad_cuentas_contables_db_cuenta_suma"   style="width:18ex;" maxlength="12"  readonly="readonly"
                                        message="Introduzca la Cuenta Suma. Ejem: '40100000' " 
                                       />
						<input 		type="hidden" name="contabilidad_cuentas_contables_db_id_cuenta_suma"  id="contabilidad_cuentas_contables_db_id_cuenta_suma" />					</td>
                    <td>
                        <img style="vertical-align:middle" class="btn_consulta_emergente" id="contabilidad_cuentas_contables_db_btn_consultar_cuenta_suma" src="imagenes/null.gif" />                    </td>
                </tr>                    
            </table>        </td>
   </tr>
	<tr style="display:none" >
		<th>Cuenta Presupuestaria:		</th>	
	    <td>	
			<table class="clear">
            	<tr>
		            <td width="1%">
                        <input 		name="contabilidad_cuentas_contables_db_cuenta_presupuesto" type="text" id="contabilidad_cuentas_contables_db_cuenta_presupuesto"   style="width:18ex;" maxlength="30" 
                        			message="Introduzca una Cuenta de Presupuesto. Ejem: '40100000, Pagos' " onblur="consulta_automatica_partidas()" onchange="consulta_automatica_partidas();"/>
                        <input 		type="hidden" name="contabilidad_cuentas_contables_db_id_cuenta_presupuesto"  id="contabilidad_cuentas_contables_db_id_cuenta_presupuesto" />					</td>
                    <td>
                        <img style="vertical-align:middle" class="btn_consulta_emergente" id="contabilidad_cuentas_contables_db_btn_consultar_cuenta_presupuesto" src="imagenes/null.gif" />                    </td>
                </tr>                    
			</table>        </td>	           
        </td>
   </tr> 
	<tr style="display:none">
		<th>Moneda:		</th>	
	    <td>	
			<table class="clear">
            	<tr>
		            <td width="1%">
                        <input disabled="disabled" name="contabilidad_cuentas_contables_db_nombre_moneda" type="text" id="contabilidad_cuentas_contables_db_nombre_moneda"   style="width:18ex;" maxlength="30"  readonly
                                    message="Introduzca un Nombre del moneda. Ejem: 'Bolívar' " 
                                    jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Moneda Invalida', styleType:'cover'}"
                                    jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                        <input 		type="hidden" name="contabilidad_cuentas_contables_db_id_moneda"  id="contabilidad_cuentas_contables_db_id_moneda" />					</td>
                    <td>
                        <img style="vertical-align:middle" class="btn_consulta_emergente" id="contabilidad_cuentas_contables_db_btn_consultar_moneda" src="imagenes/null.gif" />                    </td>
                </tr>                    
			</table>        </td>
   </tr> 
	<tr>
		<th>Requiere Auxiliar?:		</th>	
	    <td>	
            <select name="contabilidad_cuentas_contables_db_requiere_auxiliar" id="contabilidad_cuentas_contables_db_requiere_auxiliar" style="width:90px; min-width:90px;">
                <option value="t">SI</option>
                <option value="f" selected>NO</option>
            </select>        </td>
   </tr> 
	<tr>
		<th>Requiere Unidad Ejecutora?:		</th>	
	    <td>	
            <select name="contabilidad_cuentas_contables_db_requiere_unidad_ejecutora" id="contabilidad_cuentas_contables_db_requiere_unidad_ejecutora" style="width:90px; min-width:90px;">
                <option value="t">SI</option>
                <option value="f" selected>NO</option>
            </select>        </td>
   </tr> 
	<tr>
		<th>Requiere Proyecto?:		</th>	
	    <td>	
            <select name="contabilidad_cuentas_contables_db_requiere_proyecto" id="contabilidad_cuentas_contables_db_requiere_proyecto" style="width:90px; min-width:90px;">
                <option value="t">SI</option>
                <option value="f" selected>NO</option>
            </select>        </td>
   </tr>
   <tr>
		<th>Requiere utilizacion de fondos?:		</th>	
	    <td>	
            <select name="contabilidad_cuentas_contables_db_requiere_utf" id="contabilidad_cuentas_contables_db_requiere_utf" style="width:90px; min-width:90px;">
                <option value="t">SI</option>
                <option value="f" selected>NO</option>
            </select>        </td>
   </tr>                      
    <tr>
	    <th>Observaci&oacute;n:</th>
    	<td>
        	<textarea  name="contabilidad_cuentas_contables_db_comentario" cols="60" id="contabilidad_cuentas_contables_db_comentario" message="Introduzca una Observación. Ejem:'Este moneda es ...' " style="width:422px"></textarea>        </td>
    </tr>
    <tr>
    <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>
</form>