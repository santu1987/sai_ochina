<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script type="text/javascript" language="JavaScript">   
/* Marcaras de edicion de campos de entrada --> */
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
</script>
<script type='text/javascript'>
//******************************************************************
var dialog;
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ --   GUARDAR   -- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$("#plan_compra_btn_guardar").click(function() {
	if($('#form_plan_compra').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/adquisiones/plan_compra/pr/sql.registrar.php",
			data:dataForm('form_plan_compra'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
					getObj('plan_compra_pr_id_demanda').value = "";
					getObj('plan_compra_pr_codigo_demanda').value = "";
					getObj('plan_compra_pr_nombre_demanda').value = "";
					
					getObj('plan_compra_pr_id_detelle').value = "";
					getObj('plan_compra_pr_codigo_detalle').value = "";
					getObj('plan_compra_pr_nombre_detalle').value = "";
					
					getObj('plan_compra_pr_cantidad').value = "";
					getObj('plan_compra_pr_valor').value = "";
					getObj('plan_compra_pr_comentario').value = "";
						//clearForm('form_plan_compra');
					});					
				}
				else if (html=="NoRegistro")
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
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ --   ACTUALIZAR   -- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$("#plan_compra_btn_actualizar").click(function() {
	if($('#form_plan_compra').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/adquisiones/plan_compra/pr/sql.actualizar.php",
			data:dataForm('form_plan_compra'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
					getObj('plan_compra_pr_id_demanda').value = "";
					getObj('plan_compra_pr_codigo_demanda').value = "";
					getObj('plan_compra_pr_nombre_demanda').value = "";
					
					getObj('plan_compra_pr_id_detelle').value = "";
					getObj('plan_compra_pr_codigo_detalle').value = "";
					getObj('plan_compra_pr_nombre_detalle').value = "";
					
					getObj('plan_compra_pr_cantidad').value = "";
					getObj('plan_compra_pr_valor').value = "";
					getObj('plan_compra_pr_comentario').value = "";
						//clearForm('form_plan_compra');
					});					
				}
				else if (html=="NoRegistro")
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
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ --   CONSULTAS   -- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//************************************* -- Consulta General UNIDAD -- *****************************
$("#plan_compra_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/plan_compra/pr/grid_plan_compra.php", { },
		function(data)
		{								
				dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Plan de Compras', modal: true,center:false,x:0,y:0,show:false });								
				setTimeout(crear_grid,100);
		});
		function crear_grid()
		{
			jQuery("#list_grid_"+nd).jqGrid
			({
				width:1100,
				height:300,
				recordtext:"Registro(s)",
				loadtext: "Recuperando Información del Servidor",		
				url:'modulos/adquisiones/plan_compra/pr/sql_grid_plan_compra.php?nd='+nd,
				datatype: "json",
				colNames:['id','idd','ano', 'id_unidad_ejecutora', 'codigo_unidad_ejecutora',
						'Unidad Ejecutora','jefe_unidad', 'Reglon', 'id_detalle_demanda',
						'codigo_detalle_demanda','Detalle Demanda', 'id_demanda', 'codigo_demanda',
						'Demanda','Cantidad', 'Valor', 'fecha_propuesta','id Tipo Compra','Tipo Compra','comentario'],
				colModel:[
					{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
					{name:'idd',index:'idd', width:50,sortable:false,resizable:false,hidden:true},
					{name:'ano',index:'ano', width:50,sortable:false,resizable:false,hidden:true},
					{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:200,sortable:false,resizable:false,hidden:true},
					{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora', width:200,sortable:false,resizable:false,hidden:true},
					{name:'unidad_ejecutora',index:'unidad_ejecutora', width:150,sortable:false,resizable:false},
					{name:'jefe_unidad',index:'jefe_unidad', width:50,sortable:false,resizable:false,hidden:true},
					{name:'secuencia',index:'secuencia', width:50,sortable:false,resizable:false},
					{name:'id_detalle_demanda',index:'id_detalle_demanda', width:200,sortable:false,resizable:false,hidden:true},
					{name:'codigo_detalle_demanda',index:'codigo_detalle_demanda', width:200,sortable:false,resizable:false,hidden:true},
					{name:'detalle_demanda',index:'detalle_demanda', width:200,sortable:false,resizable:false},
					{name:'id_demanda',index:'id_demanda', width:50,sortable:false,resizable:false,hidden:true},
					{name:'codigo_demanda',index:'codigo_demanda', width:50,sortable:false,resizable:false,hidden:true},
					{name:'demanda',index:'demanda', width:200,sortable:false,resizable:false},
					{name:'cantidad',index:'cantidad', width:50,sortable:false,resizable:false},
					{name:'valor',index:'valor', width:50,sortable:false,resizable:false},
					{name:'fecha_propuesta',index:'fecha_propuesta', width:50,sortable:false,resizable:false,hidden:true},
					{name:'id_tipo_compra',index:'id_tipo_compra', width:200,sortable:false,resizable:false,hidden:true},
					{name:'tipo_compra',index:'tipo_compra', width:200,sortable:false,resizable:false},
					{name:'comentario',index:'comentario', width:200,sortable:false,resizable:false,hidden:true}

				],
				pager: $('#pager_grid_'+nd),
				rowNum:20,
				rowList:[20,50,100],
				imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
				onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);
					getObj('plan_compra_pr_id').value = ret.id;
					getObj('plan_compra_pr_idd').value = ret.idd;
					getObj('plan_compra_pr_ano').value = ret.ano;					
					getObj('plan_compra_pr_id_unidad').value = ret.id_unidad_ejecutora;
					getObj('plan_compra_pr_codigo_unidad').value = ret.codigo_unidad_ejecutora;
					getObj('plan_compra_pr_nombre_unidad').value = ret.unidad_ejecutora;
					getObj('plan_compra_pr_responsable').value = ret.jefe_unidad;
					getObj('plan_compra_pr_id_demanda').value = ret.id_demanda;
					getObj('plan_compra_pr_codigo_demanda').value = ret.codigo_demanda;
					getObj('plan_compra_pr_nombre_demanda').value = ret.demanda;
					getObj('plan_compra_pr_id_detelle').value = ret.id_detalle_demanda;
					getObj('plan_compra_pr_codigo_detalle').value = ret.codigo_detalle_demanda;
					getObj('plan_compra_pr_nombre_detalle').value = ret.detalle_demanda;
					getObj('plan_compra_pr_cantidad').value = ret.cantidad;
					getObj('plan_compra_pr_valor').value = ret.valor;
					getObj('plan_compra_pr_fecha_propuesta').value = ret.fecha_propuesta;
					getObj('plan_compra_pr_tipo').value = ret.id_tipo_compra;
					getObj('plan_compra_pr_comentario').value = ret.comentario;

					getObj('plan_compra_btn_cancelar').style.display='';
					getObj('plan_compra_btn_actualizar').style.display='';
					getObj('plan_compra_btn_guardar').style.display='none';					
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
				sortname: 'id',
				viewrecords: true,
				sortorder: "asc"
			});
		}
});
//************************************* -- Fin Consulta General  -- *****************************
//************************************* -- Consulta Automatica UNIDAD -- *****************************
function consulta_automatica_unidad()
{
	$.ajax({
			url:"modulos/adquisiones/plan_compra/pr/sql_grid_unidad_codigo.php?codigo_unidad="+getObj('plan_compra_pr_codigo_unidad').value,
            data:dataForm('form_plan_compra'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('plan_compra_pr_id_unidad').value = recordset[0];
				getObj('plan_compra_pr_nombre_unidad').value=recordset[1];
				}
				else
			 {  
			   	getObj('plan_compra_pr_nombre_unidad').value ="";
			    getObj('plan_compra_pr_id_unidad').value="";
				}
			 }
		});	 	 
}
//************************************* -- Consulta Manual UNIDAD -- *****************************
$("#plan_compra_pr_btn_consultar_unidad").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/plan_compra/pr/grid_plan_compra.php", { },
		function(data)
		{								
				dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad de Origen', modal: true,center:false,x:0,y:0,show:false });								
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
				url:'modulos/adquisiones/plan_compra/pr/cmb.sql.unidad.php?nd='+nd,
				datatype: "json",
				colNames:['id','Codigo', 'Unidad', 'responsable'],
				colModel:[
					{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
					{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
					{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
					{name:'responsable',index:'responsable', width:200,sortable:false,resizable:false,hidden:true}

				],
				pager: $('#pager_grid_'+nd),
				rowNum:20,
				rowList:[20,50,100],
				imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
				onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);
					getObj('plan_compra_pr_id_unidad').value = ret.id;
					getObj('plan_compra_pr_codigo_unidad').value = ret.codigo;
					getObj('plan_compra_pr_nombre_unidad').value = ret.nombre;
					getObj('plan_compra_pr_responsable').value = ret.responsable;
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
				sortname: 'codigo_unidad_ejecutora',
				viewrecords: true,
				sortorder: "asc"
			});
		}
});
//************************************* -- Consulta Automatica Demanda -- *****************************
function consulta_automatica_demanda()
{
	$.ajax({
			url:"modulos/adquisiones/plan_compra/pr/sql_grid_demanda_codigo.php?demanda_codigo="+getObj('plan_compra_pr_codigo_demanda').value,
            data:dataForm('form_plan_compra'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('plan_compra_pr_id_demanda').value = recordset[0];
				getObj('plan_compra_pr_nombre_demanda').value=recordset[1];
				getObj('plan_compra_pr_responsable').value=recordset[2];				
				}
				else
			 {  
			   	getObj('plan_compra_pr_id_demanda').value ="";
			    getObj('plan_compra_pr_nombre_demanda').value="";
				getObj('plan_compra_pr_responsable').value="";
				}
			 }
		});	 	 
}
//************************************* -- Consulta Manual Demanda -- *****************************
$("#plan_compra_pr_btn_consultar_demanda").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/plan_compra/pr/grid_plan_compra.php", { },
		function(data)
		{								
				dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de la Demanda', modal: true,center:false,x:0,y:0,show:false });								
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
				url:'modulos/adquisiones/plan_compra/pr/cmb.sql.demanda.php?nd='+nd,
				datatype: "json",
				colNames:['id','Codigo', 'Demanda'],
				colModel:[
					{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
					{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
					{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false}
				],
				pager: $('#pager_grid_'+nd),
				rowNum:20,
				rowList:[20,50,100],
				imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
				onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);
					getObj('plan_compra_pr_id_demanda').value = ret.id;
					getObj('plan_compra_pr_codigo_demanda').value = ret.codigo;
					getObj('plan_compra_pr_nombre_demanda').value = ret.nombre;
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
				sortname: 'codigo_demanda',
				viewrecords: true,
				sortorder: "asc"
			});
		}
});
//************************************* -- Consulta Automatica Detalle de la Demanda -- *****************************
function consulta_automatica_detalle()
{
	if (getObj('plan_compra_pr_codigo_demanda').value != ""){
		$.ajax({
				url:"modulos/adquisiones/plan_compra/pr/sql_grid_detalle_codigo.php?detalle_codigo="+getObj('plan_compra_pr_codigo_detalle').value+"&demanda_codigo="+getObj('plan_compra_pr_codigo_demanda').value,
				data:dataForm('form_plan_compra'), 
				type:'GET',
				cache: false,
				 success:function(html)
				 {
					var recordset=html;				
					if(recordset)
					{
					recordset = recordset.split("*");
					getObj('plan_compra_pr_id_detelle').value = recordset[0];
					getObj('plan_compra_pr_nombre_detalle').value=recordset[1];
					}
					else
				 {  
					getObj('plan_compra_pr_id_detelle').value ="";
					getObj('plan_compra_pr_nombre_detalle').value="";
					}
				 }
		});	 
	}	 
}
//************************************* -- Consulta Manual  Detalle de la Demanda -- *****************************
$("#plan_compra_pr_btn_consultar_detalle").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/plan_compra/pr/grid_plan_compra.php", { },
		function(data)
		{								
				dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente Detalle de la Demanda', modal: true,center:false,x:0,y:0,show:false });								
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
				url:"modulos/adquisiones/plan_compra/pr/cmd.sql.detalle_demanda.php?nd="+nd+"&demanda_codigo="+getObj('plan_compra_pr_codigo_demanda').value,
				datatype: "json",
				colNames:['id','Codigo', 'Detalle'],
				colModel:[
					{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
					{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
					{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false}
				],
				pager: $('#pager_grid_'+nd),
				rowNum:20,
				rowList:[20,50,100],
				imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
				onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);
					getObj('plan_compra_pr_id_detelle').value = ret.id;
					getObj('plan_compra_pr_codigo_detalle').value = ret.codigo;
					getObj('plan_compra_pr_nombre_detalle').value = ret.nombre;
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
				sortname: 'codigo_detalle_demanda',
				viewrecords: true,
				sortorder: "asc"
			});
		}
});
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ --   FIN CONSULTAS   -- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//************************************* -- Consulta Manual  Detalle de la Demanda -- *****************************
$("#plan_compra_btn_cancelar").click(function() {
	clearForm('form_plan_compra');
});

