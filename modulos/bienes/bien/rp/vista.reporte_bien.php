<? if (!$_SESSION) session_start();
?>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------
$("#form_reporte_bien_rp_consulta_bien").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/rp/vista.grid_reporte_bien.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Bienes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_codigo= jQuery("#reporte_bien_rp_codigo").val(); 
					var busq_nombre= jQuery("#reporte_bien_rp_nombre").val(); 
					var id_mayor= getObj('form_reporte_bien_rp_id_mayor').value; 
					var id_tipo_bien= getObj('form_reporte_bien_id_tipo_bien').value;
					var id_custodio= getObj('form_reporte_bien_id_custodio').value;
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_bien_nombre.php?busq_nombre="+busq_nombre+"&busq_codigo="+busq_codigo+"&id_mayor="+id_mayor+"&id_tipo_bien="+id_tipo_bien+"&id_custodio="+id_custodio,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#reporte_bien_rp_codigo").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#reporte_bien_rp_nombre").keypress(function(key)
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
							var busq_codigo= jQuery("#reporte_bien_rp_codigo").val();
							var busq_nombre= jQuery("#reporte_bien_rp_nombre").val();
							var id_mayor= getObj('form_reporte_bien_rp_id_mayor').value;
							var id_tipo_bien= getObj('form_reporte_bien_rp_id_tipo_bien').value;
							var id_custodio= getObj('form_reporte_bien_rp_id_custodio').value;
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_bien_nombre.php?busq_nombre="+busq_nombre+"&busq_codigo="+busq_codigo+"&id_mayor="+id_mayor+"&id_tipo_bien="+id_tipo_bien+"&id_custodio="+id_custodio,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/bienes/bien/rp/sql_reporte_bien_nombre.php?id_mayor='+getObj('form_reporte_bien_rp_id_mayor').value+"&id_tipo_bien="+getObj('form_reporte_bien_rp_id_tipo_bien').value+"&id_custodio="+getObj('form_reporte_bien_rp_id_custodio').value,
								datatype: "json",
								colNames:['ID','Codigo','Bien'],
								colModel:[
									{name:'id_bienes',index:'id_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_bienes',index:'codigo_bienes', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('form_reporte_bien_rp_id_bienes').value = ret.id_bienes;
									getObj('form_reporte_bien_rp_nombre_bien').value = ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#valor_impuesto_db_nombre_uni").focus();
								$('#reporte_bien_rp_codigo').alpha({allow:'0123456789 '});
								$('#reporte_bien_rp_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});



//
//


//
//----------------------------------------------------------------



$("#form_reporte_bien_rp_consulta_mayor").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/rp/vista.grid_reporte_mayor.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Mayor', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#reporte_bien_rp_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_mayor_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#reporte_bien_rp_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#reporte_bien_rp_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_mayor_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/bienes/bien/rp/sql_reporte_mayor_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Mayor','Comentario'],
								colModel:[
									{name:'id_mayor',index:'id_mayor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('form_reporte_bien_rp_id_mayor').value=ret.id_mayor;
									getObj('form_reporte_bien_rp_nombre_mayor').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#reporte_bien_rp_nombre").focus();
								$('#reporte_bien_rp_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_mayor',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});


//
//----------------------------------------------------------------

$("#form_reporte_bien_rp_consulta_custodio").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/rp/vista.grid_reporte_custodio.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Custodio', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#reporte_bien_rp_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_mayor_custodio.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#reporte_bien_rp_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#reporte_bien_rp_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_custodio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/bienes/bien/rp/sql_reporte_custodio_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Custodio','Comentario'],
								colModel:[
									{name:'id_custodio',index:'id_custodio', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('form_reporte_bien_rp_id_custodio').value=ret.id_custodio;
									getObj('form_reporte_bien_rp_nombre_custodio').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#reporte_bien_rp_nombre").focus();
								$('#reporte_bien_rp_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_custodio',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//----------------------------------------------------------------

$("#form_reporte_bien_rp_consulta_tipo_bien").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/rp/vista.grid_reporte_tipo_bien.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Bien', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#reporte_bien_rp_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_tipo_bien_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#reporte_bien_rp_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#reporte_bien_rp_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_tipo_bien_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/bienes/bien/rp/sql_reporte_tipo_bien_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Tipo','Comentario'],
								colModel:[
									{name:'id_tipo_bienes',index:'id_tipo_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('form_reporte_bien_rp_id_tipo_bien').value=ret.id_tipo_bienes;
									getObj('form_reporte_bien_rp_nombre_tipo_bien').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#reporte_bien_rp_nombre").focus();
								$('#reporte_bien_rp_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_tipo_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
// ******************************************************************************
$("#reporte_bien_rp_btn_imprimir").click(function() {
	if(getObj('form_reporte_bien_rp_nombre_bien').value==""){
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Debe Seleccionar un Activo!</p></div>",true,true);	
	}
else{
		url="pdfb.php?p=modulos/bienes/bien/rp/vista.lst.bien.php!id_mayor="+getObj('form_reporte_bien_rp_id_mayor').value+"@id_tipo_bien="+getObj('form_reporte_bien_rp_id_tipo_bien').value+"@id_custodio="+getObj('form_reporte_bien_rp_id_custodio').value+"@id_bienes="+getObj('form_reporte_bien_rp_id_bienes').value;
		openTab('RP Tarjeta de Custodia',url);
}
});
// ******************************************************************************
$("#reporte_bien_rp_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
limpiar();
setBarraEstado("");
});
function limpiar(){
	getObj('form_reporte_bien_rp_id_bienes').value='';
	getObj('form_reporte_bien_rp_nombre_bien').value='';
	getObj('form_reporte_bien_rp_id_mayor').value='';
	getObj('form_reporte_bien_rp_nombre_mayor').value='';
	getObj('form_reporte_bien_rp_id_tipo_bien').value='';
	getObj('form_reporte_bien_rp_nombre_tipo_bien').value='';
	getObj('form_reporte_bien_rp_id_custodio').value='';
	getObj('form_reporte_bien_rp_nombre_custodio').value='';
}

$('#sitio_fisico_db_nombre_unidad').alpha({allow:' '});
$('#sitio_fisico_db_nombre_sitio').alpha({allow:' '});
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
	<img id="reporte_bien_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="reporte_bien_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  /></div>
    

<form name="form_db_sitio_fisico" id="form_db_sitio_fisico">
<input type="hidden" name="form_reporte_bien_rp_id_bienes" id="form_reporte_bien_rp_id_bienes" />
<input type="hidden" name="form_reporte_bien_rp_id_mayor" id="form_reporte_bien_rp_id_mayor"/>
<input type="hidden" name="form_reporte_bien_rp_id_tipo_bien" id="form_reporte_bien_rp_id_tipo_bien"/>
<input type="hidden" name="form_reporte_bien_rp_id_custodio" id="form_reporte_bien_rp_id_custodio"/>
<input type="hidden" name="sitio_fisico_db_id_unidad_ejecutora" id="sitio_fisico_db_id_unidad_ejecutora"/>

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Reporte de Bienes			</th>
	</tr>
    <tr style="display:none">
			<th><p>Mayor</p></th>
		  <th><label>
		    <input type="text" name="form_reporte_bien_rp_nombre_mayor" id="form_reporte_bien_rp_nombre_mayor" />
	      </label><img id="form_reporte_bien_rp_consulta_mayor" src="imagenes/iconos/view16x16.png" style="cursor:pointer; overflow:hidden"/></th>
	    </tr>
        <tr style="display:none">
			<th><p>Tipo de Bien</p></th>
		  <th><label>
		    <input type="text" name="form_reporte_bien_rp_nombre_tipo_bien" id="form_reporte_bien_rp_nombre_tipo_bien" />
	      </label><img id="form_reporte_bien_rp_consulta_tipo_bien" src="imagenes/iconos/view16x16.png" style="cursor:pointer; overflow:hidden"/></th>
	    </tr>
        <tr>
			<th><p>Custodio:</p></th>
		  <th><label>
		    <input type="text" name="form_reporte_bien_rp_nombre_custodio" id="form_reporte_bien_rp_nombre_custodio" />
	      </label><img id="form_reporte_bien_rp_consulta_custodio" src="imagenes/iconos/view16x16.png" style="cursor:pointer; overflow:hidden"/></th>
	    </tr>
    <tr>
			<th width="51"><p>Bien:</p></th>
	  <th width="153"><label>
          <input type="text" name="form_reporte_bien_rp_nombre_bien" id="form_reporte_bien_rp_nombre_bien" />
      </label>
      <img id="form_reporte_bien_rp_consulta_bien" src="imagenes/iconos/view16x16.png" style="cursor:pointer; overflow:hidden"/></th>
    </tr>
		<tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>			
  </table>
</form>