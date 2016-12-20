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
<script>
//----------------------------------------------------------------
$("#presupuesto_traspaso_pr_btn_anadir").click(function() { 
		//setBarraEstado(mensaje[esperando_respuesta]);
		/*if(getObj("ordenes_pr_nro_pre_orden").value ==""){
			url = "modulos/adquisiones/orden/pr/sql.actulizar.php";
		}else if(getObj("ordenes_pr_nro_pre_orden").value !=""){*/
			url = "modulos/presupuesto/traspaso_entre_partida/pr/sql.traspaso_entre_partida.php";
		//}//alert(url) ;
		/*monto1 = getObj("ordenes_pr_monto").value;
		monto1= monto1.replace('.','');
		monto1= eval(monto1.replace(',','.'));
		monto2 = getObj("ordenes_pr_mon").value;
		monto2= monto2.replace('.','');
		monto2= eval(monto2.replace(',','.'));
		
		cantidad1 = getObj("ordenes_pr_cantidad").value;
		cantidad1= eval(cantidad1.replace(",","."));
		cantidad2 = getObj("ordenes_pr_can").value;
		cantidad2= eval(cantidad2.replace(",","."));*/
		/*if(cantidad1 < cantidad2) {
		if( monto1< monto2){*/
		//alert(cantidad1+' '+ cantidad2) ;
		$.ajax (
		{
		url: url ,
			data:dataForm('formulario_traspaso'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					jQuery("#list_presupuesto_traspaso").setGridParam({url:'modulos/presupuesto/traspaso_entre_partida/pr/cmd.sql.traspaso2.php?precompromiso='+getObj("traspaso_entre_partida_pr_precopromiso").value,page:1}).trigger("reloadGrid");

					url="pdf.php?p=modulos/presupuesto/traspaso_entre_partida/rp/vista.lst.traspaso.php¿precopromiso="+getObj('traspaso_entre_partida_pr_precopromiso').value; 
					Boxy.ask("<iframe style='width:0px; height:0px; border:0px' src="+url+" ></iframe><div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REALIZANDO IMPRESIÓN</p></div>", ["CERRAR"], 
					function(val)
					 {
                		if(val=="CERRAR")
						{
							setTimeout("limpiar()",200);
						}   
					 }, {title:"SAI-OCHINA"});
					 
					 
					getObj("presupuesto_traspaso_pr_codigo_partida_receptor").value="";
					getObj("presupuesto_traspaso_pr_partida_receptor").value="";
					getObj("presupuesto_traspaso_pr_id_partida_receptor").value="";
					getObj("presupuesto_traspaso_pr_mes_cendente").value="";
					getObj("presupuesto_traspaso_pr_mes_receptor").value="";
					getObj("presupuesto_traspaso_pr_monto").value="0,00";
					getObj("presupuesto_traspaso_pr_monto_cedente").value="0,00";
					getObj("presupuesto_traspaso_pr_monto_total").value="0,00";
					getObj("presupuesto_traspaso_pr_monto_actual_receptor").value="0,00";
					getObj("presupuesto_traspaso_pr_monto_total_receptor").value="0,00";
					
					setBarraEstado(mensaje[registro_exitoso],true,true);
					
					//clearForm('form_ordenes');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				setBarraEstado(html);
				}
			}
		});
		/*}else{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL MONTO DEBE SER MENOR A LA QUE TIENE ACTUALMENTE</p></div>",true,true);
		}
		}else{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA CANTIDA DEBE SER MENOR A LA QUE TIENE ACTUALMENTE</p></div>",true,true);
		}*/
});

