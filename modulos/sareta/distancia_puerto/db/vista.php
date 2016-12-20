<script type='text/javascript'>
var dialog;
$("#sareta_puerto_db_btn_consultar_purto_desde").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/distancia_puerto/db/grid_puerto.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Puertos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre_puerto").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/distancia_puerto/db/sql_grid_puerto.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre_puerto").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre_puerto").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/distancia_puerto/db/sql_grid_puerto.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/distancia_puerto/db/sql_grid_puerto.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/distancia_puerto/db/sql_grid_puerto.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','id_bandera','Bandera','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'id_bandera',index:'id_bandera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre_bandera',index:'nombre_bandera', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_desde').value = ret.id;
									getObj('sareta_puerto_db_desde').value = ret.nombre;
									getObj('vista_id_bandera_desde').value = ret.id_bandera;
									dialog.hideAndUnload();
									$('#form_db_distancia').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre_puerto").focus();
								$('#parametro_cxp_db_nombre_puerto').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});


$("#sareta_puerto_db_btn_consultar_puerto_hasta").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/distancia_puerto/db/grid_puerto.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Puertos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre_puerto").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/distancia_puerto/db/sql_grid_puerto.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre_puerto").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre_puerto").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/distancia_puerto/db/sql_grid_puerto.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/distancia_puerto/db/sql_grid_puerto.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/distancia_puerto/db/sql_grid_puerto.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','id_bandera','Bandera','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'id_bandera',index:'id_bandera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre_bandera',index:'nombre_bandera', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_hasta').value = ret.id;
									getObj('sareta_puerto_db_hasta').value = ret.nombre;
									getObj('vista_id_bandera_hasta').value = ret.id_bandera;
									dialog.hideAndUnload();
									$('#form_db_distancia').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre_puerto").focus();
								$('#parametro_cxp_db_nombre_puerto').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#sareta_distancia_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/distancia_puerto/db/grid_distancia.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Distancia entre Puertos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre_puerto").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/distancia_puerto/db/sql_grid_distancia.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/distancia_puerto/db/sql_grid_distancia.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/distancia_puerto/db/sql_grid_distancia.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/distancia_puerto/db/sql_grid_distancia.php?nd='+nd,
								datatype: "json",
								colNames:['ID','id_org','Bandera Desde','id_Pto_Org','Puerto_Origen','Puerto Origen','id_rec','Bandera Hasta','id_Pto_Rec','Puerto_Recalada','Puerto Recalada','Millas','obs'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_org',index:'id_org', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera_org',index:'bandera_org', width:220,sortable:false,resizable:false},
									{name:'id_pto_org',index:'id_pto_org', width:220,sortable:false,resizable:false,hidden:true},
									{name:'pto_org1',index:'pto_org1', width:220,sortable:false,resizable:false},
									{name:'pto_org',index:'pto_org', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_rec',index:'id_rec', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera_rec',index:'bandera_rec', width:220,sortable:false,resizable:false},
									{name:'id_pto_rec',index:'id_pto_rec', width:220,sortable:false,resizable:false,hidden:true},
									{name:'pto_rec1',index:'pto_rec1', width:220,sortable:false,resizable:false},
									{name:'pto_rec',index:'pto_rec', width:220,sortable:false,resizable:false,hidden:true},
									{name:'millas',index:'millas', width:220,sortable:false,resizable:false},
									{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_distancia').value = ret.id;
									getObj('vista_id_bandera_desde').value = ret.id_org;
									//getObj('').value = ret.bandera_org;
									getObj('vista_id_desde').value = ret.id_pto_org;
									getObj('sareta_puerto_db_desde').value = ret.pto_org;
									
									getObj('vista_id_bandera_hasta').value = ret.id_rec;
									//getObj('').value = ret.bandera_rec;
									getObj('vista_id_hasta').value = ret.id_pto_rec;
									getObj('sareta_puerto_db_hasta').value = ret.pto_rec;
									getObj('sareta_distancia_db_millas').value = ret.millas;
									getObj('sareta_distancia_db_vista_observacion').value = ret.obs;
									getObj('sareta_distancia_db_btn_cancelar').style.display='';
									getObj('sareta_distancia_db_btn_actualizar').style.display='';
									getObj('sareta_distancia_db_btn_eliminar').style.display='';
									getObj('sareta_distancia_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_db_distancia').jVal();
									
									
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre").focus();
								$('#parametro_cxp_db_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});



$("#sareta_distancia_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_puerto').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/distancia_puerto/db/sql.actualizar.php",
			data:dataForm('form_db_distancia'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('sareta_distancia_db_btn_eliminar').style.display='none';
						getObj('sareta_distancia_db_btn_actualizar').style.display='none';
						getObj('sareta_distancia_db_btn_guardar').style.display='';
						clearForm('form_db_distancia');
					});															
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if (html=="MismoPtoInvalido")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />No puede elegirse el mismo puerto…</p></div>",true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#sareta_distancia_db_btn_guardar").click(function() {
	if($('#form_db_distancia').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/distancia_puerto/db/sql.registrar.php",
			data:dataForm('form_db_distancia'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_db_distancia');
					});					
				}
				else if (html=="MismoPtoInvalido")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />No puede elegirse el mismo puerto…</p></div>",true,true);
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
$("#sareta_distancia_db_btn_eliminar").click(function() {
  if (getObj('vista_id_distancia').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/distancia_puerto/db/sql.eliminar.php",
			data:dataForm('form_db_distancia'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_distancia_db_btn_eliminar').style.display='none';
					getObj('sareta_distancia_db_btn_actualizar').style.display='none';
					getObj('sareta_distancia_db_btn_guardar').style.display='';
					clearForm('form_db_distancia');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion con esta Distancia entre Puerto</p></div>",true,true); 
				}
				else 
				{
					
					setBarraEstado(html,true,true);
				}
			}
		});
	}
  }
});


