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
$("#presupuesto_aprobado_pr_btn_guardar").click(function() {
alert('aqui');
	if($("#form_presupuesto_aprobado").jVal())
	{	
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/presupuesto_ley/pr/sql.presupuesto_ley2.php",
			data:dataForm('form_presupuesto_aprobado'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			html = html.split("*");
				if (html[0]=="Registrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/filetypes.png />LA OPERACION SE REGISTRO CON EXITO</p></div>",true,true);
					clearForm('form_presupuesto_aprobado');
					jQuery("#list_cotizacion_covercion").setGridParam({url:'modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?unidad=0&requisicion=0',page:1}).trigger("reloadGrid");
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
//***************************************************************************************************************
$("#presupuesto_aprobado_pr_btn_anadir").click(function() {
	if($("#form_presupuesto_aprobado").jVal())
	{	
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/presupuesto_ley/pr/sql.presupuesto_ley2.php",
			data:dataForm('form_presupuesto_aprobado'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			html = html.split("*");
				if (html[0]=="Registrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/filetypes.png />SE REGISTRO CON EXITO</p></div>",true,true);
					//clearForm('form_presupuesto_aprobado');
					getObj('presupuesto_aprobado_pr_enero').value ='0,00';
					getObj('presupuesto_aprobado_pr_febrero').value ='0,00';
					getObj('presupuesto_aprobado_pr_marzo').value ='0,00';
					getObj('presupuesto_aprobado_pr_abril').value ='0,00';
					getObj('presupuesto_aprobado_pr_mayo').value ='0,00';
					getObj('presupuesto_aprobado_pr_junio').value ='0,00';
					getObj('presupuesto_aprobado_pr_julio').value ='0,00';
					getObj('presupuesto_aprobado_pr_agosto').value ='0,00';
					getObj('presupuesto_aprobado_pr_septiembre').value ='0,00';
					getObj('presupuesto_aprobado_pr_octubre').value ='0,00';
					getObj('presupuesto_aprobado_pr_noviembre').value ='0,00';
					getObj('presupuesto_aprobado_pr_diciembre').value ='0,00';
					getObj('presupuesto_aprobado_pr_mono_total').value ='0,00';
					getObj('presupuesto_aprobado_pr_partida').value ='';
					getObj('presupuesto_aprobado_pr_nombre_partida').value ='';
					
					//jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?unidad='+getObj('presupuesto_aprobado_pr_id_unidad').value,page:1}).trigger("reloadGrid");
					//jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php',page:1}).trigger("reloadGrid");
				}
				else if (html[0]=="cerrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />EL PRESUPUESTO LEY YA FUE CERRADO</p></div>",true,true);
					//setBarraEstado(mensaje[registro_existe]);
				}
				else
				{
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});


//***************************************************************************************************************
$("#list_presupuesto_aprobado").jqGrid({
	height: 130,
	width: 670,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",

	url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Proyecto/A.C.','Accion Especifica','Partida','Monto',
				'id_unidad_ejecutora','codigo_unidad_ejecutora','unidad_ejecutora',
				'id_central','codigo_central','central',
				'id_proyecto','codigo_proyecto','proyecto',
				'id_accion_especifica','codigo_accion_especifica','accion_especifica',
				'clasificador_presupuestario','anio',
				'enero','febrero','marzo','abril','mayo','junio',
				'julio','agosto','septiembre','octubre','noviembre','diciembre'],
   	colModel:[
	   		{name:'presupuesto_aprobado_pr_cot_select',index:'presupuesto_aprobado_pr_cot_select', width:10,hidden:true},
			{name:'proyecto',index:'proyecto', width:60},
			{name:'denominacion',index:'denominacion', width:55},
	   		{name:'presupuesto_aprobado_pr_partida',index:'presupuesto_aprobado_pr_partida', width:60},
			{name:'presupuesto_aprobado_pr_mono_total',index:'presupuesto_aprobado_pr_mono_total', width:60, align:"right"},
			{name:'presupuesto_aprobado_pr_id_unidad',index:'presupuesto_aprobado_pr_id_unidad', width:200,hidden:true},
			{name:'presupuesto_aprobado_pr_codigo_unidad',index:'presupuesto_aprobado_pr_codigo_unidad', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_nombre_unidad',index:'presupuesto_aprobado_pr_nombre_unidad', width:55,hidden:true},
			{name:'presupuesto_aprobado_pr_id_accion_c',index:'presupuesto_aprobado_pr_id_accion_c', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_codigo_accion_central',index:'presupuesto_aprobado_pr_codigo_accion_central', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_nombre_accion_central',index:'presupuesto_aprobado_pr_nombre_accion_central', width:55, align:"right",hidden:true},
			{name:'presupuesto_aprobado_pr_id_proyecto',index:'presupuesto_aprobado_pr_id_proyecto', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_codigo_proyecto',index:'presupuesto_aprobado_pr_codigo_proyecto', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_nombre_proyecto',index:'presupuesto_aprobado_pr_nombre_proyecto', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_id_accion_e',index:'presupuesto_aprobado_pr_id_accion_e', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_codigo_accion_especifica',index:'presupuesto_aprobado_pr_codigo_accion_especifica', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_nombre_accion_especifica',index:'presupuesto_aprobado_pr_nombre_accion_especifica', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_nombre_partida',index:'presupuesto_aprobado_pr_nombre_partida', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_ano',index:'presupuesto_aprobado_pr_ano', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_enero',index:'presupuesto_aprobado_pr_enero', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_febrero',index:'presupuesto_aprobado_pr_febrero', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_marzo',index:'presupuesto_aprobado_pr_marzo', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_abril',index:'presupuesto_aprobado_pr_abril', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_mayo',index:'presupuesto_aprobado_pr_mayo', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_junio',index:'presupuesto_aprobado_pr_junio', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_julio',index:'presupuesto_aprobado_pr_julio', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_agosto',index:'presupuesto_aprobado_pr_agosto', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_septiembre',index:'presupuesto_aprobado_pr_septiembre', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_octubre',index:'presupuesto_aprobado_pr_octubre', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_noviembre',index:'presupuesto_aprobado_pr_noviembre', width:50,hidden:true},
			{name:'presupuesto_aprobado_pr_diciembre',index:'presupuesto_aprobado_pr_diciembre', width:50,hidden:true}
   	],
   	rowNum:15,
   	rowList:[15,30,45],
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_presupuesto_aprobado'),
   	sortname: 'id_presupuesto_ley',
    viewrecords: true,
    sortorder: "asc",
	//multiselect: true,
	//multikey: "ctrlKey",
//	footerrow : true,
//	userDataOnFooter : true,
	onSelectRow: function(id){
		s = jQuery("#list_presupuesto_aprobado").getGridParam('selarrrow');
		//alert(s);
		getObj('presupuesto_aprobado_pr_cot_select').value = s;
	}
}).navGrid("#pager_presupuesto_aprobado",{search :false,edit:false,add:false,del:false})
.navButtonAdd('#pager_presupuesto_aprobado',{caption:"Editar",
	onClickButton:function(){
		var gsr = jQuery("#list_presupuesto_aprobado").getGridParam('selrow');
		if(gsr){
			//alert(gsr);
			jQuery("#list_presupuesto_aprobado").GridToForm(gsr,"#form_presupuesto_aprobado");
		} else {
			alert("Por Favor Seleccione una Linea")
		}							
} 
});
//******************************************************************************************************************
var timeoutHndd; 
var flAutoo = true;

function anio_doSearch(ev)
{ 
	if(!flAutoo) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHndd) 
		clearTimeout(timeoutHndd) 
		timeoutHndd = setTimeout(anio_gridReload,500)
} 

function anio_gridReload()
{ 
	var presupuesto_aprobado_pr_ano = jQuery("#presupuesto_aprobado_pr_ano").val(); 
	//var busqueda_codigo = jQuery("#busqueda_codigo").val(); 
	jQuery("#list_presupuesto_aprobado").setGridParam({url:"modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?busqueda_anio="+presupuesto_aprobado_pr_ano,page:1}).trigger("reloadGrid"); 
} 
//******************************************************************************************************************

var timeoutHnd; 
var flAuto = true;

function proyecto_doSearch(ev)
{ 
	if(!flAuto) return; 
 var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(proyecto_gridReload,150)
} 
//*********************************************************************************************************************

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$("#presupuesto_aprobado_pr_btn_consultar_unidad").click(function() {
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
					var presupuesto_aprobado_pr_ano = jQuery("#presupuesto_aprobado_pr_ano").val(); 
					
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/cmb.sql.unidad.php?busq_nombre="+busq_nombre+"&busqueda_anio="+presupuesto_aprobado_pr_ano,page:1}).trigger("reloadGrid"); 
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
							var presupuesto_aprobado_pr_ano = jQuery("#presupuesto_aprobado_pr_ano").val(); 
							
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/cmb.sql.unidad.php?busq_nombre="+busq_nombre+"&busqueda_anio="+presupuesto_aprobado_pr_ano,page:1}).trigger("reloadGrid");
							
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
								var presupuesto_aprobado_pr_ano = jQuery("#presupuesto_aprobado_pr_ano").val(); 
									getObj('presupuesto_aprobado_pr_id_unidad').value = ret.id;
									getObj('presupuesto_aprobado_pr_codigo_unidad').value = ret.codigo;
									getObj('presupuesto_aprobado_pr_nombre_unidad').value = ret.nombre;
									dialog.hideAndUnload();
									jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?unidad='+ret.id+"&busqueda_anio="+presupuesto_aprobado_pr_ano,page:1}).trigger("reloadGrid");
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
            data:dataForm('form_presupuesto_aprobado'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_aprobado_pr_id_unidad').value = recordset[0];
				getObj('presupuesto_aprobado_pr_nombre_unidad').value=recordset[1];
				var presupuesto_aprobado_pr_ano = jQuery("#presupuesto_aprobado_pr_ano").val(); 
				//alert('modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?unidad='+recordset[0]);
				jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?unidad='+recordset[0]+"&busqueda_anio="+presupuesto_aprobado_pr_ano,page:1}).trigger("reloadGrid");
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('presupuesto_aprobado_pr_id_unidad').value = "";
				getObj('presupuesto_aprobado_pr_nombre_unidad').value="";		
				}
			 }
		});	 	 
}
//***********************************************************************************************************************
$("#presupuesto_aprobado_pr_btn_consultar_accion_c").click(function() {
if(getObj('presupuesto_aprobado_pr_id_proyecto').value =="")
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
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.accion_central.php?nd='+nd+'&ano='+getObj('presupuesto_aprobado_pr_ano').value,
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
								var presupuesto_aprobado_pr_ano = jQuery("#presupuesto_aprobado_pr_ano").val(); 
									getObj('presupuesto_aprobado_pr_id_accion_c').value = ret.id;
									getObj('presupuesto_aprobado_pr_codigo_accion_central').value = ret.codigo;
									getObj('presupuesto_aprobado_pr_nombre_accion_central').value = ret.denominacion;
									getObj('presupuesto_aprobado_pr_nombre_proyecto').value="  NO APLICA ESTA OPCION  ";
									getObj('presupuesto_aprobado_pr_codigo_proyecto').value ="0000" ;
									getObj('presupuesto_aprobado_pr_codigo_proyecto').disabled="disabled" ;
									getObj('presupuesto_aprobado_pr_id_proyecto').value="";
									//jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?proyecto='+ret.id,page:1}).trigger("reloadGrid");
									jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?central='+ret.id+'&unidad='+getObj('presupuesto_aprobado_pr_id_unidad').value+"&busqueda_anio="+presupuesto_aprobado_pr_ano,page:1}).trigger("reloadGrid");
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
            data:dataForm('form_presupuesto_aprobado'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_aprobado_pr_id_accion_c').value = recordset[0];
				getObj('presupuesto_aprobado_pr_nombre_accion_central').value=recordset[1];
				getObj('presupuesto_aprobado_pr_id_proyecto').value="";
				getObj('presupuesto_aprobado_pr_nombre_proyecto').value="  NO APLICA ESTA OPCION  ";
				getObj('presupuesto_aprobado_pr_codigo_proyecto').value ="0000" ;
				getObj('presupuesto_aprobado_pr_codigo_proyecto').disabled="disabled" ;
				var presupuesto_aprobado_pr_ano = jQuery("#presupuesto_aprobado_pr_ano").val();
				jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?central='+recordset[0]+'&unidad='+getObj('presupuesto_aprobado_pr_id_unidad').value+"&busqueda_anio="+presupuesto_aprobado_pr_ano,page:1}).trigger("reloadGrid");
				}
				else
			 {  
			   	getObj('presupuesto_aprobado_pr_id_accion_c').value ="";
			    getObj('presupuesto_aprobado_pr_nombre_accion_central').value="";
				getObj('presupuesto_aprobado_pr_id_proyecto').value="";
				getObj('presupuesto_aprobado_pr_nombre_proyecto').value="";
				getObj('presupuesto_aprobado_pr_codigo_proyecto').value ="" ;
				getObj('presupuesto_aprobado_pr_codigo_proyecto').disabled="" ;
				}
			 }
		});	 	 
}
//***********************************************************************************************************************
$("#presupuesto_aprobado_pr_btn_consultar_proyecto").click(function() {
if(getObj('presupuesto_aprobado_pr_id_accion_c').value =="")
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
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.proyecto.php?nd='+nd+'&ano='+getObj('presupuesto_aprobado_pr_ano').value,
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
								var presupuesto_aprobado_pr_ano = jQuery("#presupuesto_aprobado_pr_ano").val();
									getObj('presupuesto_aprobado_pr_id_proyecto').value = ret.id;
									getObj('presupuesto_aprobado_pr_codigo_proyecto').value = ret.codigo;
									getObj('presupuesto_aprobado_pr_nombre_proyecto').value = ret.denominacion;
									getObj('presupuesto_aprobado_pr_nombre_accion_central').value="  NO APLICA ESTA OPCION  ";
									getObj('presupuesto_aprobado_pr_codigo_accion_central').value ="0000" ;
									getObj('presupuesto_aprobado_pr_codigo_accion_central').disabled="disabled" ;
									jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?proyecto='+ret.id+'&unidad='+getObj('presupuesto_aprobado_pr_id_unidad').value+"&busqueda_anio="+presupuesto_aprobado_pr_ano,page:1}).trigger("reloadGrid");
									//alert('modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?proyecto='+ret.id+'&unidad='+getObj('presupuesto_aprobado_pr_id_unidad').value);
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
            data:dataForm('form_presupuesto_aprobado'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_aprobado_pr_id_proyecto').value = recordset[0];
				getObj('presupuesto_aprobado_pr_nombre_proyecto').value=recordset[1];
				getObj('presupuesto_aprobado_pr_id_accion_c').value="";
				getObj('presupuesto_aprobado_pr_nombre_accion_central').value="  NO APLICA ESTA OPCION  ";
				getObj('presupuesto_aprobado_pr_codigo_accion_central').value ="0000" ;
				getObj('presupuesto_aprobado_pr_codigo_accion_central').disabled="disabled" ;
				var presupuesto_aprobado_pr_ano = jQuery("#presupuesto_aprobado_pr_ano").val();
				jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?proyecto='+recordset[0]+'&unidad='+getObj('presupuesto_aprobado_pr_id_unidad').value+"&busqueda_anio="+presupuesto_aprobado_pr_ano,page:1}).trigger("reloadGrid");
				}
				else
			 {  
			   	getObj('presupuesto_aprobado_pr_id_proyecto').value ="";
			    getObj('presupuesto_aprobado_pr_nombre_proyecto').value="";
				getObj('presupuesto_aprobado_pr_nombre_accion_central').value="";
				getObj('presupuesto_aprobado_pr_codigo_accion_central').value ="" ;
				getObj('presupuesto_aprobado_pr_id_accion_c').value ="";
				getObj('presupuesto_aprobado_pr_codigo_accion_central').disabled="" ;
				}
			 }
		});	 	 
}
//***********************************************************************************************************************
function consulta_automatica_especifica_aprobado()
{
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_accion_especifica.php",
            data:dataForm('form_presupuesto_aprobado'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_aprobado_pr_id_accion_e').value = recordset[0];
				getObj('presupuesto_aprobado_pr_nombre_accion_especifica').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('presupuesto_aprobado_pr_id_accion_e').value ="";
			    getObj('presupuesto_aprobado_pr_nombre_accion_especifica').value="";
				}
			 }
		});	 	 
}
// -----
$("#presupuesto_aprobado_pr_btn_consultar_accion_e").click(function() {
if(getObj('presupuesto_aprobado_pr_id_accion_c').value !="" || getObj('presupuesto_aprobado_pr_id_proyecto').value !="")
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
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.accion_especifica.php?nd='+nd+"&proyecto="+getObj('presupuesto_aprobado_pr_id_proyecto').value+"&accion_central="+getObj('presupuesto_aprobado_pr_id_accion_c').value,
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
									getObj('presupuesto_aprobado_pr_id_accion_e').value = ret.id;
									getObj('presupuesto_aprobado_pr_codigo_accion_especifica').value = ret.codigo;
									getObj('presupuesto_aprobado_pr_nombre_accion_especifica').value = ret.denominacion;
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
alert('aqui');
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/pr/sql.partida.php",
            data:dataForm('form_presupuesto_aprobado'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_aprobado_pr_partida').value = recordset[0];
				getObj('presupuesto_aprobado_pr_nombre_partida').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('presupuesto_aprobado_pr_partida').value = "";
				getObj('presupuesto_aprobado_pr_nombre_partida').value="";		
				}
			 }
		});	
}
//***********************************************************************************************************************

