<? if (!$_SESSION) session_start();
?>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script>
/* Marcaras de edicion de campos de entrada --> */
(function($){
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
//----------------- fin mascara edicion de campo -------------------------///
var dialog;
//---------- consulta emergente de bienes -------------------------------------------------//
$("#registrar_depreciacion_pr_bien_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/depreciacion/pr/vista.grid_bien.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente del Activo', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#fotos_bien_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/depreciacion/pr/sql_bien_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#fotos_bien_pr_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#fotos_bien_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/depreciacion/pr/sql_bien_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");		
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
								url:'modulos/bienes/depreciacion/pr/sql_bien_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Bien','','','','','','','',''],
								colModel:[
									{name:'id_dep',index:'id_dep', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'valor_compra',index:'valor_compra',hidden:true},
									{name:'fecha_compra',index:'fecha_compra',hidden:true},
									{name:'vida_util',index:'vida_util',hidden:true},
									{name:'valor_dep_acu',index:'valor_dep_acu',hidden:true},
									{name:'valor_libros',index:'vaor_libros',hidden:true},
									{name:'fecha_dep',index:'fecha_dep',hidden:true},
									{name:'vida_util_dep',index:'vida_util_dep',hidden:true},
									{name:'valor_dep_men',index:'valor_dep_men',hidden:true}
									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id)
								{
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('registrar_depreciacion_pr_id_bien').value=ret.id_dep;
									getObj('registrar_depreciacion_pr_bien').value=ret.nombre;
									getObj('registrar_depreciacion_pr_valor_compra').value=ret.valor_compra;
									var valor_compra=ret.valor_compra;
									valor_compra=valor_compra.replace(".","");
									var vida=ret.vida_util;
									valor_compra=parseFloat(valor_compra);
									vida=parseInt(vida,10);
									var valor_depre= valor_compra/vida;
									var val_dep=valor_depre;
									valor_depre=valor_depre.currency(2,",",".");
									getObj('registrar_depreciacion_pr_vida_util').value=ret.vida_util;
									var fecha_c=ret.fecha_compra;
									var dia_c=fecha_c.substr(8,10);
									var mes_c=fecha_c.substr(5,2);
									var ano_c=fecha_c.substr(0,4);
									fecha_c=dia_c+"-"+mes_c+"-"+ano_c;
								   getObj('registrar_depreciacion_pr_fecha_compra').value=fecha_c;
									var fecha_hoy=getObj('fecha_hoy').value;
									var dia_hoy=fecha_hoy.substr(0,2);
									var mes_hoy=fecha_hoy.substr(3,2);
									var ano_hoy=fecha_hoy.substr(6,4);
									dia_hoy=parseInt(dia_hoy,10);
									mes_hoy=parseInt(mes_hoy,10); 
									ano_hoy=parseInt(ano_hoy,10);
									var fecha_d=ret.fecha_dep;
									var dia_d=fecha_d.substr(8,10); dia=parseInt(dia_d,10);
									var mes_d=fecha_d.substr(5,2); mes=parseInt(mes_d,10);
									var ano_d=fecha_d.substr(0,4); ano=parseInt(ano_d,10);
									fecha_d=dia_d+"-"+mes_d+"-"+ano_d;
								if(vida_util_dep!=0)
								{
									if(fecha_hoy!=fecha_d)
									{
										//alert('primer condicional');
										getObj('registrar_depreciacion_pr_mensual').value=valor_depre;
										getObj('registrar_depreciacion_pr_fecha_depreciar').value=fecha_d;
										var val_libros=ret.valor_libros;
										var acumun= ret.valor_dep_acu;
										getObj('registrar_depreciacion_pr_valor_libro').value=val_libros;
										getObj('registrar_depreciacion_pr_acumulada').value=acumun;
									}
								   	else
									{
										//alert('2do condicional');
										var vida_util_dep=ret.vida_util_dep;
										vida_util_dep=parseInt(vida_util_dep,10);
										vida_util_dep=vida_util_dep-1;
										getObj('vida_util_dep').value=vida_util_dep;
										getObj('registrar_depreciacion_pr_mensual').value=valor_depre;
										mes_d=parseInt(mes_d,10);
										dia_d=parseInt(dia_d,10);
										ano_d=parseInt(ano_d,10);
										var mes_siguiente=mes_d+1;
										var fecha_dep;
										if(mes_siguiente>12){
											mes_d=1;
											ano_d=ano_d+1;
											if(mes_d==1 && dia_d<10)
											{
												fecha_dep='0'+dia_d+'-'+'0'+mes_d+'-'+ano_d;
												getObj('registrar_depreciacion_pr_fecha_depreciar').value=fecha_dep;
											}
											if(mes_d==1 && dia_d>=10)
											{
												fecha_dep=dia_d+'-'+'0'+mes_d+'-'+ano_d;
												getObj('registrar_depreciacion_pr_fecha_depreciar').value=fecha_dep;
											}
										 }
										 else
										 {
											if(mes_d<9 && dia_d<10){
												mes_d=mes_d+1;
												fecha_dep='0'+dia_d+'-'+'0'+mes_d+'-'+ano_d;
												getObj('registrar_depreciacion_pr_fecha_depreciar').value=fecha_dep;
											}
											if(mes_d<9 && dia_d>=10)
											{
												if(mes_d==1 && dia_d>27)
												{
													dia_d=28;
												}
												if((mes_d==3 || mes_d==5)|| mes_d==8)
												{
													dia_d=30;
												}
												mes_d=mes_d+1;
												fecha_dep=dia_d+'-'+'0'+mes_d+'-'+ano_d;
												getObj('registrar_depreciacion_pr_fecha_depreciar').value=fecha_dep;
											}
											if(mes_d>=9 && dia_d<10)
											{ 
												mes_d=mes_d+1;
												fecha_dep='0'+dia_d+'-'+mes_d+'-'+ano_d;
												getObj('registrar_depreciacion_pr_fecha_depreciar').value=fecha_dep;
											}
											if(mes_d>=9 && dia_d>=10)
											{ 
												if(mes_d==10)
												{
													dia_d=30;
												}
												mes_d=mes_d+1;
												fecha_dep=dia_d+'-'+mes_d+'-'+ano_d;
												getObj('registrar_depreciacion_pr_fecha_depreciar').value=fecha_dep;
											}
										}
										var val_dep_men=ret.valor_dep_men;
										val_dep_men=val_dep_men.replace('.','');
										val_dep_men=val_dep_men.replace(',','.');
										val_dep_men=parseFloat(val_dep_men);
										var acumulado=ret.valor_dep_acu;
										acumulado=acumulado.replace('.','');
										acumulado=acumulado.replace(',','.');
										acumulado=parseFloat(acumulado);
										acumulado= acumulado + val_dep_men;
										//alert('acumulado:'+acumulado);
										var val_libros=ret.valor_libros;
										val_libros=val_libros.replace('.','');
										val_libros=val_libros.replace(',','.');
										val_libros=parseFloat(val_libros);
										val_libros= val_libros - val_dep_men;
										//alert('Libro:'+val_libros);
										acumulado=acumulado.currency(2,',','.');
										val_libros=val_libros.currency(2,',','.');
										getObj('registrar_depreciacion_pr_acumulada').value=acumulado;
										getObj('registrar_depreciacion_pr_valor_libro').value=val_libros;
										//alert(val_dep_men+"/"+val_libros);
									}
								}
								else
								{
									alert('Caducó la vida util del bien');
								}
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#fotos_bien_pr_nombre").focus();
								$('#fotos_bien_pr_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_dep',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//--------------------------- fin consulta bien -------------------------------------///////
//--------------------------- boton guardar depreciacion -----------------------------/////
$("#registrar_depreciacion_pr_bien_btn_guardar").click(function() {
	if ($('#form_db_depreciacion').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/depreciacion/pr/sql.registrar_depreciacion.php",
			data:dataForm('form_db_depreciacion'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					
					getObj('registrar_depreciacion_pr_bien').value='';
					getObj('registrar_depreciacion_pr_vida_util').value='';
					getObj('registrar_depreciacion_pr_valor_compra').value='0,00';
					getObj('registrar_depreciacion_pr_fecha_compra').value='';
					getObj('registrar_depreciacion_pr_fecha_depreciar').value='';
					getObj('registrar_depreciacion_pr_mensual').value='0,00';
					getObj('registrar_depreciacion_pr_acumulada').value='0,00';
					getObj('registrar_depreciacion_pr_valor_libro').value='0,00';
					getObj('registrar_depreciacion_pr_id_bien').value='';
					getObj('vida_util_dep').value='';
					getObj('registrar_depreciacion_pr_bien_btn_actualizar').style.display='none';
					getObj('registrar_depreciacion_pr_bien_btn_guardar').style.display='';
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Error fecha"){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /></p></div>",true,true);
					}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});
//------------------------------ fin boton guardar ------------------------------------//
// ---------------------------- boton limpiar -------------------------------------///
$("#registrar_depreciacion_pr_bien_btn_cancelar").click(function() {
//clearForm('form_db_custodio');
getObj('registrar_depreciacion_pr_bien').value='';
getObj('registrar_depreciacion_pr_vida_util').value='';
getObj('registrar_depreciacion_pr_valor_compra').value='0,00';
getObj('registrar_depreciacion_pr_fecha_compra').value='';
getObj('registrar_depreciacion_pr_fecha_depreciar').value='';
getObj('registrar_depreciacion_pr_mensual').value='0,00';
getObj('registrar_depreciacion_pr_acumulada').value='0,00';
getObj('registrar_depreciacion_pr_valor_libro').value='0,00';
getObj('registrar_depreciacion_pr_id_bien').value='';
getObj('vida_util_dep').value='';
getObj('registrar_depreciacion_pr_bien_btn_actualizar').style.display='none';
getObj('registrar_depreciacion_pr_bien_btn_guardar').style.display='';
setBarraEstado("");
});
//-------------------------- fin boton limpiar --------------------------------////
</script>
<div id="botonera">
	<img id="registrar_depreciacion_pr_bien_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img style="display:none" id="registrar_depreciacion_pr_bien_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif"/>
    <img style="display:none" id="registrar_depreciacion_pr_bien_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="registrar_depreciacion_pr_bien_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_depreciacion" id="form_db_depreciacion">
  <table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Depreciacion			</th>
	</tr>

    	<tr>
			<th>Bien:</th>
		  <td><ul class="input_con_emergente">
				<li>
				  <input name="registrar_depreciacion_pr_bien" type="text"  id="registrar_depreciacion_pr_bien" maxlength="60" size="30" readonly="true"/>
				  <span class="btn_consulta_emergente">
                  <input type="hidden" name="registrar_depreciacion_pr_id_bien" id="registrar_depreciacion_pr_id_bien"/>
				  </span>                </li>
				<li id="registrar_depreciacion_pr_bien_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul></td>
		</tr>
    	<tr>
    	  <th>Vida Util:</th>
    	  <td><label>
    	    <input name="registrar_depreciacion_pr_vida_util" type="text" id="registrar_depreciacion_pr_vida_util" size="2" maxlength="3" readonly="readonly" />
Mes(es)</label></td>
  	  </tr>
    	<tr>
    	  <th>Valor Compra:</th>
    	  <td><input name="registrar_depreciacion_pr_valor_compra" type="text" id="registrar_depreciacion_pr_valor_compra" size="10" maxlength="14" readonly="readonly" align="right" alt="signed-decimal"/></td>
  	  </tr>
    	<tr>
    	  <th>Fecha Compra:</th>
    	  <td><input name="registrar_depreciacion_pr_fecha_compra" type="text" id="registrar_depreciacion_pr_fecha_compra" size="10" readonly="readonly" /></td>
  	  </tr>
    	<tr>
    	  <th>Fecha Prox. depreciacion:</th>
    	  <td><input name="registrar_depreciacion_pr_fecha_depreciar" type="text" id="registrar_depreciacion_pr_fecha_depreciar" size="10" readonly="readonly" /></td>
  	  </tr>
    	<tr>
    	  <th>Depreciacion Mensual:</th>
    	  <td><input name="registrar_depreciacion_pr_mensual" type="text" id="registrar_depreciacion_pr_mensual" size="10" readonly="readonly" alt="signed-decimal"/>
   	      </td>
  	  </tr>
    	<tr>
    	  <th>Depreciacion Acumulada:</th>
    	  <td><input name="registrar_depreciacion_pr_acumulada" type="text" id="registrar_depreciacion_pr_acumulada" size="10" readonly="readonly" alt="signed-decimal"/></td>
  	  </tr>
        <tr>
			<th>Valor Libro:</th>
		  <td><input name="registrar_depreciacion_pr_valor_libro" type="text" id="registrar_depreciacion_pr_valor_libro" size="10" readonly="readonly" alt="signed-decimal"/></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
  <span class="titulo_frame">
  <input type="text" name="fecha_hoy" id="fecha_hoy" value="<?php echo date("d-m-Y"); ?>" />
  </span>
  <input type="hidden" name="vida_util_dep" id="vida_util_dep" />
</form>