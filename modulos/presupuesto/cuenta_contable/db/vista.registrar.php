<script type='text/javascript'>
var dialog;
//
//
//

$("#cuenta_contable_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/cuenta_contable/db/grid_cuenta_contable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#cuenta_contable_db-consultas-busqueda_nombre").val(); 
					var busq_partida= jQuery("#cuenta_contable_db-consultas-busqueda_partida").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/cuenta_contable/db/sql_grid_cuenta_contable.php?busq_nombre="+busq_nombre+"&busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#cuenta_contable_db-consultas-busqueda_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#cuenta_contable_db-consultas-busqueda_partida").keypress(function(key)
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
							var busq_nombre= jQuery("#cuenta_contable_db-consultas-busqueda_nombre").val();
							var busq_partida= jQuery("#cuenta_contable_db-consultas-busqueda_partida").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/cuenta_contable/db/sql_grid_cuenta_contable.php?busq_nombre="+busq_nombre+"&busq_partida="+busq_partida,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/cuenta_contable/db/sql_grid_cuenta_contable.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Partida','Grupo','Cuenta Contable','Id_Grupo','Nº Partida','Gen&eacute;rica','Espec&iacute;fica', 'Sub-Espec&iacute;fica', 'Tipo', 'Clasificador Presupuestario', 'Comentario'],
								colModel:[
									{name:'id_cuenta_contable',index:'id_cuenta_contable', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partidas',index:'partidas', width:30,sortable:false,resizable:false},
									{name:'nombreGrupo',index:'nombreGrupo', width:100,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:100,sortable:false,resizable:false},
									{name:'grupo',index:'grupo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:100,sortable:false,resizable:false,hidden:true},
									{name:'generica',index:'generica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'especifica',index:'especifica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'subespecifica',index:'subespecifica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'clasificacion_presupuestaria',index:'clasificacion_presupuestaria', width:100,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('cuenta_contable_db_id').value = ret.id_cuenta_contable;
									getObj('cuenta_contable_db_denominacion').value = ret.denominacion;	
									getObj('cuenta_contable_db_partida').value = ret.partida;
									getObj('cuenta_contable_db_generica').value = ret.generica;
									getObj('cuenta_contable_db_especifica').value = ret.especifica;
									getObj('cuenta_contable_db_subespecifica').value = ret.subespecifica;
									getObj('cuenta_contable_db_grupo').value = ret.grupo;	
									getObj('cuenta_contable_db_tipo').value = ret.tipo;
									getObj('cuenta_contable_db_cuenta_contable').value = ret.clasificacion_presupuestaria;
									getObj('cuenta_contable_db_comentario').value = ret.comentario;
									getObj('cuenta_contable_db_btn_actualizar').style.display='';
									getObj('cuenta_contable_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#cuenta_contable_db-consultas-busqueda_nombre").focus();
								$('#cuenta_contable_db-consultas-busqueda_nombre').alpha({allow:' '});
								$('#cuenta_contable_db-consultas-busqueda_partida').numeric({allow:''});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_clasi_presu',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
//
/*$("#cuenta_contable_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/cuenta_contable/db/grid_cuenta_contable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contable', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_partida= jQuery("#cuenta_contable_db-consultas-busqueda_partida").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/cuenta_contable/db/sql_grid_cuenta_contable.php?busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 
				}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#cuenta_contable_db-consultas-busqueda_partida").keypress(function(key)
				{
						if (key.keycode==13)$("#cuenta_contable_db-consultas-busqueda_boton_filtro")
						getObj('cuenta_contable_db-consultas-busqueda_nombre').value=""
						if(key.keyCode==27){this.close();}
				});
					function clasificador_presupuestario_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(clasificador_presupuestario_gridReload,500)
						}
					function clasificador_presupuestario_gridReload()
					{
							var busq_partida= jQuery("#cuenta_contable_db-consultas-busqueda_partida").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/cuenta_contable/db/sql_grid_cuenta_contable.php?busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 			
						}

					$("#cuenta_contable_db-consultas-busqueda_nombre").keypress(function(key)
						{
							if (key.keycode==13)$("#cuenta_contable_db-consultas-busqueda_boton_filtro")
							getObj('cuenta_contable_db-consultas-busqueda_partida').value=""
							if(key.keyCode==27){this.close();}
							
						});
					function clasificador_presupuestario_nombre_dosearch()
					{
							if(!flAuto) return; 
								if(timeoutHnd) 
								clearTimeout(timeoutHnd) 
								timeoutHnd = setTimeout(clasificador_presupuestario_nombre_gridReload,500)
					}
					function clasificador_presupuestario_nombre_gridReload()
					{
								var busq_nombre= jQuery("#cuenta_contable_db-consultas-busqueda_nombre").val();
								jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/cuenta_contable/db/sql_grid_cuenta_contable.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 			
					}
				
					$("#cuenta_contable_db-consultas-busqueda_boton_filtro").click(function(){
					        clasificador_presupuestario_dosearch();
							if(getObj('cuenta_contable_db-consultas-busqueda_partida').value!="")clasificador_presupuestario_dosearch();
    					    if(getObj('cuenta_contable_db-consultas-busqueda_nombre').value!="")clasificador_presupuestario_nombre_dosearch();
						    
							
						});
				}
		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:730,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/cuenta_contable/db/sql_grid_cuenta_contable.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Partida','Grupo','Cuenta Contable','Id_Grupo','Nº Partida','Gen&eacute;rica','Espec&iacute;fica', 'Sub-Espec&iacute;fica', 'Tipo', 'Clasificador Presupuestario', 'Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ipartida',index:'ipartida', width:70,sortable:false,resizable:false},
									{name:'gruponombre',index:'gruponombre', width:100,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:300,sortable:false,resizable:false},
									{name:'grupo',index:'grupo', width:110,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:221,sortable:false,resizable:false,hidden:true},
									{name:'generica',index:'generica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'especifica',index:'especifica', width:110,sortable:false,resizable:false,hidden:true},
									{name:'sub_especifica',index:'sub_especifica', width:110,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:110,sortable:false,resizable:false,hidden:true},
									{name:'clasificacion',index:'clasificacion', width:110,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:110,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuenta_contable_db_id').value = ret.id;
									getObj('cuenta_contable_db_denominacion').value = ret.denominacion;	
									getObj('cuenta_contable_db_partida').value = ret.partida;
									getObj('cuenta_contable_db_generica').value = ret.generica;
									getObj('cuenta_contable_db_especifica').value = ret.especifica;
									getObj('cuenta_contable_db_subespecifica').value = ret.sub_especifica;
									getObj('cuenta_contable_db_grupo').value = ret.grupo;	
									getObj('cuenta_contable_db_tipo').value = ret.tipo;
									getObj('cuenta_contable_db_cuenta_contable').value = ret.clasificacion;
									getObj('cuenta_contable_db_comentario').value = ret.comentario;
									getObj('cuenta_contable_db_btn_actualizar').style.display='';
									getObj('cuenta_contable_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
									$('#cuenta_contable_db-consultas-busqueda_partida').numeric();
									$('#cuenta_contable_db-consultas-busqueda_nombre').alpha();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_clasi_presu',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/

/*$("#cuenta_contable_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/cuenta_contable/db/grid_clasificador_presupuestario.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Clasificador Presupuestario',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

		});
*/
$("#cuenta_contable_db_btn_guardar").click(function() {
	if($('#form_db_cuenta_contable').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
		url: "modulos/presupuesto/cuenta_contable/db/sql.cuenta_contable.php",
			data:dataForm('form_db_cuenta_contable'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_db_cuenta_contable');
					getObj('cuenta_contable_db_denominacion').value = "";	
					getObj('cuenta_contable_db_especifica').value = "";
					getObj('cuenta_contable_db_subespecifica').value = "";
					getObj('cuenta_contable_db_cuenta_contable').value = "";
					getObj('cuenta_contable_db_comentario').value = "";
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(html,true,true);
					
				}
			}
		});
	}
});

$("#cuenta_contable_db_btn_actualizar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	/*if($('#form_db_programa').jVal())
	{*/
		$.ajax (
		{
			url: "modulos/presupuesto/cuenta_contable/db/sql.actualizar.php",
			data:dataForm('form_db_cuenta_contable'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('cuenta_contable_db_btn_actualizar').style.display='none';
					getObj('cuenta_contable_db_btn_guardar').style.display='';
					partida=getObj('cuenta_contable_db_partida').value;
					generica=getObj('cuenta_contable_db_generica').value;
					clearForm('form_db_cuenta_contable');
					getObj('cuenta_contable_db_partida').value=partida;
					getObj('cuenta_contable_db_generica').value=generica;
				}
				else
				{
					setBarraEstado(html);
				}
				
			}
		});
	});
$("#cuenta_contable_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('cuenta_contable_db_btn_cancelar').style.display='';
	getObj('cuenta_contable_db_btn_actualizar').style.display='none';
	getObj('cuenta_contable_db_btn_guardar').style.display='';
	getObj('cuenta_contable_db_grupo').value=3;
	getObj('cuenta_contable_db_tipo').value=1;
	clearForm('form_db_cuenta_contable');
});
function consulta_automatica_partida()
{
	if ((getObj('cuenta_contable_db_partida')!="") && (getObj('cuenta_contable_db_generica')!="")&&(getObj('cuenta_contable_db_especifica')!="")&&(getObj('cuenta_contable_db_subespecifica')!=""))
	{
	$.ajax({
			url:"modulos/presupuesto/cuenta_contable/db/sql.cuenta_contable_subespecifica.php",
            data:dataForm('form_db_cuenta_contable'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				if(recordset)
				{
				recordset = recordset.split(".");
				getObj('cuenta_contable_db_id').value = recordset[0].substring(1);
				getObj('cuenta_contable_db_grupo').value=recordset[5];
				getObj('cuenta_contable_db_tipo').value=recordset[14];
				getObj('cuenta_contable_db_comentario').value =recordset[13];
			 	getObj('cuenta_contable_db_denominacion').value=recordset[6];
				getObj('cuenta_contable_db_cuenta_contable').value=recordset[12];
				getObj('cuenta_contable_db_btn_actualizar').style.display='';
				getObj('cuenta_contable_db_btn_guardar').style.display='none';	
				}
				else
			 {  
			   getObj('cuenta_contable_db_id').value ="";
			    getObj('cuenta_contable_db_grupo').value="";
				getObj('cuenta_contable_db_tipo').value="";
				getObj('cuenta_contable_db_comentario').value ="" ;
			 	getObj('cuenta_contable_db_denominacion').value="" ;
				getObj('cuenta_contable_db_cuenta_contable').value="";
				}
			 }
		});	 	 
	}	//alert(html);
}
$('#cuenta_contable_db_subespecifica').blur(consulta_automatica_partida)
$('#cuenta_contable_db_subespecifica').change(consulta_automatica_partida)
$('#cuenta_contable_db_especifica').change(consulta_automatica_partida)
$('#cuenta_contable_db_generica').change(consulta_automatica_partida)
$('#cuenta_contable_db_partida').change(consulta_automatica_partida)
$('#cuenta_contable_db_denominacion').alpha({allow:'._1234567890- '});
$('#cuenta_contable_db_cuenta_contable').alphanumeric({allow:'_'});
$('#cuenta_contable_db_partida').numeric({allow:''});
$('#cuenta_contable_db_generica').numeric({allow:''});
$('#cuenta_contable_db_especifica').numeric({allow:''});
$('#cuenta_contable_db_subespecifica').numeric({allow:''});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
	
