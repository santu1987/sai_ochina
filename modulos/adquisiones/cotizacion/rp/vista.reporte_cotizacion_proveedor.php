<script>
var dialog;
$("#cotizaciones_proveedor_rp_btn_imprimir").click(function() {
if(getObj('cotizaciones_proveedor_rp_id').value != ''){															
		url="pdf.php?p=modulos/adquisiones/cotizacion/rp/vista.lst.cotizacion_proveedor.php¿id_prove="+getObj('cotizaciones_proveedor_rp_id').value; 
		//alert(url);  getObj('analisis_cotizacion_rp_id_unidad').value
		openTab("Cotizaciones por Proveedor",url);
}else{
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />DEBE SELECIONAR UN PROVEEDOR</p></div>",true,true);	
}
});
// -----------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------
$("#cotizaciones_proveedor_rp_btn_consultar_proveedor").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/adquisiones/cotizacion/rp/cmb.sql.proveedor.php?nd='+nd,
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
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cotizaciones_proveedor_rp_id').value = ret.id_proveedor;
									getObj('cotizaciones_proveedor_rp_codigo').value = ret.codigo;
									getObj('cotizaciones_proveedor_rp_nombre').value = ret.nombre;
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
//-------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------
$("#cotizaciones_proveedor_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_cotizaciones_proveedor');
	});
//-------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------
</script>
<div id="botonera">
	<img id="cotizaciones_proveedor_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="cotizaciones_proveedor_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>
<form name="form_rp_cotizaciones_proveedor" id="form_rp_cotizaciones_proveedor" >
	<table class="cuerpo_formulario" style="width:100px">
        <tr>
            <th  class="titulo_frame" colspan="3">
                <img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />	 COTIZACIONES POR PROVEEDORES</th>
        </tr>
        <tr>
        	<th>Proveedor</th>
            <td>
           		<table class="clear" style="width:420px" border="0">
                    <tr>
                        <td>
                            <input name="cotizaciones_proveedor_rp_codigo"id="cotizaciones_proveedor_rp_codigo" type="text"  size="5"  readonly>
                            <input name="cotizaciones_proveedor_rp_nombre"id="cotizaciones_proveedor_rp_nombre" type="text"  size="60" readonly>
                        </td>
                        <td>
                            <img class="btn_consulta_emergente" id="cotizaciones_proveedor_rp_btn_consultar_proveedor" src="imagenes/null.gif" />
                            <input name="cotizaciones_proveedor_rp_id" id="cotizaciones_proveedor_rp_id" type="hidden" disabled="disabled">
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