$("#presupuesto_aprobado_pr_btn_cancelar").click(function() {
	jQuery("#list_presupuesto_aprobado").setGridParam({url:"modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?"}).trigger("reloadGrid");

	clearForm('form_presupuesto_aprobado');
	getObj('presupuesto_aprobado_pr_enero').value ='0,00';
	getObj('presupuesto_aprobado_pr_febrero').value ='0,00';
	getObj('presupuesto_aprobado_pr_marzo').value ='0,00';
	getObj('presupuesto_aprobado_pr_abril').value ='0,00';
	getObj('presupuesto_aprobado_pr_mayo').value ='0,00';
	getObj('presupuesto_aprobado_pr_junio').value ='0,00';
	getObj('presupuesto_aprobado_pr_julio').value ='0,00';
	getObj('presupuesto_aprobado_pr_agosto').value ='0,00';
	getObj('presupuesto_aprobado_pr_septiembre').value ='0,00';
	getObj('presupuesto_aprobado_pr_octubre').value ='0,00';
	getObj('presupuesto_aprobado_pr_noviembre').value ='0,00';
	getObj('presupuesto_aprobado_pr_diciembre').value ='0,00';
	getObj('presupuesto_aprobado_pr_mono_total').value ='0,00';
} );
//***********************************************************************************************************************
//***********************************************************************************************************************
// -----------------------------------------------------------------------------------------------------------------------------------

