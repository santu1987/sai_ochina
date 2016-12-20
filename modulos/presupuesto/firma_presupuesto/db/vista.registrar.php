

<script type='text/javascript'>

var dialog;
//
//
//

$("#firma_presupuesto_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/firma_presupuesto/db/vista.grid_firma_presupuesto.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Programas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#firma_presupuesto_db_nombre_autoriza").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/firma_presupuesto/db/sql_firma_presupuesto.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#firma_presupuesto_db_nombre_autoriza").keypress(function(key)
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
							var busq_nombre= jQuery("#firma_presupuesto_db_nombre_autoriza").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/firma_presupuesto/db/sql_firma_presupuesto.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/presupuesto/firma_presupuesto/db/sql_firma_presupuesto.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Organismo','Nombre Autoriza','Cargo','Grado','Nombre Traspazo','Cargo Traspazo','grado Traspazo','Comentario'],
								colModel:[
									{name:'id_organismo',index:'id_organismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'organismo',index:'organismo', width:15,sortable:false,resizable:false,hidden:true},
									{name:'nombre_autoriza',index:'nombre_autoriza', width:100,sortable:false,resizable:false},
									{name:'cargo_autoriza',index:'cargo_autoriza', width:100,sortable:false,resizable:false,hidden:true},
									{name:'grado_autoriza',index:'grado_autoriza', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre_auto_traspazo',index:'nombre_auto_traspazo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'cargo_auto_traspazo',index:'cargo_auto_traspazo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'grado_auto_traspazo',index:'grado_auto_traspazo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('firma_presupuesto_db_nombre_auto').value = ret.nombre_autoriza;
									getObj('firma_presupuesto_db_cargo_auto').value = ret.cargo_autoriza;
									getObj('firma_presupuesto_db_grado_auto').value = ret.grado_autoriza;
									getObj('firma_presupuesto_db_nombre_auto_tras').value = ret.nombre_auto_traspazo;
									getObj('firma_presupuesto_db_cargo_auto_tras').value = ret.cargo_auto_traspazo;	
									getObj('firma_presupuesto_db_gardo_auto_tras').value = ret.grado_auto_traspazo;
									getObj('firma_presupuesto_db_comentario').value = ret.comentario;
									getObj('firma_presupuesto_db_btn_actualizar').style.display='';
									getObj('firma_presupuesto_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#firma_presupuesto_db_nombre_autoriza").focus();
								$('#firma_presupuesto_db_nombre_autoriza').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_organismo',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
//
/*$("#firma_presupuesto_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/firma_presupuesto/db/grid_firma_presupuesto.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Programas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:730,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/presupuesto/firma_presupuesto/db/sql_grid_firma_presupuesto.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Organismo','Nombre Autoriza','Nombre Autoriza Traspaso','Especifica', 'Sub-Especifica', 'Grupo','Tipo','Comentario', 'Cuenta Contable'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'organismo',index:'denominacion', width:221,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:221,sortable:false,resizable:false},
									{name:'generica',index:'generica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'especifica',index:'especifica', width:110,sortable:false,resizable:false,hidden:true},
									{name:'sub_especifica',index:'sub_especifica', width:110,sortable:false,resizable:false,hidden:true},
									{name:'grupo',index:'grupo', width:110,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:110,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:110,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:110,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('firma_presupuesto_db_nombre_auto').value = ret.partida;
									getObj('firma_presupuesto_db_cargo_auto').value = ret.generica;
									getObj('firma_presupuesto_db_grado_auto').value = ret.especifica;
									getObj('firma_presupuesto_db_nombre_auto_tras').value = ret.sub_especifica;
									getObj('firma_presupuesto_db_cargo_auto_tras').value = ret.grupo;	
									getObj('firma_presupuesto_db_gardo_auto_tras').value = ret.tipo;
									getObj('firma_presupuesto_db_comentario').value = ret.comentario;
									getObj('firma_presupuesto_db_btn_actualizar').style.display='';
									getObj('firma_presupuesto_db_btn_guardar').style.display='none';									
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
								sortname: 'id_organismo',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/

$("#firma_presupuesto_db_btn_guardar").click(function() {
	if($('#form_db_firma_presupuesto').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/firma_presupuesto/db/sql.firma_presupuesto.php",
			data:dataForm('form_db_firma_presupuesto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_firma_presupuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}

			}
		});
	}
});

$("#firma_presupuesto_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	/*if($('#form_db_programa').jVal())
	{*/
		$.ajax (
		{
			url: "modulos/presupuesto/firma_presupuesto/db/sql.actualizar.php",
			data:dataForm('form_db_firma_presupuesto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('firma_presupuesto_db_btn_actualizar').style.display='none';
					getObj('firma_presupuesto_db_btn_guardar').style.display='';
					clearForm('form_db_firma_presupuesto');
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	//}
});

$("#firma_presupuesto_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('firma_presupuesto_db_btn_cancelar').style.display='';
	getObj('firma_presupuesto_db_btn_actualizar').style.display='none';
	getObj('firma_presupuesto_db_btn_guardar').style.display='';
	clearForm('form_db_firma_presupuesto');
});


$('#firma_presupuesto_db_nombre_auto').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ '});
$('#firma_presupuesto_db_cargo_auto').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ '});
$('#firma_presupuesto_db_grado_auto').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ '});
$('#firma_presupuesto_db_nombre_auto_tras').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ '});
$('#firma_presupuesto_db_cargo_auto_tras').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ '});
$('#firma_presupuesto_db_grado_auto_tras').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ '});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
</script>
<div id="botonera">
	<img id="firma_presupuesto_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="firma_presupuesto_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="firma_presupuesto_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="firma_presupuesto_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif" onclick="guardar()" />
</div>
<form method="post"  name="form_db_firma_presupuesto" id="form_db_firma_presupuesto">
	<table class="cuerpo_formulario">
		<tr>
			<th colspan="2" class="titulo_frame"><img src="imagenes/iconos/clasifica24x24.png" style="padding-right:5px;" align="absmiddle" /> Firma Presupuesto</th>
		</tr>
		<tr>
			<th>Nombre autoriza : 		</th>
			<td>	<input name="firma_presupuesto_db_nombre_auto" type="text" id="firma_presupuesto_db_nombre_auto" style="width:62ex" maxlength="60"message="Introduzca el Nombre de quien autoriza."
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"></td>
		</tr>
		<tr>
			<th>Cargo autoriza : 			</th>
			<td>	<input name="firma_presupuesto_db_cargo_auto" type="text" style="width:62ex" id="firma_presupuesto_db_cargo_auto"  maxlength="60" message="Introduzca el cargo de quien autoriza"
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"></td>
		</tr>
		<tr>
			<th>Grado autoriza : 			</th>
			<td>	<input name="firma_presupuesto_db_grado_auto" type="text" id="firma_presupuesto_db_grado_auto"  style="width:62ex" maxlength="60" message="Introduzca el  de quien autoriza." ></td>
		</tr>
		<tr>
			<th>Nombre autoriza el traspaso : 	</th>
			<td>	<input name="firma_presupuesto_db_nombre_auto_tras" type="text" id="firma_presupuesto_db_nombre_auto_tras"  style="width:62ex" maxlength="60" message="Introduzca el Nombre de quien autoriza el traspaso."
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"></td>
		</tr>	
		<tr>
			<th>Cargo autoriza traspaso : 	</th>
			<td ><input name="firma_presupuesto_db_cargo_auto_tras" type="text" id="firma_presupuesto_db_cargo_auto_tras"  style="width:62ex" maxlength="60"	message="Introduzca el Cargo de quien autoriza el traspaso."
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	</td>
		</tr>
		<tr>
			<th>Grado autoriza traspaso : 	</th>
			<td ><input name="firma_presupuesto_db_gardo_auto_tras" type="text" id="firma_presupuesto_db_gardo_auto_tras"  style="width:62ex" maxlength="60"	 message="Introduzca el Grado de quien autoriza el traspaso."/>	</td>
		</tr>
		<tr>
			<th>Comentario :					</th>
			<td>	<textarea name="firma_presupuesto_db_comentario" id="firma_presupuesto_db_comentario" cols="65" rows="3"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>