//***************************************************************************************************************
$("#list_presupuesto_traspaso").jqGrid({
	height: 130,
	width: 720,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",

	url:'modulos/presupuesto/traspaso_entre_partida/pr/cmd.sql.traspaso2.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Partida','Monto','Presupuesto','Precomprometido','Faltante','clasificador_presupuestario'],
   	colModel:[
			{name:'presupuesto_traspaso_pr_codigo_partida_receptor',index:'presupuesto_traspaso_pr_codigo_partida_receptor', width:40, align:"right"},
	   		{name:'monto',index:'monto', width:50, align:"right"},
			{name:'presupuesto',index:'presupuesto', width:50, align:"right"},
			{name:'precomprometido',index:'precomprometido', width:50, align:"right"},
			{name:'falta',index:'falta', width:50, align:"right"},
			{name:'presupuesto_traspaso_pr_partida_receptor',index:'presupuesto_traspaso_pr_partida_receptor', width:10,hidden:true}

   	],
   	rowNum:15,
   	rowList:[15,30,45],
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_presupuesto_traspaso'),
   	sortname: 'partida',
    viewrecords: true,
    sortorder: "asc",
	//multiselect: true,
	//multikey: "ctrlKey",
//	footerrow : true,
//	userDataOnFooter : true,
	onSelectRow: function(id){
		s = jQuery("#list_presupuesto_traspaso").getGridParam('selarrrow');
		//alert(s);
		getObj('presupuesto_aprobado_pr_cot_select').value = s;
	}
}).navGrid("#pager_presupuesto_traspaso",{search :false,edit:false,add:false,del:false})
.navButtonAdd('#pager_presupuesto_traspaso',{caption:"Editar",
	onClickButton:function(){
		var gsr = jQuery("#list_presupuesto_traspaso").getGridParam('selrow');
		if(gsr){
			//alert(gsr);
			jQuery("#list_presupuesto_traspaso").GridToForm(gsr,"#formulario_traspaso");
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
		timeoutHndd = setTimeout(anio_gridReload,100)
} 

function anio_gridReload()
{ 
	var presupuesto_aprobado_pr_ano = jQuery("#presupuesto_aprobado_pr_ano").val(); 
	//var busqueda_codigo = jQuery("#busqueda_codigo").val(); 
	jQuery("#list_presupuesto_traspaso").setGridParam({url:"modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?busqueda_anio="+presupuesto_aprobado_pr_ano,page:1}).trigger("reloadGrid"); 
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
//----------------------------------------------------------------------------------------------------------------------------

$("#traspaso_entre_partida_pr_btn_consultar_precopromiso").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:450,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/traspaso_entre_partida/pr/cmd.sql.pre_compromiso.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Pre-compromiso','id_unidad','codigo','unidad','id_especifica','codigo_especifica','especifica','tipo','id_acc_pro','codigo_acc_pro','acc_pro'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'precompromiso',index:'precompromiso', width:50,sortable:false,resizable:false},
									{name:'id_unidad',index:'id_unidad', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'unidad',index:'unidad', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_especifica',index:'id_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_especifica',index:'codigo_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'especifica',index:'especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_acc_pro',index:'id_acc_pro', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_acc_pro',index:'codigo_acc_pro', width:50,sortable:false,resizable:false,hidden:true},
									{name:'acc_pro',index:'acc_pro', width:50,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('traspaso_entre_partida_pr_precopromiso_id').value = ret.id;
									getObj('traspaso_entre_partida_pr_precopromiso').value = ret.precompromiso;
									getObj('presupuesto_traspaso_pr_id_unidad_cedente').value = ret.id_unidad;
									getObj('presupuesto_traspaso_pr_codigo_unidad_cedente').value = ret.codigo;
									getObj('presupuesto_traspaso_pr_unidad_cedente').value = ret.unidad;
									getObj('presupuesto_traspaso_pr_id_especifica_cedente').value = ret.id_especifica;
									getObj('presupuesto_traspaso_pr_codigo_especifica_cedente').value = ret.codigo_especifica;
									getObj('presupuesto_traspaso_pr_especifica_cedente').value = ret.especifica;
									getObj('presupuesto_traspaso_pr_id_especifica_receptor').value = ret.id_especifica;
									getObj('presupuesto_traspaso_pr_codigo_especifica_receptor').value = ret.codigo_especifica;
									getObj('presupuesto_traspaso_pr_especifica_receptor').value = ret.especifica;
									if(ret.tipo ==1){
										getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value = ret.id_acc_pro;
										getObj('presupuesto_traspaso_pr_codigo_proyecto_cedente').value = ret.codigo_acc_pro;
										getObj('presupuesto_traspaso_pr_proyecto_cedente').value = ret.acc_pro;
										getObj('presupuesto_traspaso_pr_id_central_cedente').value = 0;
										getObj('presupuesto_traspaso_pr_codigo_central_cedente').value = '0000';
										getObj('presupuesto_traspaso_pr_central_cedente').value = 'NO APLICA';
									}else{
										getObj('presupuesto_traspaso_pr_id_central_cedente').value = ret.id_acc_pro;
										getObj('presupuesto_traspaso_pr_codigo_central_cedente').value = ret.codigo_acc_pro;
										getObj('presupuesto_traspaso_pr_central_cedente').value = ret.acc_pro;
										getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value = 0;
										getObj('presupuesto_traspaso_pr_codigo_proyecto_cedente').value = '0000';
										getObj('presupuesto_traspaso_pr_proyecto_cedente').value = 'NO APLICA';
									}
					jQuery("#list_presupuesto_traspaso").setGridParam({url:'modulos/presupuesto/traspaso_entre_partida/pr/cmd.sql.traspaso2.php?precompromiso='+getObj("traspaso_entre_partida_pr_precopromiso").value,page:1}).trigger("reloadGrid");
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
								sortname: 'id_orden_compra_servicioe',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//----------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------

$("#presupuesto_traspaso_pr_btn_consultar_unidad_cedente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:450,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.unidad_ejecutora.php?nd='+nd+'&ano='+getObj('presupuesto_traspaso_pr_ano').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Unidad Ejecutora'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:350,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_id_unidad_cedente').value = ret.id;
									getObj('presupuesto_traspaso_pr_codigo_unidad_cedente').value = ret.codigo;
									getObj('presupuesto_traspaso_pr_unidad_cedente').value = ret.nombre;
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
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
function consulta_automatica_unidad()
{ 
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/pr/sql_grid_unidad.php",
            data:dataForm('formulario_traspaso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_traspaso_pr_id_unidad_cedente').value = recordset[0]; 
				getObj('presupuesto_traspaso_pr_unidad_cedente').value=recordset[1];
				}
				else
			 {  
			   	getObj('presupuesto_traspaso_pr_id_unidad_cedente').value ="";
			    getObj('presupuesto_traspaso_pr_unidad_cedente').value="";
				}
			 }
		});	 	 
}
// -----------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------ Accion Central -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------

$("#presupuesto_traspaso_pr_btn_consultar_central_cedente").click(function() {
if(getObj('presupuesto_traspaso_pr_id_unidad_cedente').value  !=0 && getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
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
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_central.php?nd='+nd+'&unidad='+getObj('presupuesto_traspaso_pr_id_unidad_cedente').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Accion Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_id_central_cedente').value = ret.id;
									getObj('presupuesto_traspaso_pr_codigo_central_cedente').value = ret.codigo;
									getObj('presupuesto_traspaso_pr_central_cedente').value = ret.denominacion;
									getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value = "";
									getObj('presupuesto_traspaso_pr_codigo_proyecto_cedente').value = "0000";
									getObj('presupuesto_traspaso_pr_codigo_proyecto_cedente').disabled="disabled" ;
									getObj('presupuesto_traspaso_pr_proyecto_cedente').value = "  NO APLICA ESTA OPCION ";
									
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
								sortname: 'codigo_accion_central',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
function consulta_automatica_accion_central()
{ 
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/pr/sql_grid_accion_cental_codigo.php",
            data:dataForm('formulario_traspaso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_traspaso_pr_id_central_cedente').value = recordset[0];
				getObj('presupuesto_traspaso_pr_central_cedente').value=recordset[1];
				getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value = "";
				getObj('presupuesto_traspaso_pr_codigo_proyecto_cedente').value = "0000";
				getObj('presupuesto_traspaso_pr_codigo_proyecto_cedente').disabled="disabled" ;
				getObj('presupuesto_traspaso_pr_proyecto_cedente').value = "  NO APLICA ESTA OPCION ";
				}
				else
			 {  
			   	getObj('presupuesto_traspaso_pr_id_central_cedente').value ="";
				getObj('presupuesto_traspaso_pr_codigo_central_cedente').value ="";
			    getObj('presupuesto_traspaso_pr_central_cedente').value="";
				getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value = "";
				getObj('presupuesto_traspaso_pr_codigo_proyecto_cedente').value = "";
				getObj('presupuesto_traspaso_pr_codigo_proyecto_cedente').disabled="" ;
				getObj('presupuesto_traspaso_pr_proyecto_cedente').value = "";
				}
			 }
		});	 	 
}
//***************************************************************************************************************
// ------------------------------------------------------ PROYECTO -----------------------------------------------------------------------------
$("#presupuesto_traspaso_pr_btn_consultar_proyecto_cedente").click(function() {
//alert('aqui');
if(getObj('presupuesto_traspaso_pr_id_unidad_cedente').value  !=0 && getObj('presupuesto_traspaso_pr_id_central_cedente').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proyecto', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.proyecto.php?nd='+nd+'&unidad='+getObj('presupuesto_traspaso_pr_id_unidad_cedente').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value = ret.id;
									getObj('presupuesto_traspaso_pr_codigo_proyecto_cedente').value = ret.codigo;
									getObj('presupuesto_traspaso_pr_proyecto_cedente').value = ret.denominacion;
									
									getObj('presupuesto_traspaso_pr_id_central_cedente').value = "";
									getObj('presupuesto_traspaso_pr_codigo_central_cedente').value = "0000";
									getObj('presupuesto_traspaso_pr_codigo_central_cedente').disabled="disabled" ;
									getObj('presupuesto_traspaso_pr_central_cedente').value = "  NO APLICA ESTA OPCION ";
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
function consulta_automatica_proyecto()
{
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/pr/sql_grid_proyecto_codigo.php",
            data:dataForm('formulario_traspaso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value = recordset[0];
				getObj('presupuesto_traspaso_pr_proyecto_cedente').value=recordset[1];
				getObj('presupuesto_traspaso_pr_id_central_cedente').value = "";
				getObj('presupuesto_traspaso_pr_codigo_central_cedente').value = "0000";
				getObj('presupuesto_traspaso_pr_codigo_central_cedente').disabled="disabled" ;
				getObj('presupuesto_traspaso_pr_central_cedente').value = "  NO APLICA ESTA OPCION ";
				}
				else
			 {  
			   	getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value ="";
			    getObj('presupuesto_traspaso_pr_codigo_proyecto_cedente').value="";
				 getObj('presupuesto_traspaso_pr_proyecto_cedente').value="";
				getObj('presupuesto_traspaso_pr_codigo_central_cedente').value="";
				getObj('presupuesto_traspaso_pr_central_cedente').value ="" ;
				getObj('presupuesto_traspaso_pr_id_central_cedente').value ="";
				getObj('presupuesto_traspaso_pr_id_central_cedente').disabled="" ;
				}
			 }
		});	 	 
}

//***************************************************************************************************************
//***************************************************************************************************************
// ------------------------------------------------------ Accion Especifica -----------------------------------------------------------------------------

$("#presupuesto_traspaso_pr_btn_consultar_especifica_cedente").click(function() {

if(getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value !="" || getObj('presupuesto_traspaso_pr_id_central_cedente').value !="")
{
	if (getObj('presupuesto_traspaso_pr_id_central_cedente').value !=""){
		urls='modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_especifica_central.php?nd='+nd+'&unidad='+getObj('presupuesto_traspaso_pr_id_unidad_cedente').value+'&accion='+getObj('presupuesto_traspaso_pr_id_central_cedente').value;
	}
	if (getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value !=""){
		urls='modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_especifica_proyecto.php?nd='+nd+'&unidad='+getObj('presupuesto_traspaso_pr_id_unidad_cedente').value+'&proyecto='+getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value;
	}
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Especifica', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
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
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_id_especifica_cedente').value = ret.id;
									getObj('presupuesto_traspaso_pr_codigo_especifica_cedente').value = ret.codigo;
									getObj('presupuesto_traspaso_pr_especifica_cedente').value = ret.denominacion;
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
//****************************************************
function consulta_automatica_especifica()
{
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/pr/sql_grid_accion_especifica.php",
            data:dataForm('formulario_traspaso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_traspaso_pr_id_especifica_cedente').value = recordset[0];
				getObj('presupuesto_traspaso_pr_especifica_cedente').value=recordset[1];
				getObj('presupuesto_traspaso_pr_codigo_especifica_cedente').value=recordset[2];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('presupuesto_traspaso_pr_id_especifica_cedente').value ="";
			    getObj('presupuesto_traspaso_pr_especifica_cedente').value="";
				}
			 }
		});	 	 
}
//*****************************************************************************************************
//*****************************************************************************************************
// -----------------------------------------------------------------------------------------------------------------------------------
$("#presupuesto_traspaso_pr_btn_consultar_partida_cedente").click(function() {
if(getObj('presupuesto_traspaso_pr_id_especifica_cedente').value !="" )
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:450,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.partida.php?nd='+nd+'&unidad='+getObj('presupuesto_traspaso_pr_id_unidad_cedente').value+'&unidad_es='+getObj('presupuesto_traspaso_pr_id_especifica_cedente').value,
								datatype: "json",
								colNames:['Id','Partida', 'Descripci&oacute;n','Monto'],
								colModel:[
									{name:'id_clasi_presu',index:'id_clasi_presu', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false},
									{name:'monto',index:'monto', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_codigo_partida_cedente').value = ret.partida;
									getObj('presupuesto_traspaso_pr_partida_cedente').value = ret.denominacion;
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
								sortname: 'id_clasi_presu',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
//----------------------------------------------------------------------------------------------------------------

//*****************************************************************************************************

$("#presupuesto_traspaso_pr_btn_consultar_mes_cedente").click(function() {
//alert('aqui');
if(getObj('presupuesto_traspaso_pr_id_unidad_cedente').value  !=""  && (getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value !=""  || getObj('presupuesto_traspaso_pr_id_central_cedente').value !="")  && getObj('presupuesto_traspaso_pr_codigo_partida_cedente').value !="")
{
	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
		//alert("modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.monto_mensual.php?unidad="+getObj('presupuesto_traspaso_pr_id_unidad_cedente').value+"&unidad_es="+getObj('presupuesto_traspaso_pr_id_especifica_cedente').value+"&partida_toda="+getObj('presupuesto_traspaso_pr_codigo_partida_cedente').value+"&proyecto="+getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value+"&accion_central="+getObj('presupuesto_traspaso_pr_id_central_cedente').value+"&ano="+getObj('presupuesto_traspaso_pr_ano').value);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url: "modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.monto_mensual.php?unidad="+getObj('presupuesto_traspaso_pr_id_unidad_cedente').value+"&unidad_es="+getObj('presupuesto_traspaso_pr_id_especifica_cedente').value+"&partida_toda="+getObj('presupuesto_traspaso_pr_codigo_partida_cedente').value+"&proyecto="+getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value+"&accion_central="+getObj('presupuesto_traspaso_pr_id_central_cedente').value+"&ano="+getObj('presupuesto_traspaso_pr_ano').value,
								datatype: "json",
								colNames:['id','Mes', 'Presupuesto'],
								colModel:[
									{name:'mes',index:'mes', width:50,sortable:false,resizable:false},
									{name:'mes',index:'mes', width:50,sortable:false,resizable:false},
									{name:'presupuesto',index:'presupuesto', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_mes_cendente').value = ret.mes;
									getObj('presupuesto_traspaso_pr_monto').value = ret.presupuesto;

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
								sortname: 'mes',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	}
});
//////////////**************************************¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬
/////////////*************************************** RECEPTOR |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
//----------------------------------------------------------------------------------------------------------------------------

$("#presupuesto_traspaso_pr_btn_consultar_unidad_receptor").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:450,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.unidad_ejecutora.php?nd='+nd+'&ano='+getObj('presupuesto_traspaso_pr_ano').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Unidad Ejecutora'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:350,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_id_unidad_receptor').value = ret.id;
									getObj('presupuesto_traspaso_pr_codigo_unidad_receptor').value = ret.codigo;
									getObj('presupuesto_traspaso_pr_unidad_receptor').value = ret.nombre;
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
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
function consulta_automatica_unidad_receptor()
{ 
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/pr/sql_grid_unidad_receptor.php",
            data:dataForm('formulario_traspaso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_traspaso_pr_id_unidad_receptor').value = recordset[0]; 
				getObj('presupuesto_traspaso_pr_unidad_receptor').value=recordset[1];
				}
				else
			 {  
			   	getObj('presupuesto_traspaso_pr_id_unidad_receptor').value ="";
			    getObj('presupuesto_traspaso_pr_unidad_receptor').value="";
				}
			 }
		});	 	 
}
// -----------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------ Accion Central -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------

$("#presupuesto_traspaso_pr_btn_consultar_central_receptor").click(function() {
if(getObj('presupuesto_traspaso_pr_id_unidad_receptor').value  !=0 && getObj('presupuesto_traspaso_pr_id_proyecto_receptor').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
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
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_central.php?nd='+nd+'&unidad='+getObj('presupuesto_traspaso_pr_id_unidad_receptor').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Accion Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_id_central_receptor').value = ret.id;
									getObj('presupuesto_traspaso_pr_codigo_central_receptor').value = ret.codigo;
									getObj('presupuesto_traspaso_pr_central_receptor').value = ret.denominacion;
									getObj('presupuesto_traspaso_pr_id_proyecto_receptor').value = "";
									getObj('presupuesto_traspaso_pr_codigo_proyecto_receptor').value = "0000";
									getObj('presupuesto_traspaso_pr_codigo_proyecto_receptor').disabled="disabled" ;
									getObj('presupuesto_traspaso_pr_proyecto_receptor').value = "  NO APLICA ESTA OPCION ";
									
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
								sortname: 'codigo_accion_central',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
function consulta_automatica_accion_central_receptor()
{ 
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/pr/sql_grid_accion_cental_codigo_receptor.php",
            data:dataForm('formulario_traspaso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_traspaso_pr_id_central_receptor').value = recordset[0];
				getObj('presupuesto_traspaso_pr_central_receptor').value=recordset[1];
				getObj('presupuesto_traspaso_pr_id_proyecto_receptor').value = "";
				getObj('presupuesto_traspaso_pr_codigo_proyecto_receptor').value = "0000";
				getObj('presupuesto_traspaso_pr_codigo_proyecto_receptor').disabled="disabled" ;
				getObj('presupuesto_traspaso_pr_proyecto_receptor').value = "  NO APLICA ESTA OPCION ";
				}
				else
			 {  
			   	getObj('presupuesto_traspaso_pr_id_central_receptor').value ="";
				getObj('presupuesto_traspaso_pr_codigo_central_receptor').value ="";
			    getObj('presupuesto_traspaso_pr_central_receptor').value="";
				getObj('presupuesto_traspaso_pr_id_proyecto_receptor').value = "";
				getObj('presupuesto_traspaso_pr_codigo_proyecto_receptor').value = "";
				getObj('presupuesto_traspaso_pr_codigo_proyecto_receptor').disabled="" ;
				getObj('presupuesto_traspaso_pr_proyecto_receptor').value = "";
				}
			 }
		});	 	 
}
//***************************************************************************************************************
// ------------------------------------------------------ PROYECTO -----------------------------------------------------------------------------
$("#presupuesto_traspaso_pr_btn_consultar_proyecto_receptor").click(function() {
//alert('aqui');
if(getObj('presupuesto_traspaso_pr_id_unidad_receptor').value  !=0 && getObj('presupuesto_traspaso_pr_id_central_receptor').value =="")
{
//alert('aqui');
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proyecto', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.proyecto.php?nd='+nd+'&unidad='+getObj('presupuesto_traspaso_pr_id_unidad_receptor').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_id_proyecto_receptor').value = ret.id;
									getObj('presupuesto_traspaso_pr_codigo_proyecto_receptor').value = ret.codigo;
									getObj('presupuesto_traspaso_pr_proyecto_receptor').value = ret.denominacion;
									
									getObj('presupuesto_traspaso_pr_id_central_receptor').value = "";
									getObj('presupuesto_traspaso_pr_codigo_central_receptor').value = "0000";
									getObj('presupuesto_traspaso_pr_codigo_central_receptor').disabled="disabled" ;
									getObj('presupuesto_traspaso_pr_central_receptor').value = "  NO APLICA ESTA OPCION ";
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
function consulta_automatica_proyecto_receptor()
{
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/pr/sql_grid_proyecto_codigo_receptor.php",
            data:dataForm('formulario_traspaso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_traspaso_pr_id_proyecto_receptor').value = recordset[0];
				getObj('presupuesto_traspaso_pr_proyecto_receptor').value=recordset[1];
				getObj('presupuesto_traspaso_pr_id_central_receptor').value = "";
				getObj('presupuesto_traspaso_pr_codigo_central_receptor').value = "0000";
				getObj('presupuesto_traspaso_pr_codigo_central_receptor').disabled="disabled" ;
				getObj('presupuesto_traspaso_pr_central_receptor').value = "  NO APLICA ESTA OPCION ";
				}
				else
			 {  
			   	getObj('presupuesto_traspaso_pr_id_proyecto_receptor').value ="";
			    getObj('presupuesto_traspaso_pr_codigo_proyecto_receptor').value="";
				 getObj('presupuesto_traspaso_pr_proyecto_receptor').value="";
				getObj('presupuesto_traspaso_pr_codigo_central_receptor').value="";
				getObj('presupuesto_traspaso_pr_central_receptor').value ="" ;
				getObj('presupuesto_traspaso_pr_id_central_receptor').value ="";
				getObj('presupuesto_traspaso_pr_id_central_receptor').disabled="" ;
				}
			 }
		});	 	 
}