$('#presupuesto_aprobado_pr_codigo_unidad').change(consulta_automatica_unidad_ejecutora);
$('#presupuesto_aprobado_pr_codigo_accion_central').change(consulta_automatica_accion_central);
$('#presupuesto_aprobado_pr_codigo_proyecto').change(consulta_automatica_proyecto);
$('#presupuesto_aprobado_pr_partida').change(consulta_automatica_partida_numero_aprobado);
$('#presupuesto_aprobado_pr_codigo_accion_especifica').change(consulta_automatica_especifica_aprobado);

//***********************************************************************************************************************
//alert('llego');
function suma()
{ 
	
	
	valor=getObj('presupuesto_aprobado_pr_enero').value.float() + getObj('presupuesto_aprobado_pr_febrero').value.float() + getObj('presupuesto_aprobado_pr_marzo').value.float() + getObj('presupuesto_aprobado_pr_abril').value.float() + getObj('presupuesto_aprobado_pr_mayo').value.float() + getObj('presupuesto_aprobado_pr_junio').value.float() + getObj('presupuesto_aprobado_pr_julio').value.float() + getObj('presupuesto_aprobado_pr_agosto').value.float() + getObj('presupuesto_aprobado_pr_septiembre').value.float() + getObj('presupuesto_aprobado_pr_octubre').value.float() + getObj('presupuesto_aprobado_pr_noviembre').value.float() + getObj('presupuesto_aprobado_pr_diciembre').value.float();
	
	//valor='999999.58';
	valor = valor.currency(2,',','.');	
		
	getObj('presupuesto_aprobado_pr_mono_total').value = valor;

}
</script>
<div id="botonera">
	<img id="presupuesto_aprobado_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<!--<img id="presupuesto_aprobado_pr_btn_pdf" class="btn_imprimir"src="imagenes/null.gif"  />		
	<img id="presupuesto_aprobado_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>-->
	<img id="presupuesto_aprobado_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>