</script>
<div id="botonera">
	<img id="cuenta_contable_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="cuenta_contable_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" onclick="Application.evalCode('win_popup_armador', true);" /><img id="cuenta_contable_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="cuenta_contable_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif" onclick="guardar()" /></div>
<form method="post"  name="form_db_cuenta_contable" id="form_db_cuenta_contable">
	<table class="cuerpo_formulario">
		<tr>
			<th colspan="2" class="titulo_frame"><img src="imagenes/iconos/clasifica24x24.png" style="padding-right:5px;" align="absmiddle" /> Cuenta Contable </th>
		</tr>
		<tr>
			<th>Partida : 				</th>
			<td>	<input name="cuenta_contable_db_partida" type="text" id="cuenta_contable_db_partida" size="3"  maxlength="3" message="Introduzca una Partida en el Siguiente Formato (301 o 401). " 
				jVal="{valid:/^[0-9]{3}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}" onchange="consulta_automatica_partida" onclick="consulta_automatica_partida" onblur="consulta_automatica_partida" ></td>
		</tr>
		<tr>
			<th> Gen&eacute;rica :		</th>
			<td>	<input name="cuenta_contable_db_generica" type="text" id="cuenta_contable_db_generica" size="3" maxlength="2"message="Introduzca la Partida Gen&eacute;rica." 
				jVal="{valid:/^[0-9]{1,2}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}" onchange="consulta_automatica_partida" onclick="consulta_automatica_partida"  onblur="consulta_automatica_partida" ></td>
		</tr>
		<tr>
			<th> Espec&iacute;fica:		</th>
			<td><input name="cuenta_contable_db_especifica" type="text" id="cuenta_contable_db_especifica" size="3" maxlength="2" message="Introduzca la Partida Especifica." 
				jval="{valid:/^[0-9]{1,2}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}" onchange="consulta_automatica_partida" onclick="consulta_automatica_partida" onblur="consulta_automatica_partida" /></td>
		</tr>
		<tr>
			<th> Sub-Espec&iacute;fica:	</th>
			<td><input name="cuenta_contable_db_subespecifica" type="text" id="cuenta_contable_db_subespecifica" size="3" maxlength="2" message="Introduzca la Partida Sub-Especifica."  
				jval="{valid:/^[0-9]{1,2}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}" onclick="consulta_automatica_partida" onchange="consulta_automatica_partida"  onblur="consulta_automatica_partida"  /></td>
		</tr>
		<tr>
			<th>Grupo:</th>
			<td >
				<select name="cuenta_contable_db_grupo" id="cuenta_contable_db_grupo" style="width:90px; min-width:90px;"  onfocus="consulta_automatica_partida">
					<option value="1">Activos</option>
					<option value="2">Pasivos</option>
					<option value="5">Resultados</option>
					<option value="6">Patrimonio</option>
					<option value="7">Cuentas de Orden</option>
				</select>			
			</td>
		</tr>
		<tr>
			<th>Tipo:</th>
			<td >
				<select name="cuenta_contable_db_tipo" id="cuenta_contable_db_tipo" style="width:90px; min-width:90px;">
					<option value="1">Titulo</option>
					<option value="2">Detalle</option>
				</select>			</td>
		</tr>
		<tr>
			<th>Clasificaci&oacute;n Presupuestaria:			</th>
			<td>	<input name="cuenta_contable_db_cuenta_contable" type="text" id="cuenta_contable_db_cuenta_contable" size="13" maxlength="12" message="Introduzca el Nombre de la Cuenta Contable. "
			jVal="{valid:/^[0-9.]{8,20}$/, message:'Col&oacute;quele al menos 10 d&iacute;gitos ', styleType:'cover'}"
				jValKey="{valid:/[0-9-.]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"></td>
		</tr>
		<tr>
			<th>Denominaci&oacute;n:		</th>
			<td>	<input name="cuenta_contable_db_denominacion" type="text" id="cuenta_contable_db_denominacion" style="width:80ex" maxlength="100" message="Introduzca el Nombre de la Clasificacion." 
				jVal="{valid:/^[a-zA-Z // ,áéíóúÁÉÍÓÚ _1234567890-]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z //,áéíóúÁÉÍÓÚ _1234567890-]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"></td>
		</tr>	

		<tr>
			<th>Comentario:				</th>
			<td>	<textarea name="cuenta_contable_db_comentario" id="cuenta_contable_db_comentario" cols="86" rows="3"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="cuenta_contable_db_id" id="cuenta_contable_db_id" />
</form>
