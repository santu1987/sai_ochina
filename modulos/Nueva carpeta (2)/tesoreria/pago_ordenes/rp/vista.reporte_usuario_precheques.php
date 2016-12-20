<?php
session_start();
if(date("d")=="31")
{
	$dia=date("d")-1;
	$mes=date("m")-1;
	$ayo=date("Y");
}
	else
	{
		$dia=date("d");	
	}
if(date("m")=="12")
{
	$mes="01";
	$ayo=date("Y")+1;
}
else
	{
	$mes=date("m")-1;
	$ayo=date("Y");
	}
$fecha=date("d/m/Y",mktime(0,0,0,$mes,$dia,$ayo));
?>

<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>


var dialog;

$("#form_precheques_usuarios_rp_btn_imprimir").click(function() {
if(($('#form_rp_precheques_usuarios').jVal()))
	{
	
				if((getObj('tesoreria_precheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_precheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_precheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_precheques_proveedores_rp_id').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_precheques.php¿id_usuario="+getObj('tesoreria_precheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value;
				//url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_precheques.php¿id_usuario="+getObj('tesoreria_precheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value;
				//alert(url);
				//alert(getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value);
				openTab("Precheques/Usuarios",url);
				}
				else
				if((getObj('tesoreria_precheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_precheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_precheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_precheques_usuarios_rp_usuario').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_precheques.php¿id_proveedor="+getObj('tesoreria_precheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value;
						openTab("Precheques/Proveedores",url);
					}
					else
				if((getObj('tesoreria_precheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_precheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_precheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_precheques_usuarios_rp_usuario').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.banco_precheques.php¿id_banco="+getObj('tesoreria_precheques_banco_rp_id_banco').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_precheques_banco_rp_n_cuenta').value+"@tipo="+getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value;
						openTab("Precheques/Bancos",url);
					}
			else
				if((getObj('tesoreria_precheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_precheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_precheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_precheques_usuarios_rp_usuario').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.banco_precheques.php¿id_banco="+getObj('tesoreria_precheques_banco_rp_id_banco').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value;
						openTab("Precheques/Bancos",url);
					}		
			/// consultas de reportes combinados  usuario-banco-cuentas
			else
				if((getObj('tesoreria_precheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_precheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_precheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_precheques_proveedores_rp_id').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_precheques.php¿id_usuario="+getObj('tesoreria_precheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_precheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_precheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value; 
				openTab("Precheques/Usuarios",url);
				}
			/// usuario-banco
			else
				if((getObj('tesoreria_precheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_precheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_precheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_precheques_proveedores_rp_id').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_precheques.php¿id_usuario="+getObj('tesoreria_precheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_precheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value;; 
				openTab("Precheques/Usuarios",url);
				}	
			//porveedor-banco
				else
				if((getObj('tesoreria_precheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_precheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_precheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_precheques_usuarios_rp_usuario').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_precheques.php¿id_proveedor="+getObj('tesoreria_precheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_precheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value;
						openTab("Precheques/Proveedores",url);
					}
			//porveedor-banco-cuentas
				else
				if((getObj('tesoreria_precheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_precheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_precheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_precheques_usuarios_rp_usuario').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_precheques.php¿id_proveedor="+getObj('tesoreria_precheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_precheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_precheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value;
						openTab("Precheques/Proveedores",url);
					}
			//usuario-proveedor
			else
				if((getObj('tesoreria_precheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_precheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_precheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_precheques_proveedores_rp_id').value!=""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_precheques.php¿id_usuario="+getObj('tesoreria_precheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value+"@id_proveedor="+getObj('tesoreria_precheques_proveedores_rp_id').value+"@tipo="+getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value;
				openTab("Precheques/Usuarios",url);
				}	
			else
				if((getObj('tesoreria_precheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_precheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_precheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_precheques_proveedores_rp_id').value!=""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_precheques.php¿id_usuario="+getObj('tesoreria_precheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_precheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_precheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_precheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_precheques_banco_rp_id_banco').value+"@id_proveedor="+getObj('tesoreria_precheques_proveedores_rp_id').value+"@tipo="+getObj('tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque').value; 
				openTab("Precheques/Usuarios",url);
				}			
	 }
});
$("#form_precheques_usuarios_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_precheques_usuarios');
	getObj("tesoreria_precheques_usuarios_rp_fecha_desde").value = "<?=$fecha;   ?>";
	getObj("tesoreria_precheques_usuarios_rp_fecha_hasta").value = "<?=date("d/m/Y");   ?>";
	getObj("tesoreria_precheques_usuarios_rp_fecha_desde_oculto").value="<?=  $fecha; ?>";
	getObj("tesoreria_precheques_usuarios_rp_fecha_hasta_oculto").value="<?= date("d/m/Y"); ?>";
	getObj("tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque").value=1;
	
	
});




//------------------------------------------------------------------------------
$("#tesoreria_precheques_usuarios_rp_btn_consultar_usuario").click(function() {
////
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/rp/grid_usuario_banco_cuentas.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tesoreria-reportes-busq_nombre_usuario").val();
					var busq_usuario= jQuery("#tesoreria-reportes-busq_nombre_usuario2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria-reportes-busq_nombre_usuario").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_reportes_dosearch();
												
					});
				$("#tesoreria-reportes-busq_nombre_usuario2").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_reportes_dosearch();
												
					});
					function tesoreria_usuario_reportes_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(tesoreria_usuario_reportes_gridReload,500)
										}
						function tesoreria_usuario_reportes_gridReload()
						{
							var busq_nombre= jQuery("#tesoreria-reportes-busq_nombre_usuario").val(); 
							var busq_usuario= jQuery("#tesoreria-reportes-busq_nombre_usuario2").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			
						}
			}
		});
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/rp/sql_grid_usuario.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Usuario','Unidad'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'unidad',index:'unidad', width:200,sortable:false,resizable:false},
								
												],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('tesoreria_precheques_usuarios_rp_id_usuario').value = ret.id;
									getObj('tesoreria_precheques_usuarios_rp_usuario').value = ret.nombre;
								
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
	
});