$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("#sareta_distancia_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_distancia_db_btn_cancelar').style.display='';
	getObj('sareta_distancia_db_btn_eliminar').style.display='none';
	getObj('sareta_distancia_db_btn_actualizar').style.display='none';
	getObj('sareta_distancia_db_btn_guardar').style.display='';
	clearForm('form_db_distancia');
});
	
$('#sareta_distancia_db_millas').numeric({allow:'0123456789'});
</script>
<style type="text/css">
<!--
.style4 {color: #33CCFF}
-->
</style>



<div id="botonera">
	<img id="sareta_distancia_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_distancia_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_distancia_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_distancia_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_distancia_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form method="post" id="form_db_distancia" name="form_db_distancia">
<input type="hidden" name="vista_id_distancia" id="vista_id_distancia" />
<input type="hidden" name="vista_id_bandera_desde" id="vista_id_bandera_desde" />
<input type="hidden" name="vista_id_bandera_hasta" id="vista_id_bandera_hasta" />
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Distancia Pto.</th>
	</tr>
	<tr>
    <tr>
		<th colspan="4"><span class="style4">Puerto Origen</span></th>
	</tr>
	    <th>Bandera Desde:		</th>	
	  <td> Venezuela</td>
	</tr>
    <tr>
	<th>Puerto: </th>	
	<td><ul class="input_con_emergente">
              <li>
		<p>
        <input type="hidden" name="vista_id_desde" id="vista_id_desde" />
		  <input name="sareta_puerto_db_desde" type="text" id="sareta_puerto_db_desde"   value="" size="40" maxlength="30"  readonly
						message="Introduzca una Bandera para el Puerto." 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ]{1,30}$/, message:'Bandera  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
         
                        
       
        <li id="sareta_puerto_db_btn_consultar_purto_desde" class="btn_consulta_emergente"></li>
		    </ul></td>
	</tr>	
    <tr>
		<th colspan="4"><span class="style4">Puerto Recalada</span></th>
	</tr>	
         <th>Bandera Hasta:		</th>	
	       <td>Venezuela</td>
	</tr>
    <tr>
	<th>Puerto: </th>	
	<td><ul class="input_con_emergente">
              <li>
		<p>
        <input type="hidden" name="vista_id_hasta" id="vista_id_hasta" />
		  <input name="sareta_puerto_db_hasta" type="text" class="style4" id="sareta_puerto_db_hasta"  value="" size="40" maxlength="30"  readonly
						message="Introduzca una Bandera para el Puerto." 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ]{1,30}$/, message:'Bandera  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
         
                        
        <li id="sareta_puerto_db_btn_consultar_puerto_hasta" class="btn_consulta_emergente"></li>
		    </ul></td>
	</tr>	
    <tr>
    	<th>Millas:</th>
    	<td>
         <input name="sareta_distancia_db_millas" type="text" id="sareta_distancia_db_millas"   value="" size="40" maxlength="30" 
         				message="Introduzca las Millas de Distancia." 
						jVal="{valid:/^[0-9]{1,30}$/, message:'Millas  Invalida', styleType:'cover'}"
						jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Millas: '+$(this).val()]}"  />
      
       	</td>
    </tr>
	<tr>
	<tr>
		<th>Comentario:</th>			
        <td ><textarea name="sareta_distancia_db_vista_observacion" cols="60" 
        id="sareta_distancia_db_vista_observacion"  
        message="Introduzca una Observación. "></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>