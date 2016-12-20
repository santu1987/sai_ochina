<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_usuario_banco_cuentas_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/tesoreria/usuario_banco_cuentas/rp/vista.lst.usuario_banco_cuentas.php¿id_usuario="+getObj('tesoreria_usuario_banco_cuentas_rp_id_usuario').value; 
		openTab("Banco/Chequeras",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_usuario_banco_cuentas_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_usuario_banco_cuentas');
});
//------------------------------------------------------------------------------
$("#tesoreria_usuario_banco_cuentas_rp_btn_consultar_usuario").click(function() {
////
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/usuario_banco_cuentas/rp/grid_usuario_banco_cuentas.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tesoreria-reportes-busq_nombre_usuario_banco").val(); 
					var busq_usuario= jQuery("#tesoreria-reportes-busq_nombre_usuario_banco2").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/usuario_banco_cuentas/rp/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria-reportes-busq_nombre_usuario_banco").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_banco_reportes_dosearch();
												
					});
				$("#tesoreria-reportes-busq_nombre_usuario_banco2").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_banco_reportes_dosearch();
												
					});
					function tesoreria_usuario_banco_reportes_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(tesoreria_usuario_banco_reportes_gridReload,500)
										}
						function tesoreria_usuario_banco_reportes_gridReload()
						{
						var busq_nombre= jQuery("#tesoreria-reportes-busq_nombre_usuario_banco").val(); 
						var busq_usuario= jQuery("#tesoreria-reportes-busq_nombre_usuario_banco2").val(); 
						jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/usuario_banco_cuentas/rp/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			
						}
			}
		});
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/usuario_banco_cuentas/rp/sql_grid_usuario.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Usuario','Unidad'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'unidad',index:'unidad', width:200,sortable:false,resizable:false},
												],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_usuario_banco_cuentas_rp_id_usuario').value = ret.id;
									getObj('tesoreria_usuario_banco_cuentas_rp_usuario').value = ret.nombre;
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
								sortname: 'id_proyecto',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	
});

	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/usuario_banco_cuentas/rp/grid_usuario_banco_cuentas.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/tesoreria/usuario_banco_cuentas/rp/sql_grid_usuario.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Usuario'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									
												],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('tesoreria_usuario_banco_cuentas_rp_id_usuario').value = ret.id;
									getObj('tesoreria_usuario_banco_cuentas_rp_usuario').value = ret.nombre;
							
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
								sortname: 'id_proyecto',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	
});*/
//

//-------------------------------------------------------------------

//-------------------------------------------------------------------
//

//-------------------------------------------------------------------
/*-------------------   Inicio Validaciones  ---------------------------*/
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
/*-------------------   Fin Validaciones  ---------------------------*/
</script>

<div id="botonera">
	<img id="form_rp_usuario_banco_cuentas_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_usuario_banco_cuentas_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_usuario_banco_cuentas" id="form_rp_usuario_banco_cuentas">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Relaci&oacute;n usuario Bancos </th>
	  </tr>
		<!--<tr>
			<th>Selección</th>
			<td>
				<input id="tesoreria_banco_chequeras_rp_opt_todas" name="presupuesto_ley_pr_opt" type="radio" value="0" checked="checked"> Todas
				<input id="presupuesto_ley_pr_opt_unidad" name="presupuesto_ley_pr_opt" type="radio" value="1"> Una (UNIDAD EJECUTORA)			
			</td>
		</tr>-->
		
		<tr>
          <th>Usuario</th>
		  <td><ul class="input_con_emergente">
              <li>
                <input name="tesoreria_usuario_banco_cuentas_rp_usuario" type="text" id="tesoreria_usuario_banco_cuentas_rp_usuario"    size="50" maxlength="80" message="Seleccione el Nombre de un usuario" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                <input type="hidden" id="tesoreria_usuario_banco_cuentas_rp_id_usuario" name="tesoreria_usuario_banco_cuentas_rp_id_usuario"/>
              <li id="tesoreria_usuario_banco_cuentas_rp_btn_consultar_usuario" class="btn_consulta_emergente"></li>
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>