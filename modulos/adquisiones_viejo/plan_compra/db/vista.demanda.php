<script type='text/javascript'>
//******************************************************************
var dialog;
//******************************************************************
$("#demanda_btn_guardar").click(function() {
	if($('#form_demnada').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/adquisiones/plan_compra/db/sql.registrar_demanda.php",
			data:dataForm('form_demnada'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_demnada');
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
//************************************* -- Consulta Manual -- *****************************
//
//
//
$("#demanda_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/plan_compra/db/vista.grid_plan_compra.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Demanda', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_codigo= jQuery("#plan_compra_db_codigo_demanda").val(); 
					var busq_nombre= jQuery("#plan_compra_db_nombre_demanda").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/plan_compra/db/sql_plan_compra.php?busq_cdigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				
				var timeoutHnd; 
				var flAuto = true;
				$("#plan_compra_db_codigo_demanda").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#plan_compra_db_nombre_demanda").keypress(function(key)
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
							var busq_codigo= jQuery("#plan_compra_db_codigo_demanda").val();
							var busq_nombre= jQuery("#plan_compra_db_nombre_demanda").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/plan_compra/db/sql_plan_compra.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}
			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/adquisiones/plan_compra/db/sql_plan_compra.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Nombre','Comentario'],
								colModel:[
									{name:'id_demanda',index:'id_demanda', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_demanda',index:'codigo_demanda', width:15,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_demanda').value = ret.id_demanda;
									getObj('demanda_db_codigo').value = ret.codigo_demanda;
									getObj('demanda_db_nombre').value = ret.nombre;
									getObj('demanda_db_comentario').value = ret.comentario;
									getObj('demanda_btn_cancelar').style.display='';
									getObj('demanda_btn_actualizar').style.display='';
									getObj('demanda_btn_guardar').style.display='none';
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#plan_compra_db_codigo_demanda").focus();
								$('#plan_compra_db_codigo_demanda').alpha({allow:'0123456789'});
								$('#plan_compra_db_nombre_demanda').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_demanda',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
//
/*$("#demanda_btn_consultar").click(function() {
	var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/plan_compra/db/grid_plan_compra.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Tipo de Demanda',modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/adquisiones/plan_compra/db/sql_grid_demanda.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Nombre','Comentario'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:220,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false,hidden:true},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_demanda').value = ret.id;
									getObj('demanda_db_codigo').value = ret.codigo;
									getObj('demanda_db_nombre').value = ret.nombre;
									getObj('demanda_db_comentario').value = ret.comentario;
									getObj('demanda_btn_cancelar').style.display='';
									getObj('demanda_btn_actualizar').style.display='';
									getObj('demanda_btn_guardar').style.display='none';
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
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/
//************************************* -- Consulta Automatica -- *****************************
function consulta_automatica_demanda()
{
	$.ajax({
			url:"modulos/adquisiones/plan_compra/db/sql_grid_demanda_codigo.php?demanda_codigo="+getObj('demanda_db_codigo').value,
            data:dataForm('form_demnada'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)  
				{
				recordset = recordset.split("*");
				getObj('id_demanda').value = recordset[0];
				getObj('demanda_db_nombre').value=recordset[1];
				getObj('demanda_db_comentario').value=recordset[2];
				getObj('demanda_btn_cancelar').style.display='';
				getObj('demanda_btn_actualizar').style.display='';
				getObj('demanda_btn_guardar').style.display='none';
				}
				else
			 {  
			   	getObj('id_demanda').value ="";
			    getObj('demanda_db_nombre').value="";
				getObj('demanda_db_comentario').value="";
				}
			 }
		});	 	 
}

//************************************* -- Actualizar -- *****************************
$("#demanda_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_demnada').jVal())
	{
		$.ajax (
		{
			url: "modulos/adquisiones/plan_compra/db/sql.actualizar_demanda.php",
			data:dataForm('form_demnada'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						clearForm('form_demnada');
					});															
				}
				else if (html=="NoActualizo")
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
//************************************* -- Limpiar -- *****************************
$("#demanda_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('demanda_btn_cancelar').style.display='';
	getObj('demanda_btn_actualizar').style.display='none';
	getObj('demanda_btn_guardar').style.display='';
	clearForm('form_demnada');
});
//******************************************************************
$('#demanda_db_codigo').change(consulta_automatica_demanda);

$('#demanda_db_nombre').alpha({allow:' ·ÈÌÛ˙¡…Õ”⁄Ò—'});
//******************************************************************
</script>
<div id="botonera">
	<img id="demanda_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
	<img id="demanda_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="demanda_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="demanda_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form id="form_demnada" name="form_demnada">
<input type="hidden" name="id_demanda" id="id_demanda" />
	<table  class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Demanda </th>
		</tr>
		<tr>
			<th>Codigo</th>
			<td><input type="text" name="demanda_db_codigo" id="demanda_db_codigo" size="6" maxlength="5"
				message="Introduzca un Nombre. Ejem: 'Articulo' " 
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò—0123456789]{3,5}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò—0123456789]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
				onchange="consulta_automatica_demanda" onclick="consulta_automatica_demanda"
			 	/>
			</td>
		</tr>
		<tr>
			<th>Nombre</th>
			<td><input type="text" name="demanda_db_nombre" id="demanda_db_nombre" size="63" maxlength="60"
				message="Introduzca un Nombre. Ejem: 'Articulo' " 
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò—.,- ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò—.,-]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
			 	/>
			</td>
		</tr>
		<tr>
			<th>Comentario</th>
			<td>
				<textarea cols="60" name="demanda_db_comentario" id="demanda_db_comentario" message="Introduzca un Comentario. Ejem: 'Este Tipo de...' "></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>