//***************************************************************************************************************
//***************************************************************************************************************
// ------------------------------------------------------ Accion Especifica -----------------------------------------------------------------------------

$("#presupuesto_traspaso_pr_btn_consultar_especifica_receptor").click(function() {

if(getObj('presupuesto_traspaso_pr_id_proyecto_receptor').value !="" || getObj('presupuesto_traspaso_pr_id_central_receptor').value !="")
{
	if (getObj('presupuesto_traspaso_pr_id_central_receptor').value !=""){
		urls='modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_especifica_central.php?nd='+nd+'&unidad='+getObj('presupuesto_traspaso_pr_id_unidad_receptor').value+'&accion='+getObj('presupuesto_traspaso_pr_id_central_receptor').value;
	}
	if (getObj('presupuesto_traspaso_pr_id_proyecto_receptor').value !=""){
		urls='modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_especifica_proyecto.php?nd='+nd+'&unidad='+getObj('presupuesto_traspaso_pr_id_unidad_receptor').value+'&proyecto='+getObj('presupuesto_traspaso_pr_id_proyecto_receptor').value;
	}
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Especifica', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
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
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_id_especifica_receptor').value = ret.id;
									getObj('presupuesto_traspaso_pr_codigo_especifica_receptor').value = ret.codigo;
									getObj('presupuesto_traspaso_pr_especifica_receptor').value = ret.denominacion;
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
//****************************************************
function consulta_automatica_especifica_receptor()
{
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/pr/sql_grid_accion_especifica_receptor.php",
            data:dataForm('formulario_traspaso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_traspaso_pr_id_especifica_receptor').value = recordset[0];
				getObj('presupuesto_traspaso_pr_especifica_receptor').value=recordset[1];
				getObj('presupuesto_traspaso_pr_codigo_especifica_receptor').value=recordset[2];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('presupuesto_traspaso_pr_id_especifica_receptor').value ="";
			    getObj('presupuesto_traspaso_pr_especifica_receptor').value="";
				}
			 }
		});	 	 
}
//*****************************************************************************************************
//*****************************************************************************************************
// -----------------------------------------------------------------------------------------------------------------------------------
$("#presupuesto_traspaso_pr_btn_consultar_partida_receptor").click(function() {
if(getObj('presupuesto_traspaso_pr_id_especifica_receptor').value !="" )
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:450,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/traspaso_entre_partida/pr/cmd.sql.traspaso_receptor.php?nd='+nd+'&unidad='+getObj('presupuesto_traspaso_pr_id_unidad_receptor').value+'&unidad_es='+getObj('presupuesto_traspaso_pr_id_especifica_receptor').value+'&partida='+getObj('presupuesto_traspaso_pr_codigo_partida_receptor').value,
								datatype: "json",
								colNames:['Id','Partida', 'Descripci&oacute;n'],
								colModel:[
									{name:'id_clasi_presu',index:'id_clasi_presu', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_codigo_partida_receptor').value = ret.partida;
									getObj('presupuesto_traspaso_pr_partida_receptor').value = ret.denominacion;
									getObj('presupuesto_traspaso_pr_id_partida_receptor').value = ret.id_clasi_presu;
									
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
								sortname: 'id_clasi_presu',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
//----------------------------------------------------------------------------------------------------------------

//*****************************************************************************************************

$("#presupuesto_traspaso_pr_btn_consultar_mes_receptor").click(function() {
//alert('aqui');
if(getObj('presupuesto_traspaso_pr_codigo_partida_receptor').value !="")
{
//alert('aqui 1');
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
		//alert("modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.monto_mensual.php?unidad="+getObj('traspaso_entre_partida_db_unidad_ejecutora').value+"&unidad_es="+getObj('traspaso_entre_partida_db_accion_especifica_id').value+"&partida_toda="+getObj('traspaso_entre_partida_db_partida_numero').value+"&proyecto="+getObj('traspaso_entre_partida_db_proyecto_id').value+"&accion_central="+getObj('traspaso_entre_partida_db_accion_central_id').value);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url: "modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.monto_mensual.php?unidad="+getObj('presupuesto_traspaso_pr_id_unidad_cedente').value+"&unidad_es="+getObj('presupuesto_traspaso_pr_id_especifica_receptor').value+"&partida_toda="+getObj('presupuesto_traspaso_pr_codigo_partida_receptor').value+"&proyecto="+getObj('presupuesto_traspaso_pr_id_proyecto_cedente').value+"&accion_central="+getObj('presupuesto_traspaso_pr_id_central_cedente').value+"&ano="+getObj('presupuesto_traspaso_pr_ano').value,
								datatype: "json",
								colNames:['id','Mes', 'Presupuesto'],
								colModel:[
									{name:'mes',index:'mes', width:50,sortable:false,resizable:false},
									{name:'mes',index:'mes', width:50,sortable:false,resizable:false},
									{name:'presupuesto',index:'presupuesto', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('presupuesto_traspaso_pr_mes_receptor').value = ret.mes;
									getObj('presupuesto_traspaso_pr_monto_actual_receptor').value = ret.presupuesto;
									getObj('presupuesto_traspaso_pr_monto_total_receptor').value = eval(getObj('presupuesto_traspaso_pr_monto_actual_receptor').value.float() + getObj('presupuesto_traspaso_pr_monto_cedente').value.float() );
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
								sortname: 'mes',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	}
});
////////////////////////////////////////////////////////////////////////
function montoreceptor()
{
	var monto_total;
	if (getObj('presupuesto_traspaso_pr_monto').value.float() >= getObj('presupuesto_traspaso_pr_monto_cedente').value.float())
	{
		monto_total = getObj('presupuesto_traspaso_pr_monto').value.float() - getObj('presupuesto_traspaso_pr_monto_cedente').value.float();
		monto_total = monto_total.currency(2,',','.');	
		getObj('presupuesto_traspaso_pr_monto_total').value = monto_total;
	}
	else{
		getObj('presupuesto_traspaso_pr_monto_cedente').value="0,00";
		monto_total = getObj('presupuesto_traspaso_pr_monto').value.float();
		monto_total = monto_total.currency(2,',','.');	
		getObj('presupuesto_traspaso_pr_monto_total').value = monto_total;
		setBarraEstado(mensaje[monto_cedente_superior],true,true);
	}
	
}
///////////////////////////////////////////////////////////////////////    
$('#presupuesto_traspaso_pr_codigo_unidad_cedente').change(consulta_automatica_unidad);
$('#presupuesto_traspaso_pr_codigo_central_cedente').change(consulta_automatica_accion_central);
$('#presupuesto_traspaso_pr_codigo_proyecto_cedente').change(consulta_automatica_proyecto);
$('#presupuesto_traspaso_pr_codigo_especifica_cedente').change(consulta_automatica_especifica);

$('#presupuesto_traspaso_pr_codigo_unidad_receptor').change(consulta_automatica_unidad_receptor);
$('#presupuesto_traspaso_pr_codigo_central_receptor').change(consulta_automatica_accion_central_receptor);
$('#presupuesto_traspaso_pr_codigo_proyecto_receptor').change(consulta_automatica_proyecto_receptor);
$('#presupuesto_traspaso_pr_codigo_especifica_receptor').change(consulta_automatica_especifica_receptor);
</script>
<form name="formulario_traspaso" id="formulario_traspaso">
<input type="hidden" name="presupuesto_traspaso_pr_secuencia" id="presupuesto_traspaso_pr_secuencia" />
	<table  class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/proceso28x28.png" style="padding-right:5px;" align="absmiddle" /> Traspaso Entre Partidas </th>
		</tr>
		<tr>
			<th>A&ntilde;o
				<select name="presupuesto_traspaso_pr_ano" id="presupuesto_traspaso_pr_ano" >
					<option value="2011">2011</option>
				</select>
			</th>
			<td align="right">
				<b>Precompromiso</b>&nbsp;&nbsp;
				<input type="hidden" name="traspaso_entre_partida_pr_precopromiso_id" id="traspaso_entre_partida_pr_precopromiso_id" />
				<input type="text" name="traspaso_entre_partida_pr_precopromiso" id="traspaso_entre_partida_pr_precopromiso" />
				<img class="btn_consulta_emergente" id="traspaso_entre_partida_pr_btn_consultar_precopromiso" src="imagenes/null.gif" />
			</td>
		</tr>
		<tr>
			<th colspan="2" align="center" bgcolor="#83B4D8" style="text-align:center;color:#FFFFFF; font-size:14px">Cedente	</th>	
		</tr>
		<tr>
			<th>Unidad Solicitante</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" name="presupuesto_traspaso_pr_codigo_unidad_cedente" id="presupuesto_traspaso_pr_codigo_unidad_cedente" size="6" maxlength="6"></td>
						<td><input type="text" name="presupuesto_traspaso_pr_unidad_cedente" id="presupuesto_traspaso_pr_unidad_cedente" size="90" maxlength="100" readonly></td>
						<td>
							<!--<img id="presupuesto_traspaso_pr_btn_consultar_unidad_cedente" class="btn_consulta_emergente"  src="imagenes/null.gif" />-->
							<input type="hidden" name="presupuesto_traspaso_pr_id_unidad_cedente" id="presupuesto_traspaso_pr_id_unidad_cedente">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Centralizada</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" name="presupuesto_traspaso_pr_codigo_central_cedente" id="presupuesto_traspaso_pr_codigo_central_cedente" size="6" maxlength="6"></td>
						<td><input type="text" name="presupuesto_traspaso_pr_central_cedente" id="presupuesto_traspaso_pr_central_cedente" size="90" maxlength="100" readonly></td>
						<td>
							<!--<img id="presupuesto_traspaso_pr_btn_consultar_central_cedente" class="btn_consulta_emergente"  src="imagenes/null.gif" />-->
							<input type="hidden" name="presupuesto_traspaso_pr_id_central_cedente" id="presupuesto_traspaso_pr_id_central_cedente" >
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Proyecto</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" name="presupuesto_traspaso_pr_codigo_proyecto_cedente" id="presupuesto_traspaso_pr_codigo_proyecto_cedente" size="6" maxlength="6"></td>
						<td><input type="text" name="presupuesto_traspaso_pr_proyecto_cedente" id="presupuesto_traspaso_pr_proyecto_cedente" size="90" maxlength="100" readonly></td>
						<td>
							<!--<img id="presupuesto_traspaso_pr_btn_consultar_proyecto_cedente" class="btn_consulta_emergente"  src="imagenes/null.gif" />-->
							<input type="hidden" name="presupuesto_traspaso_pr_id_proyecto_cedente" id="presupuesto_traspaso_pr_id_proyecto_cedente" >
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Especifica</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" name="presupuesto_traspaso_pr_codigo_especifica_cedente" id="presupuesto_traspaso_pr_codigo_especifica_cedente" size="6" maxlength="6"></td>
						<td><input type="text" name="presupuesto_traspaso_pr_especifica_cedente" id="presupuesto_traspaso_pr_especifica_cedente" size="90" maxlength="100" readonly></td>
						<td>
							<img id="presupuesto_traspaso_pr_btn_consultar_especifica_cedente" class="btn_consulta_emergente"  src="imagenes/null.gif" />
							<input type="hidden" name="presupuesto_traspaso_pr_id_especifica_cedente" id="presupuesto_traspaso_pr_id_especifica_cedente">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Partida</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" name="presupuesto_traspaso_pr_codigo_partida_cedente" id="presupuesto_traspaso_pr_codigo_partida_cedente" size="12" maxlength="12" readonly></td>
						<td><input type="text" name="presupuesto_traspaso_pr_partida_cedente" id="presupuesto_traspaso_pr_partida_cedente" size="83" maxlength="100" readonly></td>
						<td>
							<img id="presupuesto_traspaso_pr_btn_consultar_partida_cedente" class="btn_consulta_emergente"  src="imagenes/null.gif" />
							<input type="hidden" name="presupuesto_traspaso_pr_id_partida_cedente" id="presupuesto_traspaso_pr_id_partida_cedente" >
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Mes Cedente</th>
			<td>
				<input name="presupuesto_traspaso_pr_mes_cendente" type="text" id="presupuesto_traspaso_pr_mes_cendente"  maxlength="12" message="Introduzca el mes cedente."  size="20" readonly >
				<img id="presupuesto_traspaso_pr_btn_consultar_mes_cedente" class="btn_consulta_emergente"  src="imagenes/null.gif" />
			</td>
		</tr>
		<tr>
			<th colspan="2">
				<table  width="100%" border="0">
					<tr>
						<th>Monto Actual</th>
						<td>
							<input  name="presupuesto_traspaso_pr_monto" type="text" id="presupuesto_traspaso_pr_monto"  
							size="15" readonly 	message="Monto Actual del Mes Cedente" 
							onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" value="0,00"  
							jVal="{valid:/^[0123456789,.]{1,15}$/, message:'Monto Invalida', styleType:'cover'}"
							jValKey="{valid:/[0123456789,]/, cFunc:'alert', cArgs:['Monto: '+$(this).val()]}"  style="text-align:right"/>
						</td>
						<th>Monto</th>
						<td>
							<input name="presupuesto_traspaso_pr_monto_cedente" type="text" 
							id="presupuesto_traspaso_pr_monto_cedente" size="15" maxlength="15"  
							message="Introduzca el Monto Cedente." 
							jVal="{valid:/^[0123456789,.-]{1,15}$/, message:'Monto Invalida', styleType:'cover'}"
							jValKey="{valid:/[0123456789,.-]/, cFunc:'alert', cArgs:['Monto: '+$(this).val()]}"
							 alt="decimal" value="0" onkeyup="montoreceptor()" style="text-align:right" />						
						</td>
						<th>Monto Total</th>
						<td>
							<input name="presupuesto_traspaso_pr_monto_total" type="text" 
							id="presupuesto_traspaso_pr_monto_total" size="15"  readonly  style="text-align:right"
							 value="0,00"   alt="decimal"/>
						</td>
					</tr>
				</table>
			</th>
		</tr>
		<tr>
			<th colspan="2" align="center" bgcolor="#83B4D8" style="text-align:center;color:#FFFFFF; font-size:14px">Receptora	</th>	
		</tr>
		<!--<tr>
			<th>Unidad Solicitante</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" name="presupuesto_traspaso_pr_codigo_unidad_receptor" id="presupuesto_traspaso_pr_codigo_unidad_receptor" size="6" maxlength="6"></td>
						<td><input type="text" name="presupuesto_traspaso_pr_unidad_receptor" id="presupuesto_traspaso_pr_unidad_receptor" size="90" maxlength="100" readonly></td>
						<td>
							<img id="presupuesto_traspaso_pr_btn_consultar_unidad_receptor" class="btn_consulta_emergente"  src="imagenes/null.gif" />
							<input type="hidden" name="presupuesto_traspaso_pr_id_unidad_receptor" id="presupuesto_traspaso_pr_id_unidad_receptor">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Centralizada</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" name="presupuesto_traspaso_pr_codigo_central_receptor" id="presupuesto_traspaso_pr_codigo_central_receptor" size="6" maxlength="6"></td>
						<td><input type="text" name="presupuesto_traspaso_pr_central_receptor" id="presupuesto_traspaso_pr_central_receptor" size="90" maxlength="100" readonly></td>
						<td>
							<img id="presupuesto_traspaso_pr_btn_consultar_central_receptor" class="btn_consulta_emergente"  src="imagenes/null.gif" />
							<input type="hidden" name="presupuesto_traspaso_pr_id_central_receptor" id="presupuesto_traspaso_pr_id_central_receptor" >
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Proyecto</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" name="presupuesto_traspaso_pr_codigo_proyecto_receptor" id="presupuesto_traspaso_pr_codigo_proyecto_receptor" size="6" maxlength="6"></td>
						<td><input type="text" name="presupuesto_traspaso_pr_proyecto_receptor" id="presupuesto_traspaso_pr_proyecto_receptor" size="90" maxlength="100" readonly></td>
						<td>
							<img id="presupuesto_traspaso_pr_btn_consultar_proyecto_receptor" class="btn_consulta_emergente"  src="imagenes/null.gif" />
							<input type="hidden" name="presupuesto_traspaso_pr_id_proyecto_receptor" id="presupuesto_traspaso_pr_id_proyecto_receptor" >
						</td>
					</tr>
				</table>
			</td>
		</tr>-->
		<tr>
			<th>Acci&oacute;n Especifica</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" name="presupuesto_traspaso_pr_codigo_especifica_receptor" id="presupuesto_traspaso_pr_codigo_especifica_receptor" size="6" maxlength="6" readonly></td>
						<td><input type="text" name="presupuesto_traspaso_pr_especifica_receptor" id="presupuesto_traspaso_pr_especifica_receptor" size="90" maxlength="100" readonly></td>
						<td>
							<!--<img id="presupuesto_traspaso_pr_btn_consultar_especifica_receptor" class="btn_consulta_emergente"  src="imagenes/null.gif" />-->
							<input type="hidden" name="presupuesto_traspaso_pr_id_especifica_receptor" id="presupuesto_traspaso_pr_id_especifica_receptor">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Partida</th>
			<td>
				<table width="100%" class="clear">
					<tr>
						<td><input type="text" name="presupuesto_traspaso_pr_codigo_partida_receptor" id="presupuesto_traspaso_pr_codigo_partida_receptor" size="12" maxlength="12" readonly></td>
						<td><input type="text" name="presupuesto_traspaso_pr_partida_receptor" id="presupuesto_traspaso_pr_partida_receptor" size="83" maxlength="100" readonly></td>
						<td>
							<!--<img id="presupuesto_traspaso_pr_btn_consultar_partida_receptor" class="btn_consulta_emergente"  src="imagenes/null.gif" />-->
							<input type="hidden" name="presupuesto_traspaso_pr_id_partida_receptor" id="presupuesto_traspaso_pr_id_partida_receptor" >
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Mes Receptor </th>
			<td>
				<input name="presupuesto_traspaso_pr_mes_receptor" type="text" id="presupuesto_traspaso_pr_mes_receptor"  maxlength="12" message="Introduzca el mes cedente."  size="20" readonly >
				<img id="presupuesto_traspaso_pr_btn_consultar_mes_receptor" class="btn_consulta_emergente"  src="imagenes/null.gif" />
			</td>
		</tr>
		<tr>
			<th colspan="2">
				<table  width="100%" border="0">
					<tr>
						<th>Monto Actual</th>
						<td>
							<input  name="presupuesto_traspaso_pr_monto_actual_receptor" type="text" id="presupuesto_traspaso_pr_monto_actual_receptor"  size="13" readonly 
							message="Monto Actual del Mes Receptor"  style="text-align:right"
							jVal="{valid:/^[0123456789,.]{1,15}$/, message:'Monto Invalido', styleType:'cover'}"  
							jValKey="{valid:/[0123456789.,]/, cFunc:'alert', cArgs:['Monto: '+$(this).val()]}" 
							 value="0,00"  />
						</td>
						<th>Monto Total</th>
						<td>
							<input name="presupuesto_traspaso_pr_monto_total_receptor" type="text" id="presupuesto_traspaso_pr_monto_total_receptor" 
							size="15"   style="text-align:right"  value="0,00"/>	
						</td>
					</tr>
				</table>
			</th>
		</tr>
		<tr>
			<th>Comentario</th>
			<td>
				<textarea name="presupuesto_traspaso_pr_comentario" id="presupuesto_traspaso_pr_comentario" cols="65" rows="2" message="Introduzca un Comentario para el Presupuesto."></textarea>
				<img style="vertical-align:middle" id="presupuesto_traspaso_pr_btn_anadir" src="imagenes/actuliza40.png"   />			
			</td>
		</tr>
		<tr>
			<td  class="celda_consulta" colspan="2">
				<table id="list_presupuesto_traspaso" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_presupuesto_traspaso" class="scroll" style="text-align:center;"></div> 
				<br />
			</td>
		</tr>
		
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>