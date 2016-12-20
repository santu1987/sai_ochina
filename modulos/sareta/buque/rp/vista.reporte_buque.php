<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_buque_btn_imprimir").click(function() {
var v1 =getObj('sareta_buque_rp_texto_nombre').value;
var v2 =getObj('sareta_buque_rp_texto_bandera').value;
var v3 =getObj('sareta_buque_rp_texto_actividad').value;
var v4 =getObj('sareta_buque_rp_texto_clase').value;
		url="pdf.php?p=modulos/sareta/buque/rp/vista.lst.de_buque.php¿busq_nombre_buque="+v1+";"+v2+"%"+v3+"*"+v4; 
		openTab("Listado/Buques",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_sareta_buque_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_buque_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_buque_rp_btn_consultar_buque").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/buque/rp/grid_buque.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Buques', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_rp_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/rp/sql_grid_buque.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_rp_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#parametro_cxp_rp_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/rp/sql_grid_buque.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/buque/rp/sql_grid_buque.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/buque/rp/sql_grid_buque.php?nd='+nd,
								datatype: "json",
								colNames:['id','Matricula','Call Sign','Nombre','nombre','id_bandera','Bandera','bandera',
								'R. Bruto','id_actividad','Actividad','actividad','id_clase','Clase','clase','Nac/Ext','Pago Anual',
								'id_ley','Ley','ley','Exonerado','com'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'matricula',index:'matricula', width:220,sortable:false,resizable:false},
									{name:'call_sign',index:'call_sign', width:220,sortable:false,resizable:false},
									
									{name:'nombre1',index:'nombre1', width:220,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_bandera',index:'id_bandera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera1',index:'bandera1', width:220,sortable:false,resizable:false},
									{name:'bandera',index:'bandera', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'r_bruto',index:'r_bruto', width:220,sortable:false,resizable:false},
									
									{name:'id_actividad',index:'id_actividad', width:220,sortable:false,resizable:false,hidden:true},
									{name:'actividad1',index:'actividad1', width:220,sortable:false,resizable:false},
									{name:'actividad',index:'actividad', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_clase',index:'id_clase', width:220,sortable:false,resizable:false,hidden:true},
									{name:'clase1',index:'clase1', width:220,sortable:false,resizable:false},
									{name:'clase',index:'clase', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'nac',index:'nac', width:220,sortable:false,resizable:false},
									{name:'pago_anual',index:'pago_anual', width:220,sortable:false,resizable:false},
									
									{name:'id_ley',index:'id_ley', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ley1',index:'ley1', width:220,sortable:false,resizable:false},
									{name:'ley',index:'ley', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'exonerado',index:'exonerado', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('sareta_buque_rp_texto_nombre').value = ret.nombre;
									dialog.hideAndUnload();
									$('#form_rp_buque_consulta').jVal();
									
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_rp_nombre").focus();
								$('#parametro_cxp_rp_nombre').alpha({allow:'0123456789 '});
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
	
//--------------------------------------------------------------------------------------------
$("#sareta_buque_rp_btn_consultar_bandera").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/buque/rp/grid_bandera.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Banderas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_rp_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/rp/sql_grid_bandera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_rp_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#parametro_cxp_rp_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/rp/sql_grid_bandera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/buque/rp/sql_grid_bandera.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/buque/rp/sql_grid_bandera.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Abreviatura','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'abreviatura',index:'abreviatura', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);	
									getObj('sareta_buque_rp_texto_bandera').value = ret.nombre;
									dialog.hideAndUnload();
									$('#form_rp_buque_consulta').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_rp_nombre").focus();
								$('#parametro_cxp_rp_nombre').alpha({allow:'0123456789 '});
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
//-------------------------------------------------------------------------------
$("#sareta_buque_rp_btn_consultar_actividad").click(function() {
var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/sareta/buque/rp/grid_tipo_actividad.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Tipo de Actividades',modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/buque/rp/sql_grid_tipo_actividad.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								
									getObj('sareta_buque_rp_texto_actividad').value = ret.nombre;
		
									dialog.hideAndUnload();
									$('#form_rp_buque_consulta').jVal();
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
});
//---------------------------------------------------------------------------

$("#sareta_buque_rp_btn_consultar_clase").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/buque/rp/grid_clases_buques.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Clases de Buques', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_rp_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/rp/sql_grid_clases_buques.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_rp_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#parametro_cxp_rp_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/rp/sql_grid_clases_buques.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/buque/rp/sql_grid_clases_buques.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/buque/rp/sql_grid_clases_buques.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Abreviatura','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'abreviatura',index:'abreviatura', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('sareta_buque_rp_texto_clase').value = ret.nombre;	
									dialog.hideAndUnload();
									$('#form_rp_buque_consulta').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_rp_nombre").focus();
								$('#parametro_cxp_rp_nombre').alpha({allow:'0123456789 '});
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
/*-------------------   Inicio Validaciones  ---------------------------*/
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
/*-------------------   Fin Validaciones  ---------------------------*/
$('#sareta_bandera_rp_texto_nombre_bandera').alpha({allow:' áéíóúÁÉÍÓÚñ'});
</script>

<div id="botonera">
	<img id="form_rp_sareta_buque_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_buque_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_buque_consulta" id="form_rp_buque_consulta">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Buques</th>
	  </tr>
		
		<tr>
          <th>Buque:</th>
			<td>
			<ul class="input_con_emergente">
              <li>
                <input name="sareta_buque_rp_texto_nombre" type="text" id="sareta_buque_rp_texto_nombre"    
				size="50" maxlength="30" message="Escribir el Nombre Para el Buque. <br><br>
				Para Seleccionar Todo un Listado de Buques Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
				jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]
				{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
				jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
                <input type="hidden" id="sareta_buque_rp_id_buque" name="sareta_buque_rp_id_buque"/>
              <li id="sareta_buque_rp_btn_consultar_buque" class="btn_consulta_emergente"></li>
		    </ul>
			</td>
		</tr>
        <tr>
          <th>Bandera:</th>
			<td>
			<ul class="input_con_emergente">
              <li>
                <input name="sareta_buque_rp_texto_bandera" type="text" id="sareta_buque_rp_texto_bandera"  readonly="readonly"  
				size="50" maxlength="30" message="Busque el Nombre de la Bandera." 
				jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]
				{1,60}$/,message:'Bandera Invalido', styleType:'cover'}"
				jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Bandera: '+$(this).val()]}" />
 <input type="hidden" id="sareta_buque_rp_id_bandera" name="sareta_buque_rp_id_bandera"/>
              <li id="sareta_buque_rp_btn_consultar_bandera" class="btn_consulta_emergente"></li>
		    </ul>
			</td>
		</tr>
         <tr>
          <th>Actividad:</th>
			<td>
			<ul class="input_con_emergente">
              <li>
                <input name="sareta_buque_rp_texto_actividad" type="text" id="sareta_buque_rp_texto_actividad"  readonly="readonly"  
				size="50" maxlength="30" message="Busque el Nombre de la Actividad." 
				jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]
				{1,60}$/,message:'Actividad Invalido', styleType:'cover'}"
				jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Actividad: '+$(this).val()]}" />
 <input type="hidden" id="sareta_buque_rp_id_actividad" name="sareta_buque_rp_id_actividad"/>
              <li id="sareta_buque_rp_btn_consultar_actividad" class="btn_consulta_emergente"></li>
		    </ul>
			</td>
		</tr>
        <tr>
          <th>Clase:</th>
			<td>
			<ul class="input_con_emergente">
              <li>
                <input name="sareta_buque_rp_texto_clase" type="text" id="sareta_buque_rp_texto_clase"  readonly="readonly"  
				size="50" maxlength="30" message="Busque la Clase de Buque." 
				jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]
				{1,60}$/,message:'Clase Invalido', styleType:'cover'}"
				jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Clase: '+$(this).val()]}" />
 <input type="hidden" id="sareta_buque_rp_id_clase" name="sareta_buque_rp_id_clase"/>
              <li id="sareta_buque_rp_btn_consultar_clase" class="btn_consulta_emergente"></li>
		    </ul>
			</td>
		</tr>
		<tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>