//-------------------------------------------------------------------
//------------------------------------------------------------------------------
$("#tesoreria_precheques_proveedor_db_btn_consultar_proveedor").click(function() {
////
var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/cheques/rp/grid_usuario_banco_cuentas.php", { },
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
								url:'modulos/tesoreria/cheques/rp/cmb.sql.proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo','Proveedor'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_precheques_proveedores_rp_id').value = ret.id_proveedor;
									getObj('tesoreria_precheques_proveedores_rp_codigo').value = ret.codigo;
									getObj('tesoreria_precheques_proveedores_rp_nombre').value = ret.nombre;
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
//-------------------------------------------------------------------
$("#tesoreria_precheques_banco_rp_btn_consultar_banco").click(function() {

	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/rp/grid_banco_cuenta.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos Activos', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:450,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/rp/sql_grid_banco.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Nombre','Sucursal','Direccion','Estatus','Cuenta'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'sucursal',index:'sucursal', width:200,sortable:false,resizable:false,hidden:true},
									{name:'direccion',index:'direccion', width:200,sortable:false,resizable:false,hidden:true},
                                    {name:'estatus',index:'estatus', width:200,sortable:false,resizable:false,hidden:true},
									{name:'cuenta',index:'cuenta', width:200,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_precheques_banco_rp_id_banco').value = ret.id;
									getObj('tesoreria_precheques_banco_rp_nombre').value = ret.nombre;
									getObj('tesoreria_precheques_banco_rp_n_cuenta').value = ret.cuenta;									
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
	
});//--------------------------------------------------------------------------------------------------------
$("#tesoreria_precheques_banco_rp_btn_consultar_cuentas").click(function() {
if(getObj('tesoreria_precheques_banco_rp_id_banco').value!="")
{
	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/rp/grid_banco_cuenta.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuentas Activas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/rp/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_precheques_banco_rp_id_banco').value,
								//'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Nº Cuenta','Estatus'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:50,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_precheques_banco_rp_n_cuenta').value=ret.ncuenta;
									dialog.hideAndUnload();
									//$('#form_tesoreria_db_usuario_banco_cuentas').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'ncuenta',
								viewrecords: true,
								sortorder: "asc"
								
							});
						}
}
});
//--------------------------------------------------------------------------------------------------------------------------------------
//consultas automaticas
function consulta_automatica_proveedor()
{	$.ajax({
			url:"modulos/tesoreria/cheques/rp/sql_grid_proveedor_codigo_precheque.php",
            data:dataForm('form_rp_precheques_usuarios'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('tesoreria_precheques_proveedores_rp_id').value=recordset[0];
				getObj('tesoreria_precheques_proveedores_rp_nombre').value = recordset[1];
				}
				else
			 {  
			   	getObj('tesoreria_precheques_proveedores_rp_nombre').value ="";
				getObj('tesoreria_precheques_proveedores_rp_id').value="";
				}
				
			 }
		});	 	 
}

/*-------------------   Inicio Validaciones  ---------------------------*/
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
	$('#tesoreria_precheques_proveedores_rp_codigo').change(consulta_automatica_proveedor);
/*-------------------   Fin Validaciones  ---------------------------*/
</script>

