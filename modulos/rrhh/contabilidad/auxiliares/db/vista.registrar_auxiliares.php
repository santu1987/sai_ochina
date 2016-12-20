<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
//
//
$("#contabilidad_auxiliares_db_btn_consultar").click(function() {
url="modulos/contabilidad/auxiliares/db/sql_contabilidad_auxiliares_cons.php?cuenta_contable="+getObj('contabilidad_auxiliares_db_id_cuenta_contable').value;
//alert(url);
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php?cuenta_contable="+getObj('contabilidad_auxiliares_db_id_cuenta_contable').value,
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de auxiliares', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#contabilidad_auxiliares_nombre_consulta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_contabilidad_auxiliares_cons.php?busq_nombre="+busq_nombre+"&cuenta_contable="+getObj('contabilidad_auxiliares_db_id_cuenta_contable').value,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#contabilidad_auxiliares_nombre_consulta").keypress(function(key)
				{
						auxiliares_dosearch();
												
					});
					function auxiliares_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(auxiliares_gridReload,500)
										}
						function auxiliares_gridReload()
						{
							var busq_nombre= jQuery("#contabilidad_auxiliares_nombre_consulta").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_contabilidad_auxiliares_cons.php?busq_nombre="+busq_nombre+"&cuenta_contable="+getObj('contabilidad_auxiliares_db_id_cuenta_contable').value,page:1}).trigger("reloadGrid"); 
							url="modulos/contabilidad/auxiliares/db/sql_contabilidad_auxiliares_cons.php?busq_nombre="+busq_nombre;
							//alert(url);
						}
/////////////////////////////////////-2DA FORMA DE REALIZAR-////////////////////////////////////////////
				//	$("#programa-consultas-busq_nombre").keypress(function(key){
				//	var busq_nombre= jQuery("#programa-consultas-busq_nombre").val(); 
				//	jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			//	});
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
								url:'modulos/contabilidad/auxiliares/db/sql_contabilidad_auxiliares_cons.php?nd='+nd+"&cuenta_contable="+getObj('contabilidad_auxiliares_db_id_cuenta_contable').value,
								datatype: "json",
								colNames:['ID','Cuenta Contable','Auxiliar','Nombre','Comentario','Nombre','Comentario','desc_cuenta'],
								colModel:[
									{name:'id_aux',index:'id_aux', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_auxiliares',index:'cuenta_auxiliares', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre2',index:'nombre2', width:50,sortable:false,resizable:false},
									{name:'comentario2',index:'comentario2', width:50,sortable:false,resizable:false},
									{name:'desc_cuenta',index:'desc_cuenta', width:50,sortable:false,resizable:false,hidden:true},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('contabilidad_vista_auxiliares').value = ret.id_aux;
								//	getObj('contabilidad_auxiliar_db_cuenta_contable').value=ret.cuenta_contable;
								//	getObj('contabilidad_auxiliares_db_id_cuenta_contable').value=ret.id_cuenta_cont;
								//	getObj('contabilidad_auxiliares_db_desc').value=ret.desc_cuenta;
									getObj('contabilidad_auxiliares_db_cuenta_auxiliar').value = ret.cuenta_auxiliares;
									getObj('contabilidad_auxiliares_db_nombre').value = ret.nombre;
									getObj('contabilidad_auxiliares_db_comentario').value = ret.comentario;
									getObj('contabilidad_auxiliar_db_btn_cancelar').style.display='';
									getObj('contabilidad_auxiliares_db_btn_actualizar').style.display='';
									//getObj('contabilidad_auxiliares_db_btn_eliminar').style.display='';
									getObj('contabilidad_auxiliares_db_btn_guardar').style.display='none';

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
								sortname: 'id_aux',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
	///
/*$("#contabilidad_auxiliares_db_btn_eliminar").click(function() {
	if($('#form_contabilidad_db_auxiliares').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/auxiliares/db/sql.eliminar.php",
			data:dataForm('form_contabilidad_db_auxiliares'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					clearForm('form_contabilidad_db_auxiliares');
					limpiar_auxiliar();
				}
				else if (html=="ExisteRelacion")
				{

				setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else
				{
					setBarraEstado(html,true,true);					
				}			
			}
		});
	}
});*/

	//
$("#contabilidad_auxiliares_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_contabilidad_db_auxiliares').jVal())
	{
		$.ajax (
		{
			url: "modulos/contabilidad/auxiliares/db/sql.actualizar.php",
			data:dataForm('form_contabilidad_db_auxiliares'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			//alert(html);
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
				    getObj('contabilidad_auxiliar_db_btn_cancelar').style.display='';
					getObj('contabilidad_auxiliares_db_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					//getObj('contabilidad_auxiliares_db_btn_eliminar').style.display='none';
					clearForm('form_contabilidad_db_auxiliares');
				}
			/*	else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('contabilidad_auxiliar_db_btn_cancelar').style.display='';
					getObj('contabilidad_auxiliares_db_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_db_auxiliares');
				}*/
			
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#contabilidad_auxiliares_db_btn_guardar").click(function() {
	if($('#form_contabilidad_db_auxiliares').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/auxiliares/db/sql.registrar.php",
			data:dataForm('form_contabilidad_db_auxiliares'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_contabilidad_db_auxiliares');
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					clearForm('form_contabilidad_db_auxiliares');
					}
					else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					
				}
			
			}
		});
	}
});