<form name="form_presupuesto_aprobado" id="form_presupuesto_aprobado">
<input type="hidden" name="presupuesto_aprobado_pr_cot_select" id="presupuesto_aprobado_pr_cot_select" />
	<table  class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Presupuesto de Ley</th>
		</tr>
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select name="presupuesto_aprobado_pr_ano" id="presupuesto_aprobado_pr_ano" onchange="anio_doSearch(arguments[0]||event)">
					<option value="2010" selected="selected">2010</option>
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
						<td><input type="text" size="6" maxlength="6" name="presupuesto_aprobado_pr_codigo_unidad" id="presupuesto_aprobado_pr_codigo_unidad" ></td>
						<td><input type="text" size="90" maxlength="100" name="presupuesto_aprobado_pr_nombre_unidad" id="presupuesto_aprobado_pr_nombre_unidad" readonly></td>
						<td>
							<img id="presupuesto_aprobado_pr_btn_consultar_unidad" class="btn_consulta_emergente"  src="imagenes/null.gif" />
							<input type="hidden" name="presupuesto_aprobado_pr_id_unidad" id="presupuesto_aprobado_pr_id_unidad">
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
						<td><input type="text" size="6" maxlength="6" name="presupuesto_aprobado_pr_codigo_accion_central" id="presupuesto_aprobado_pr_codigo_accion_central" ></td>
						<td><input type="text" size="90" maxlength="100" name="presupuesto_aprobado_pr_nombre_accion_central" id="presupuesto_aprobado_pr_nombre_accion_central" readonly></td>
						<td><img class="btn_consulta_emergente" id="presupuesto_aprobado_pr_btn_consultar_accion_c" src="imagenes/null.gif" />
							<input type="hidden" name="presupuesto_aprobado_pr_id_accion_c" id="presupuesto_aprobado_pr_id_accion_c">
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
						<td><input type="text" size="6" maxlength="6" name="presupuesto_aprobado_pr_codigo_proyecto" id="presupuesto_aprobado_pr_codigo_proyecto" ></td>
						<td><input type="text" size="90" maxlength="100" name="presupuesto_aprobado_pr_nombre_proyecto" id="presupuesto_aprobado_pr_nombre_proyecto" readonly></td>
						<td><img class="btn_consulta_emergente" id="presupuesto_aprobado_pr_btn_consultar_proyecto" src="imagenes/null.gif" />
							<input type="hidden" name="presupuesto_aprobado_pr_id_proyecto" id="presupuesto_aprobado_pr_id_proyecto">
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
						<td><input type="text" size="6" maxlength="6" name="presupuesto_aprobado_pr_codigo_accion_especifica" id="presupuesto_aprobado_pr_codigo_accion_especifica" onchange="consulta_automatica_especifica_aprobado" onclick="consulta_automatica_especifica_aprobado"></td>
						<td><input type="text" size="90" maxlength="100" name="presupuesto_aprobado_pr_nombre_accion_especifica" id="presupuesto_aprobado_pr_nombre_accion_especifica" readonly></td>
						<td><img class="btn_consulta_emergente" id="presupuesto_aprobado_pr_btn_consultar_accion_e" src="imagenes/null.gif" />
							<input type="hidden" name="presupuesto_aprobado_pr_id_accion_e" id="presupuesto_aprobado_pr_id_accion_e">
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
						<td><input type="text" size="12" maxlength="12" name="presupuesto_aprobado_pr_partida" id="presupuesto_aprobado_pr_partida" 
		onchange="consulta_automatica_partida_numero_aprobado" onclick="consulta_automatica_partida_numero_aprobado"	></td>
						<td><input type="text" size="83" maxlength="100" name="presupuesto_aprobado_pr_nombre_partida" id="presupuesto_aprobado_pr_nombre_partida" readonly></td>
						<td><img class="btn_consulta_emergente" id="presupuesto_aprobado_pr_btn_consultar_partida" src="imagenes/null.gif" />
							<input type="hidden" name="presupuesto_aprobado_pr_id_partida" id="presupuesto_aprobado_pr_id_partida">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th colspan="2">
				<table width="100%" class="clear">
					<tr style="text-align:center" >
						<th align="center">Enero</th>
						<th align="center">Febrero</th>
						<th align="center">Marzo</th>
						<th align="center">Abril</th>
						<th align="center">Mayo</th>
						<th align="center">Junio</th>
					</tr>
					<tr>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_enero" id="presupuesto_aprobado_pr_enero"></td>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_febrero" id="presupuesto_aprobado_pr_febrero"></td>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_marzo" id="presupuesto_aprobado_pr_marzo"></td>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_abril" id="presupuesto_aprobado_pr_abril"></td>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_mayo" id="presupuesto_aprobado_pr_mayo"></td>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_junio" id="presupuesto_aprobado_pr_junio"></td>
					</tr>
					<tr style="text-align:center" >
						<th align="center">Julio</th>
						<th align="center">Agosto</th>
						<th align="center">Septiembre</th>
						<th align="center">Octubre</th>
						<th align="center">Noviembre</th>
						<th align="center">Diciembre</th>
					</tr>
					<tr>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_julio" id="presupuesto_aprobado_pr_julio"></td>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_agosto" id="presupuesto_aprobado_pr_agosto"></td>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_septiembre" id="presupuesto_aprobado_pr_septiembre"></td>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_octubre" id="presupuesto_aprobado_pr_octubre"></td>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_noviembre" id="presupuesto_aprobado_pr_noviembre"></td>
						<td><input type="text" size="15" maxlength="15" value="0,00" style="text-align:right" alt="signed-decimal" onblur="suma()" name="presupuesto_aprobado_pr_diciembre" id="presupuesto_aprobado_pr_diciembre"></td>
					</tr>
					<tr>
						<th valign="middle" style="vertical-align:middle">Monto Total</th>
						<td colspan="4"><input type="text" size="16" maxlength="16" value="0,00" style="text-align:right" alt="signed-decimal" name="presupuesto_aprobado_pr_mono_total" id="presupuesto_aprobado_pr_mono_total"></td>
						<td><img id="presupuesto_aprobado_pr_btn_anadir" src="imagenes/actuliza40.png"   /></td>
					</tr>
				</table>
			</th>
		</tr>
		<!--<tr>
			<td class="celda_consulta" colspan="2">
				<table id="list_presupuesto_aprobado" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_presupuesto_aprobado" class="scroll" style="text-align:center;"></div> 
				<br />
			</td>
		</tr>-->
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>	

	</table>
</form>