<div id="botonera">
	<img id="form_cheques_anulados_usuarios_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_cheques_anulados_usuarios_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_precheques_usuarios" id="form_rp_precheques_usuarios">
  <table class="cuerpo_formulario">
    <tr>
      <th class="titulo_frame" colspan="2"> <img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Relaci&oacute;n precheques Emitidos </th>
    </tr>
    <!--<tr>
			<th>Selección</th>
			<td>
				<input id="tesoreria_banco_chequeras_rp_opt_todas" name="presupuesto_ley_pr_opt" type="radio" value="0" checked="checked"> Todas
				<input id="presupuesto_ley_pr_opt_unidad" name="presupuesto_ley_pr_opt" type="radio" value="1"> Una (UNIDAD EJECUTORA)			
			</td>
		</tr>-->
    <tr>
      <th>Usuario</th>
      <td><ul class="input_con_emergente">
          <li>
            <input name="tesoreria_precheques_usuarios_rp_usuario" type="text" id="tesoreria_precheques_usuarios_rp_usuario"    size="50" maxlength="80" message="Seleccione el Nombre de un usuario" readonly 
			 />
            <input type="hidden" id="tesoreria_precheques_usuarios_rp_id_usuario" name="tesoreria_precheques_usuarios_rp_id_usuario"/>
          </li>
        <li id="tesoreria_precheques_usuarios_rp_btn_consultar_usuario" class="btn_consulta_emergente"></li>
      </ul></td>
    </tr>
	<th>Banco:</th>
				<td>
			  <ul class="input_con_emergente">
				<li>
						<input name="tesoreria_precheques_banco_rp_nombre" type="text" id="tesoreria_precheques_banco_rp_nombre"   value="" size="50" maxlength="80" message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' " readonly  
							/>
						<input type="hidden"  id="tesoreria_precheques_banco_rp_id_banco" name="tesoreria_precheques_banco_rp_id_banco"/>
				</li>
				<li id="tesoreria_precheques_banco_rp_btn_consultar_banco" class="btn_consulta_emergente"></li>
			</ul>			</td>
    </tr>
	<tr>
		<th>N&ordm; Cuenta: </th>	
	     <td>
		 
		 <ul class="input_con_emergente">		
		<li>
		  <input name="tesoreria_precheques_banco_rp_n_cuenta" type="text" id="tesoreria_precheques_banco_rp_n_cuenta"   value="" size="50" maxlength="20" message="Seleccione el Número de cuenta. "  readonly />  
		</li>
		<li id="tesoreria_precheques_banco_rp_btn_consultar_cuentas" class="btn_consulta_emergente"></li>	</ul></td>
	<tr>
	<th>Proveedor</th>
		  <td>
				<ul class="input_con_emergente">
				<li>
				<input name="tesoreria_precheques_proveedores_rp_codigo" type="text" id="tesoreria_precheques_proveedores_rp_codigo"  maxlength="4"  message="Introduzca el Nº Proveedor. "
				onchange="consulta_automatica_proveedor" onclick="consulta_automatica_proveedor"
				size="5"/>
	
				<input type="text" name="tesoreria_precheques_proveedores_rp_nombre" id="tesoreria_precheques_proveedores_rp_nombre" size="60"
			    readonly />
				<input type="hidden" name="tesoreria_precheques_proveedores_rp_id" id="tesoreria_precheques_proveedores_rp_id" readonly />
				</li> 
					<li id="tesoreria_precheques_proveedor_db_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
				</ul>
	  </td>
	</tr>
	<tr>
	  <th>Tipo :</th>
	      <td><label>
	      <select name="tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque" id="tesoreria_precheques_proveedor_db_btn_consultar_tipo_cheque">
            <option value="1">Autom&aacute;tico</option>
            <option value="2">Manual</option>
			<option value="3">Todos</option>
         </select>
	      </label></td>
	</tr>
	<tr>
	  <th>Desde :</th>
	      <td><label>
	      <input readonly="true" type="text" name="tesoreria_precheques_usuarios_rp_fecha_desde" id="tesoreria_precheques_usuarios_rp_fecha_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="tesoreria_precheques_usuarios_rp_fecha_desde_oculto" id="tesoreria_precheques_usuarios_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
	      <button type="reset" id="tesoreria_precheques_usuarios_rp_fecha_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_precheques_usuarios_rp_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_precheques_usuarios_rp_fecha_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("tesoreria_precheques_usuarios_rp_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("tesoreria_precheques_usuarios_rp_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("tesoreria_precheques_usuarios_rp_fecha_desde").value =getObj("tesoreria_precheques_usuarios_rp_fecha_desde_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
    </tr>
	<tr>
	  <th>Hasta :</th>
	      <td><label>
	      <input readonly="true" type="text" name="tesoreria_precheques_usuarios_rp_fecha_hasta" id="tesoreria_precheques_usuarios_rp_fecha_hasta" size="7" value="<?   $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="tesoreria_precheques_usuarios_rp_fecha_hasta_oculto" id="tesoreria_precheques_usuarios_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
	      <button type="reset" id="tesoreria_precheques_usuarios_rp_fecha_boton_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_precheques_usuarios_rp_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_precheques_usuarios_rp_fecha_boton_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("tesoreria_precheques_usuarios_rp_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("tesoreria_precheques_usuarios_rp_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("tesoreria_precheques_usuarios_rp_fecha_hasta").value =getObj("tesoreria_cheques_usuarios_rp_fecha_hasta_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
    </tr>	  
    <tr>
      <td colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
  </table>
</form>