//******************************************************************
$('#plan_compra_pr_codigo_unidad').change(consulta_automatica_unidad);
$('#plan_compra_pr_codigo_demanda').change(consulta_automatica_demanda);
$('#plan_compra_pr_codigo_detalle').change(consulta_automatica_detalle);
//******************************************************************
</script>
<div id="botonera">
	<img id="plan_compra_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
	<img id="plan_compra_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="plan_compra_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="plan_compra_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>

<form name="form_plan_compra" id="form_plan_compra">
<input type="hidden" name="plan_compra_pr_id" id="plan_compra_pr_id"  />
<input type="hidden" name="plan_compra_pr_idd" id="plan_compra_pr_idd"  />

	<table  class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Plan de Compras 
			</th>
		</tr>
		<tr>
			<th>A&ntilde;o</th>
			<td><input type="text" name="plan_compra_pr_ano" id="plan_compra_pr_ano" style="text-align:right" size="5" maxlength="4" /></td>
		</tr>		
		<tr>
			<th>Unidad de Origen</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input type="text" name="plan_compra_pr_codigo_unidad" id="plan_compra_pr_codigo_unidad" size="7" maxlength="6"
						message="Introduzca un Codigo Unidad."	onchange="consulta_automatica_unidad" onclick="consulta_automatica_unidad" />
						<input type="text" name="plan_compra_pr_nombre_unidad" id="plan_compra_pr_nombre_unidad" style="width:55ex" maxlength="60"
						message="Introduzca un Nombre Unidad."  readonly 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ 1234567890-_]{2,60}$/, message:'Unidad Invalida', styleType:'cover'}"
						jValKey="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890-_]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
					</li>
					<li id="plan_compra_pr_btn_consultar_unidad" class="btn_consulta_emergente"></li>
				</ul>
				<input type="hidden" name="plan_compra_pr_id_unidad" id="plan_compra_pr_id_unidad" />
			</td>
		</tr>
		<tr>
			<th>Responsable</th>
			<td><input type="text" name="plan_compra_pr_responsable" id="plan_compra_pr_responsable"  style="width:66ex" maxlength="60"/></td>
		</tr>
		<tr>
			<th colspan="2" bgcolor="#4c7595">&nbsp;</th>
		</tr>
		<tr>
			<th>Demanda</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input type="text" name="plan_compra_pr_codigo_demanda" id="plan_compra_pr_codigo_demanda" size="7" maxlength="6"
						message="Introduzca una Demanda."	onchange="consulta_automatica_demanda" onclick="consulta_automatica_demanda" />
						<input type="text" name="plan_compra_pr_nombre_demanda" id="plan_compra_pr_nombre_demanda" style="width:55ex" maxlength="60"
						message="Introduzca el Nombre de la Demanda."  readonly 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ 1234567890-_]{2,60}$/, message:'Nombre Invalida', styleType:'cover'}"
						jValKey="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890-_]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
					</li>
					<li id="plan_compra_pr_btn_consultar_demanda" class="btn_consulta_emergente"></li>
				</ul>
				<input type="hidden" name="plan_compra_pr_id_demanda" id="plan_compra_pr_id_demanda" />
			</td>
		</tr>
		<tr>
			<th>Detalle de la Demanda</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input type="text" name="plan_compra_pr_codigo_detalle" id="plan_compra_pr_codigo_detalle" size="7" maxlength="6"
						message="Introduzca un detalle."	onchange="consulta_automatica_detalle" onclick="consulta_automatica_detalle" />
						<input type="text" name="plan_compra_pr_nombre_detalle" id="plan_compra_pr_nombre_detalle" style="width:55ex" maxlength="60"
						message="Introduzca el Nombre del detalle de la Demanda."  readonly 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ 1234567890-_]{2,60}$/, message:'Nombre Invalida', styleType:'cover'}"
						jValKey="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890-_]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
					</li>
					<li id="plan_compra_pr_btn_consultar_detalle" class="btn_consulta_emergente"></li>
				</ul>
				<input type="hidden" name="plan_compra_pr_id_detelle" id="plan_compra_pr_id_detelle" />
			</td>
		</tr>
		<tr>
			<th>Cantidad Estimada</th>
			<td><input type="text" name="plan_compra_pr_cantidad" id="plan_compra_pr_cantidad" style="text-align:right" size="12" maxlength="11" alt="integer" /></td>
		</tr>
		<tr>
			<th>Valor</th>
			<td><input type="text" name="plan_compra_pr_valor" id="plan_compra_pr_valor" style="text-align:right" size="12" maxlength="11" /></td>
		</tr>
		<tr>
			<th>Fecha Propuesta:</th>
			<td>
				<label>
				<input readonly="true" type="text" name="plan_compra_pr_fecha_propuesta" id="plan_compra_pr_fecha_propuesta" size="7" value="<?php echo date("d/m/Y")?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
				jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
				<button type="reset" id="fecha_propuesta_boton"> ...</button>
					<script type="text/javascript">
						Calendar.setup({
							inputField     :    "plan_compra_pr_fecha_propuesta",      // id of the input field
							ifFormat       :    "%d/%m/%Y",       // format of the input field
							showsTime      :    false,            // will display a time selector
							button         :    "fecha_propuesta_boton",   // trigger for the calendar (button ID)
							singleClick    :    true          // double-click mode
						});
					</script>
				</label>
			</td>
		</tr>
		<tr>
			<th>Tipo de Compra</th>
			<td>
				<select name="plan_compra_pr_tipo" id="plan_compra_pr_tipo" style="width:92px; min-width:92px;">
					<option value="1">Nacional</option>
					<option value="2">Internacional</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Comentarios</th>
			<td>
				<textarea name="plan_compra_pr_comentario" id="plan_compra_pr_comentario" cols="70"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>