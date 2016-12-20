<?php
if (!$_SESSION) session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	$sql="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (id_organismo = ".$_SESSION["id_organismo"].") ORDER BY tipo_documento_cxp.nombre";
	$rs_tipos_doc =& $conn->Execute($sql);
	while (!$rs_tipos_doc->EOF) {
		$opt_tipos_doc.="<option value='".$rs_tipos_doc->fields("id_tipo_documento")."' >".$rs_tipos_doc->fields("nombre")."</option>";
		$rs_tipos_doc->MoveNext();
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////	
?>
<script type="text/javascript">
//////////////////////////////////////////////////////////////////////////
function consulta_automatica_cuentas_contables_cxp2()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuentas_contables_codigo_cxp2.php",
            data:dataForm('form_rel_doc_cta'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
				getObj('cuentas_por_pagar_db_reldoc_cuenta').value = recordset[1];
				getObj('cuentas_por_pagar_db_reldoc_cuenta_id').value=recordset[0];
				getObj('cuentas_por_pagar_db_reldoc_cuenta_descripcion').value=recordset[2];

			}
				else
				{
				getObj('cuentas_por_pagar_db_reldoc_cuenta').value = "";
				getObj('cuentas_por_pagar_db_reldoc_cuenta_id').value="";
				getObj('cuentas_por_pagar_db_reldoc_cuenta_descripcion').value="";
				}
				
			 }
			
		});	 	 
}
////////////////////////////////////////////////////////////////////////////
function limpiar_formu()
{
	getObj('cuentas_por_pagar_db_rel_doc_cta_tipo').value=0;
	getObj('cuentas_por_pagar_db_rel_doc_cta_btn_actualizar').style.display='none';
	getObj('cuentas_por_pagar_db_rel_doc_cta_btn_guardar').style.display='';
	clearForm('form_rel_doc_cta');

}
$("#cuentas_por_pagar_db_rel_doc_cta_btn_cancelar").click(function(){
	limpiar_formu();
});
////////////////////////////////////////////////////////////////////////////
$("#cuentas_por_pagar_db_rel_doc_cta_btn_guardar").click(function(){
		
					setBarraEstado(mensaje[esperando_respuesta]);
					$.ajax (
					{
						url:'modulos/cuentas_por_pagar/documentos/db/sql.relacionar_cuentas.php',
						data:dataForm('form_rel_doc_cta'),
						type:'POST',
						cache: false,
						success: function(html)
						{
							//alert(html);
							recordset=html.split("*");
							if (recordset[0]=="Registrado")
							{
								//setBarraEstado("");
								setBarraEstado(mensaje[registro_exitoso],true,true);
								//
								limpiar_formu();
			
							}
							else if (recordset[0]=="NoRegistro")
							{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE REGISTR&Oacute; LA ORDEN</p></div>",true,true);
			
			
							}
							else
							{
								alert(recordset[0]);
								setBarraEstado(recordset[0]);
							}
						
						}
					});
	
});
//////////////////////////////////////////////////////////////////////////
$("#cuentas_por_pagar_db_rel_doc_cta_btn_consultar").click(function(){
	
	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/cuentas_por_pagar/documentos/db/grid_relacion.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Relaci&oacute;n Doc/Cta Cont', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					//alert("entro01");
					var busq_cuenta= $("#relacion_cuenta_contable").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta_doc.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
					
				$("#relacion_tipo_documento").keypress(
					function(key)
					{
						
						dosearch3();													
					}
				);		
				function dosearch3()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload3,500)
				}				
				function gridReload3()
				{
					//alert("entro");
					var busq_tipo= $("#relacion_tipo_documento").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta_doc.php?busq_tipo="+busq_tipo,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta_doc.php?busq_tipo="+busq_tipo;	
					//alert(url);
				}
				/////////////////////////////////////////////////
				$("#relacion_descripcion_cuenta").keypress(
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
					//alert("entro");
					var busq_nom= $("#relacion_descripcion_cuenta").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta_doc.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta_doc.php?busq_nom="+busq_nom;	
					//alert(url);
				}
				//
				$("#relacion_cuenta_contable").keypress(
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
					var busq_cuenta= $("#relacion_cuenta_contable").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta_doc.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta_doc.php?busq_cuenta="+busq_cuenta;
                 ///  alert(url);				
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
								url:'modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta_doc.php?nd='+nd,
								datatype: "json",
		colNames:['id','id_cta','Cuenta Contable', 'Denominacion','id_tipo','Tipo Documento'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_cta',index:'id_cta', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'id_tipo',index:'id_tipo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false}
									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#cuentas_por_pagar_db_rel_doc_id').val(ret.id);
									$('#cuentas_por_pagar_db_rel_doc_cta_tipo').val(ret.id_tipo);
									$('#cuentas_por_pagar_db_reldoc_cuenta').val(ret.cuenta_contable);
									$('#cuentas_por_pagar_db_reldoc_cuenta_id').val(ret.id_cta);
									$('#cuentas_por_pagar_db_reldoc_cuenta_descripcion').val(ret.nombre);
									//
								getObj('cuentas_por_pagar_db_rel_doc_cta_btn_actualizar').style.display='';
								getObj('cuentas_por_pagar_db_rel_doc_cta_btn_guardar').style.display='none';
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
///////////////////////////////////////////////////////////////////////////
$("#cuentas_por_pagar_db_rel_doc_cta_btn_actualizar").click(function(){
//

	setBarraEstado(mensaje[esperando_respuesta]);
	if((getObj('cuentas_por_pagar_db_reldoc_cuenta_id').value!='')&&(getObj('cuentas_por_pagar_db_rel_doc_cta_tipo').value!='0'))
	{
		$.ajax (
		{
			url:'modulos/cuentas_por_pagar/documentos/db/sql.actualizar_relacion.php',
			data:dataForm('form_rel_doc_cta'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				//alert(html);
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					
					limpiar_formu();

				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					
				}				
				else
				{
					alert(html);
					setBarraEstado(html);
				}
			}
		});
	
	}// fin de if((getObj('cuentas_por_pagar_db_reldoc_cuenta_id').value!='')&&(getObj('cuentas_por_pagar_db_rel_doc_cta_tipo').value!='0'))
//

});
//////////////////////////////////////////////////////////////////////////
$("#cuentas_por_pagar_db_reldoc_btn_consultar_cuenta").click(function() {
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/cuentas_por_pagar/documentos/db/grid_pagar_prove.php", { },
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
			url:"modulos/cuentas_por_pagar/documentos/db/grid_relacion.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					//alert("entro01");
					var busq_cuenta= $("#relacion_cuenta_contable").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-cuenta-contable-busqueda-partida").keypress(
					function(key)
					{
						
						dosearch3();													
					}
				);		
				function dosearch3()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload3,500)
				}
				function gridReload3()
				{
					//alert("entro");
					var busq_partida= $("#consulta-cuenta-contable-busqueda-partida").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_partida="+busq_partida,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_partida="+busq_partida;	
					//alert(url);
				}	
				$("#relacion_descripcion_cuenta").keypress(
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
					//alert("entro");
					var busq_nom= $("#relacion_descripcion_cuenta").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_nom="+busq_nom;	
					//alert(url);
				}
				//
				$("#relacion_cuenta_contable").keypress(
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
					var busq_cuenta= $("#relacion_cuenta_contable").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta;
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
								url:'modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?nd='+nd,
								datatype: "json",
		colNames:['C&oacute;digo','Cuenta', 'Denominacion','Tipo','requiere_auxiliar','requiere_proyecto','requiere_unidad_ejecutora','requiere_utilizacion_fondos'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false},
									{name:'requiere_auxiliar',index:'requiere_auxiliar', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_proyecto',index:'requiere_proyecto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_unidad_ejecutora',index:'requiere_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_utilizacion_fondos',index:'requiere_utilizacion_fondos', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#cuentas_por_pagar_db_reldoc_cuenta').val(ret.cuenta_contable);
									$('#cuentas_por_pagar_db_reldoc_cuenta_id').val(ret.id);
									$('#cuentas_por_pagar_db_reldoc_cuenta_descripcion').val(ret.nombre);
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
</script>
<div id="botonera"><img id="cuentas_por_pagar_db_rel_doc_cta_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
   <img id="cuentas_por_pagar_db_rel_doc_cta_btn_eliminar" class="btn_anular"src="imagenes/null.gif" style="display:none"/>
	<img id="cuentas_por_pagar_db_rel_doc_cta_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>
	<img id="cuentas_por_pagar_db_rel_doc_cta_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
	<img src="imagenes/null.gif"  class="btn_consultar" id="cuentas_por_pagar_db_rel_doc_cta_btn_consultar" />
	<img id="cuentas_por_pagar_db_rel_doc_cta_btn_abrir" src="imagenes/iconos/abrir_orden_cxp.png" style="display:none" />
	<img id="cuentas_por_pagar_db_rel_doc_cta_btn_cerrar" src="imagenes/iconos/cerrar_orden_cxp.png" style="display:none"/>
	<img id="cuentas_por_pagar_db_rel_doc_cta_btn_imprimir"  class="btn_imprimir" src="imagenes/null.gif"  style="display:none" /></div>
<form method='POST' id="form_rel_doc_cta" name="form_rel_doc_cta">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Registrar Relaci&oacute;n Doc/CuentasCont			</th>
		</tr>
		<tr>
			<th>Tipo de Documento</th>
		  <td>
					<select id="cuentas_por_pagar_db_rel_doc_cta_tipo" name="cuentas_por_pagar_db_rel_doc_cta_tipo">
	<option value="0">---- SELECCIONE -----</option>
					<?= $opt_tipos_doc; ?>
</select>
					<span class="titulo_frame">
					<input type="text" name="cuentas_por_pagar_db_rel_doc_id" id="cuentas_por_pagar_db_rel_doc_id">
			</span>			</td>
		</tr>
		<tr>
			<th>Cuenta Contable</th>
			<td style="border-top: 1px #BADBFC solid">
			<ul class="input_con_emergente">
			 <li>
				<input type="text" name="cuentas_por_pagar_db_reldoc_cuenta" id="cuentas_por_pagar_db_reldoc_cuenta"  size='12' maxlength="12" onBlur="consulta_automatica_cuentas_contables_cxp2()" onChange="consulta_automatica_cuentas_contables_cxp2()"
				message="Introduzca la cuenta contable" 
				 />
				<input type="hidden" id="cuentas_por_pagar_db_reldoc_cuenta_id" name="cuentas_por_pagar_db_reldoc_cuenta_id" />
				<input type="text" name="cuentas_por_pagar_db_reldoc_cuenta_descripcion" id="cuentas_por_pagar_db_reldoc_cuenta_descripcion"  size='60' maxlength="60" readonly="readonly"
				message="Introduzca la cuenta contable" 
				/>
			 </li>
			<li id="cuentas_por_pagar_db_reldoc_btn_consultar_cuenta" class="btn_consulta_emergente"></li>
			</ul>			</td>
		</tr>
		<tr>
		<th colspan="2">&nbsp;</th>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>	