$("#tesoreria_moneda_db_btn_consultar_moneda").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/moneda/db/grid_moneda.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Impuesto', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/tesoreria/moneda/db/cmb.sql.organismo.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo', 'Organismo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false},
									{name:'organismo',index:'organismo', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
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
});
$("#contabilidad_vista_btn_consultar_auxiliar").click(function() {
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
///
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_cuenta_contable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-cuenta-contable-busqueda2").keypress(
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
					var busq_nom= $("#consulta-cuenta-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom;	
					//alert(url);
				}
				//
				$("#consulta-cuenta-contable-busqueda-nombre").keypress(
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
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta;
                 // ¿ alert(url);				
				}
			}
		}
	);
///						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Cuenta', 'Denominacion','Tipo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#contabilidad_auxiliar_db_cuenta_contable').val(ret.cuenta_contable);
									getObj('contabilidad_auxiliares_db_id_cuenta_contable').value=ret.id;
									getObj('contabilidad_auxiliares_db_desc').value=ret.nombre;
					
//									$('#contabilidad_auxiliares_db_id_cuenta_contable').val(ret.id);
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
/////////////////////////////////////////////////////
function consulta_automatica_auxiliar()
{
	$.ajax({
			url:"modulos/contabilidad/auxiliares/db/sql_grid_auxi.php",
            data:dataForm('form_contabilidad_db_auxiliares'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
				//getObj('').value = recordset[0];
					//getObj('contabilidad_auxiliares_db_id_cuenta_contable').value=recordset[1];
				//	getObj('contabilidad_auxiliar_db_cuenta_contable').value=recordset[2];
					getObj('contabilidad_auxiliares_db_nombre').value=recordset[2];
					getObj('contabilidad_vista_auxiliares').value = recordset[0];
						getObj('contabilidad_auxiliar_db_btn_cancelar').style.display='';									getObj('contabilidad_auxiliares_db_btn_actualizar').style.display='';
									getObj('contabilidad_auxiliares_db_btn_guardar').style.display='none';

				}
				else
				if(recordset=='vacio')
				{	
					//getObj('contabilidad_auxiliares_db_nombre').value="";
				//getObj('contabilidad_auxiliares_db_cuenta_auxiliar').value="";
				/*	getObj('contabilidad_auxiliares_db_id_cuenta_contable').value="";
					getObj('contabilidad_auxiliar_db_cuenta_contable').value="";
					getObj('contabilidad_auxiliares_db_nombre').value="";
					getObj('contabilidad_auxiliares_db_comentario').value="";*/
					//getObj('contabilidad_auxiliares_db_btn_eliminar').style.display='none';
				//	getObj('contabilidad_auxiliar_db_cuenta_contable').value=recordset[2];

				}
				
			 }
		});	 	 
}
///

