<script type='text/javascript'>
//******************************************************************
var dialog;
//******************************************************************
$("#detalle_demanda_btn_guardar").click(function() {
	if($('#form_detalle_demnada').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/adquisiones/plan_compra/db/sql.registrar_detalle_demanda.php",
			data:dataForm('form_detalle_demnada'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_detalle_demnada');
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
//******************************************************************
//
//
//
$("#detalle_demanda_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/plan_compra/db/vista.grid_plan_com.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Demanda', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_codigo= jQuery("#plan_compra_db_cod_demanda").val(); 
					var busq_nombre= jQuery("#plan_compra_db_nom_demanda").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/plan_compra/db/sql_plan_com.php?busq_cdigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				
				var timeoutHnd; 
				var flAuto = true;
				$("#plan_compra_db_cod_demanda").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#plan_compra_db_nom_demanda").keypress(function(key)
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
							var busq_codigo= jQuery("#plan_compra_db_cod_demanda").val();
							var busq_nombre= jQuery("#plan_compra_db_nom_demanda").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/plan_compra/db/sql_plan_com.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/adquisiones/plan_compra/db/sql_plan_com.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Nombre','Comentario','id_demanda','Codigo Demanda','Demanda'],
								colModel:[
									{name:'id_detalle_demanda',index:'id_detalle_demanda', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_detalle_demanda',index:'codigo_detalle_demanda', width:25,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_demanda',index:'id_demanda', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_demanda',index:'codigo_demanda', width:40,sortable:false,resizable:false},
									{name:'demanda',index:'demanda', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_detalle_demanda').value = ret.id_detalle_demanda;
									getObj('detalle_demanda_db_codigo').value = ret.codigo_detalle_demanda;
									getObj('detalle_demanda_db_nombre').value = ret.nombre;
									getObj('detalle_demanda_db_comentario').value = ret.comentario;
									getObj('detalle_demanda_db_id_demanda').value = ret.id_demanda;
									getObj('detalle_demanda_db_demanda').value = ret.demanda;
									getObj('detalle_demanda_db_codigo_demanda').value = ret.codigo_demanda;
									getObj('detalle_demanda_btn_cancelar').style.display='';
									getObj('detalle_demanda_btn_actualizar').style.display='';
									getObj('detalle_demanda_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_detalle_demnada').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#plan_compra_db_codigo_demanda2").focus();
								$('#plan_compra_db_cod_demanda').alpha({allow:'0123456789'});
								$('#plan_compra_db_nom_demanda').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_detalle_demanda',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
//
/*$("#detalle_demanda_btn_consultar").click(function() {
	var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/plan_compra/db/grid_plan_compra.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente Detalle de la Demanda',modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/plan_compra/db/sql_grid_detalle_demanda.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Nombre','Comentario','id_demanda','Codigo Demanda','Demanda'],
								colModel:[
									{name:'id',index:'id', width:10,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:250,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_demanda',index:'id_demanda', width:10,sortable:false,resizable:false,hidden:true},
									{name:'codigo_demanda',index:'codigo_demanda', width:80,sortable:false,resizable:false},
									{name:'demanda',index:'demanda', width:250,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_detalle_demanda').value = ret.id;
									getObj('detalle_demanda_db_codigo').value = ret.codigo;
									getObj('detalle_demanda_db_nombre').value = ret.nombre;
									getObj('detalle_demanda_db_comentario').value = ret.comentario;
									getObj('detalle_demanda_db_id_demanda').value = ret.id_demanda;
									getObj('detalle_demanda_db_demanda').value = ret.demanda;
									getObj('detalle_demanda_db_codigo_demanda').value = ret.codigo_demanda;
									getObj('detalle_demanda_btn_cancelar').style.display='';
									getObj('detalle_demanda_btn_actualizar').style.display='';
									getObj('detalle_demanda_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_detalle_demnada').jVal();
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
//******************************************************************
//
//
//
$("#detalle_demanda_db_btn_consultar_demanda").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/plan_compra/db/vista.grid_plan_compra2.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Demanda', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_codigo= jQuery("#plan_compra_db_codigo_demanda2").val(); 
					var busq_nombre= jQuery("#plan_compra_db_nombre_demanda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/plan_compra/db/sql_plan_compra2.php?busq_cdigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				
				var timeoutHnd; 
				var flAuto = true;
				$("#plan_compra_db_codigo_demanda2").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#plan_compra_db_nombre_demanda2").keypress(function(key)
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
							var busq_codigo= jQuery("#plan_compra_db_codigo_demanda2").val();
							var busq_nombre= jQuery("#plan_compra_db_nombre_demanda2").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/plan_compra/db/sql_plan_compra2.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/plan_compra/db/sql_plan_compra2.php?nd='+nd,
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
									getObj('detalle_demanda_db_id_demanda').value = ret.id_demanda;
									getObj('detalle_demanda_db_codigo_demanda').value = ret.codigo_demanda;
									getObj('detalle_demanda_db_demanda').value = ret.nombre;
									getObj('id_detalle_demanda').value = "";
									getObj('detalle_demanda_db_nombre').value = "";
									getObj('detalle_demanda_db_codigo').value = "";
									getObj('detalle_demanda_db_comentario').value = "";
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#plan_compra_db_codigo_demanda2").focus();
								$('#plan_compra_db_codigo_demanda2').alpha({allow:'0123456789'});
								$('#plan_compra_db_nombre_demanda2').alpha({allow:' '});
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
/*$("#detalle_demanda_db_btn_consultar_demanda").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/plan_compra/db/grid_plan_compra.php", { },
		function(data)
		{								
				dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Demanda', modal: true,center:false,x:0,y:0,show:false });								
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
				url:'modulos/adquisiones/plan_compra/db/cmb.sql.demanda.php?nd='+nd,
				datatype: "json",
				colNames:['id','Codigo', 'Nombre'],
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
					getObj('detalle_demanda_db_id_demanda').value = ret.id;
					getObj('detalle_demanda_db_codigo_demanda').value = ret.codigo;
					getObj('detalle_demanda_db_demanda').value = ret.nombre;
					getObj('id_detalle_demanda').value = "";
					getObj('detalle_demanda_db_nombre').value = "";
					getObj('detalle_demanda_db_codigo').value = "";
					getObj('detalle_demanda_db_comentario').value = "";
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
//************************************* -- Consulta Automatica Detalle-- *****************************
function consulta_automatica_detalle()
{
	$.ajax({
			url:"modulos/adquisiones/plan_compra/db/sql_grid_detalle_codigo.php?detalle_codigo="+getObj('detalle_demanda_db_codigo').value+"&id_demanda="+getObj('detalle_demanda_db_id_demanda').value,
            data:dataForm('form_detalle_demnada'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('id_detalle_demanda').value = recordset[0];
			    getObj('detalle_demanda_db_nombre').value=recordset[1];
				getObj('detalle_demanda_db_comentario').value=recordset[2];
				getObj('detalle_demanda_btn_cancelar').style.display='';
				getObj('detalle_demanda_btn_actualizar').style.display='';
				getObj('detalle_demanda_btn_guardar').style.display='none';
				}
				else
			 {  
			   	getObj('id_detalle_demanda').value ="";
			    //getObj('detalle_demanda_db_id_demanda').value="";
				//getObj('detalle_demanda_db_codigo_demanda').value="";
				//getObj('detalle_demanda_db_demanda').value ="";
			    getObj('detalle_demanda_db_nombre').value="";
				getObj('detalle_demanda_db_comentario').value="";
				}
			 }
		});	 	 
}
//************************************* -- Consulta Automatica Demanda -- *****************************
function consulta_automatica_demanda()
{
	$.ajax({
			url:"modulos/adquisiones/plan_compra/db/sql_grid_demanda_codigo.php?demanda_codigo="+getObj('detalle_demanda_db_codigo_demanda').value,
            data:dataForm('form_detalle_demnada'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('detalle_demanda_db_id_demanda').value = recordset[0];
				getObj('detalle_demanda_db_demanda').value=recordset[1];
				getObj('id_detalle_demanda').value = "";
				getObj('detalle_demanda_db_nombre').value = "";
				getObj('detalle_demanda_db_codigo').value = "";
				getObj('detalle_demanda_db_comentario').value = "";
				}
				else
			 {  
			   	getObj('detalle_demanda_db_id_demanda').value ="";
			    getObj('detalle_demanda_db_demanda').value="";
				}
			 }
		});	 	 
}

//******************************************************************
$("#detalle_demanda_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_detalle_demnada').jVal())
	{
		$.ajax (
		{
			url: "modulos/adquisiones/plan_compra/db/sql.actualizar_detalle_demanda.php",
			data:dataForm('form_detalle_demnada'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						//getObj('tipo_detalle_demanda_btn_eliminar').style.display='none';
						clearForm('form_detalle_demnada');
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
//******************************************************************
$("#detalle_demanda_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('detalle_demanda_btn_cancelar').style.display='';
	getObj('detalle_demanda_btn_actualizar').style.display='none';
	getObj('detalle_demanda_btn_guardar').style.display='';
	clearForm('form_detalle_demnada');
});
//******************************************************************
$('#detalle_demanda_db_codigo').change(consulta_automatica_detalle);
$('#detalle_demanda_db_codigo_demanda').change(consulta_automatica_demanda);

$('#detalle_demanda_db_nombre').alpha({allow:' áéíóúÁÉÍÓÚñÑ'});
//******************************************************************
</script>
<div id="botonera">
	<img id="detalle_demanda_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
	<img id="detalle_demanda_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="detalle_demanda_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="detalle_demanda_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form id="form_detalle_demnada" name="form_detalle_demnada">
<input type="hidden" name="id_detalle_demanda" id="id_detalle_demanda" />
	<table  class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Detalle Demanda </th>
		</tr>
		<tr>
			<th>Demanda</th>
		  <td >
				<ul class="input_con_emergente">
					<li>
						<input name="detalle_demanda_db_codigo_demanda" type="text" id="detalle_demanda_db_codigo_demanda" size="6" maxlength="5"
						message="Introduzca un Nombre Demanda."
						onchange="consulta_automatica_demanda" onclick="consulta_automatica_demanda"
						>
						
						<input name="detalle_demanda_db_demanda" type="text" id="detalle_demanda_db_demanda"  style="width:55ex" maxlength="60"
						message="Introduzca un Nombre Demanda."  readonly
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ ,.1234567890()-_]{2,60}$/, message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/^[a-zA-Z ,.áéíóúÁÉÍÓÚ1234567890()-_]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}">
					</li>
					<li id="detalle_demanda_db_btn_consultar_demanda" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="detalle_demanda_db_id_demanda" type="hidden" id="detalle_demanda_db_id_demanda" />
			</td>
		</tr>		
		<tr>
			<th>Codigo</th>
			<td><input type="text" name="detalle_demanda_db_codigo" id="detalle_demanda_db_codigo" style="width:6ex" maxlength="4"
				message="Introduzca un Nombre. Ejem: 'Articulo' " 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñÑ0123456789]{4}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñÑ0123456789]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
				onchange="consulta_automatica_detalle" onclick="consulta_automatica_detalle"
			 	/>
			</td>
		</tr>
		<tr>
			<th>Nombre</th>
			<td><input type="text" name="detalle_demanda_db_nombre" id="detalle_demanda_db_nombre" style="width:66ex" maxlength="60"
				message="Introduzca un Nombre. Ejem: 'Articulo' " 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñÑ.()//,0123456789]{1,100}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñÑ.()//,0123456789]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
			 	/>
			</td>
		</tr>
		<tr>
			<th>Comentario</th>
			<td>
				<textarea style="width:66ex" name="detalle_demanda_db_comentario" id="detalle_demanda_db_comentario" message="Introduzca un Comentario. Ejem: 'Este Tipo de...' "></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>