$("#contabilidad_auxiliar_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
	//getObj('contabilidad_auxiliares_db_btn_eliminar').style.display='none';
	getObj('contabilidad_auxiliares_db_btn_actualizar').style.display='none';
	getObj('contabilidad_auxiliares_db_btn_consultar').style.display='';
	clearForm('form_contabilidad_db_auxiliares');
	getObj('contabilidad_auxiliares_db_id_cuenta_contable')="";
});
function limpiar_auxiliar()
{
	setBarraEstado("");
	getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
	//getObj('contabilidad_auxiliares_db_btn_eliminar').style.display='none';
	getObj('contabilidad_auxiliares_db_btn_actualizar').style.display='none';
	getObj('contabilidad_auxiliares_db_btn_consultar').style.display='';
	clearForm('form_contabilidad_db_auxiliares');
	
}
function cuenta_contable_cod()
{///alert("entro");
$.ajax({
			url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_cont_cod.php",
            data:dataForm('form_contabilidad_db_auxiliares'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
					recordset = recordset.split("*");
					getObj('contabilidad_auxiliares_db_id_cuenta_contable').value=recordset[0];
					getObj('contabilidad_auxiliar_db_cuenta_contable').value=recordset[1];
					getObj('contabilidad_auxiliares_db_desc').value=recordset[2];
				}
				else
				{
					getObj('contabilidad_auxiliar_db_cuenta_contable').value="";
				}
			 }
		});		
}
</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

//$('#contabilidad_auxiliares_db_nombre').alpha({allow:' áéíóúÄÉÍÓÚ. '});
$('#contabilidad_auxiliares_db_cuenta_auxiliar').numeric({});
$('#contabilidad_auxiliar_db_cuenta_contable').numeric({});
$("input, select, textarea").bind("focus", function(){
	/////////////////////////////
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	
</script>

<div id="botonera">
	<img id="contabilidad_auxiliar_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="contabilidad_auxiliares_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/> 
	<img src="imagenes/null.gif" width="39" class="btn_consultar" id="contabilidad_auxiliares_db_btn_consultar" />
	<img id="contabilidad_auxiliares_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
	<img id="contabilidad_auxiliares_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
<form method="post" id="form_contabilidad_db_auxiliares" name="form_contabilidad_db_auxiliares">
<input type="hidden"  id="contabilidad_vista_auxiliares" name="contabilidad_vista_auxiliares"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Auxiliares</th>
	</tr>
    <tr>
		<th>Cuenta Contable:</th>
		 <td>
		 <ul class="input_con_emergente">
		 <li>
		    	<input type="text" name="contabilidad_auxiliar_db_cuenta_contable" id="contabilidad_auxiliar_db_cuenta_contable"  size='12' maxlength="12"
				message="Introduzca la cuenta contable"  onblur="cuenta_contable_cod()"
				/>
		       <input type="text" id="contabilidad_auxiliares_db_desc"  name="contabilidad_auxiliares_db_desc" readonly="readonly">
                <input type="hidden" id="contabilidad_auxiliares_db_id_cuenta_contable" name="contabilidad_auxiliares_db_id_cuenta_contable" />
		 </li>
		<li id="contabilidad_vista_btn_consultar_auxiliar" class="btn_consulta_emergente"></li>
	    </ul>	  </td>	
    </tr>    
	<tr>
		<th>Cuenta Auxiliar:</th>
		 <td>
   	<input type="text" name="contabilidad_auxiliares_db_cuenta_auxiliar" id="contabilidad_auxiliares_db_cuenta_auxiliar"  size='12' maxlength="8" onblur="consulta_automatica_auxiliar()" onchange="consulta_automatica_auxiliar()"
				message="Introduzca la cuenta del auxiliar" 
					jval="{valid:/^[0-9]{1,8}$/, message:'Codigo Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>	</tr>
	
	
	<tr>
		<th>Nombre:		</th>	
	    <td>	
		<input name="contabilidad_auxiliares_db_nombre" type="text" id="contabilidad_auxiliares_db_nombre"   value="" size="40" maxlength="60" message="Introduzca un Nombre de la cuenta auxiliar. Ejem: 'Banco Bol&iacute;var' " 
						 /><!--jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"-->	   </td>
   </tr>
    
    
    
	<tr>
		<th>Comentarios:</th>
		<td><textarea  name="contabilidad_auxiliares_db_comentario" cols="60" id="contabilidad_auxiliares_db_comentario" message="Introduzca una Observaci&oacute;n. Ejem:'Esta cuenta es ...' " style="width:422px"></textarea>		</td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
</table>
<input   type="hidden" name="contabilidad_auxiliares_db_id_aux"  id="contabilidad_auxiliares_db_id_aux" />
</form>