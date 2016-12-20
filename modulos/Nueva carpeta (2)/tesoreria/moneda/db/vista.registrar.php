<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
//
//
$("#tesoreria_moneda_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/moneda/db/vista.grid_tesoreria_nombre2.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Moneda', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tesoreria_moneda_db_nombre2").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/moneda/db/sql_tesoreria_moneda_nombre2.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_moneda_db_nombre2").keypress(function(key)
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
							var busq_nombre= jQuery("#tesoreria_moneda_db_nombre2").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/moneda/db/sql_tesoreria_moneda_nombre2.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/moneda/db/sql_tesoreria_moneda_nombre2.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Denominacion','aa','aa','aa','aa'],
								colModel:[
									{name:'id_moneda',index:'id_moneda', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_moneda',index:'codigo_moneda', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'id_organismo',index:'id_organismo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'organismo',index:'organismo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true},
									{name:'denominacion',index:'denominacion', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_moneda_db_id').value = ret.id_moneda;
									getObj('tesoreria_moneda_db_codigo').value=ret.codigo_moneda;
									getObj('tesoreria_moneda_db_nombre').value = ret.nombre;
									getObj('tesoreria_moneda_db_observacion').value = ret.comentario;
									getObj('tesoreria_moneda_db_btn_cancelar').style.display='';
									getObj('tesoreria_moneda_db_btn_actualizar').style.display='';
									//getObj('tesoreria_moneda_db_btn_eliminar').style.display='';
									getObj('tesoreria_moneda_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#tesoreria_moneda_db_nombre2").focus();
								$('#tesoreria_moneda_db_nombre2').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_moneda',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
$("#tesoreria_moneda_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_tesoreria_db_moneda').jVal())
	{
		$.ajax (
		{
			url: "modulos/tesoreria/moneda/db/sql.actualizar.php",
			data:dataForm('form_tesoreria_db_moneda'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					
				    getObj('tesoreria_moneda_db_btn_cancelar').style.display='';
					getObj('tesoreria_moneda_db_btn_actualizar').style.display='none';
					//getObj('tesoreria_moneda_db_btn_eliminar').style.display='none';
					getObj('tesoreria_moneda_db_btn_guardar').style.display='';
					clearForm('form_tesoreria_db_moneda');
				  	//getObj("tesoreria_moneda_db_fecha").value = "<?=  date("d/m/Y"); ?>";
					getObj("tesoreria_moneda_db_fecha_oculto").value = "<?=  date("d/m/Y"); ?>";	
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('tesoreria_moneda_db_btn_cancelar').style.display='';
					getObj('tesoreria_moneda_db_btn_actualizar').style.display='none';
					//getObj('tesoreria_moneda_db_btn_eliminar').style.display='none';
					getObj('tesoreria_moneda_db_btn_guardar').style.display='';
					clearForm('form_tesoreria_db_moneda');
					//getObj('tesoreria_moneda_db_fecha').value = "<?= date("d/m/Y"); ?>";
					getObj('tesoreria_moneda_db_fecha_oculto').value = "<?= date("d/m/Y"); ?>";
					
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#tesoreria_moneda_db_btn_guardar").click(function() {
	if($('#form_tesoreria_db_moneda').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/tesoreria/moneda/db/sql.registrar.php",
			data:dataForm('form_tesoreria_db_moneda'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_tesoreria_db_moneda');
					getObj('tesoreria_moneda_db_fecha').value = "<?= date("d/m/Y"); ?>";
					getObj('tesoreria_moneda_db_fecha_oculto').value = "<?= date("d/m/Y"); ?>";
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					clearForm('form_tesoreria_db_moneda');
					getObj('tesoreria_moneda_db_fecha').value = "<?= date("d/m/Y"); ?>";
					getObj('tesoreria_moneda_db_fecha_oculto').value = "<?= date("d/m/Y"); ?>";
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
								loadtext: "Recuperando InformaciÛn del Servidor",		
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
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
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
$("#tesoreria_moneda_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('tesoreria_moneda_db_btn_guardar').style.display='';
	//getObj('tesoreria_moneda_db_btn_eliminar').style.display='none';
	getObj('tesoreria_moneda_db_btn_actualizar').style.display='none';
	getObj('tesoreria_moneda_db_btn_consultar').style.display='';
	clearForm('form_tesoreria_db_moneda');
	//getObj("tesoreria_moneda_db_fecha").value = "<?=  date("d/m/Y"); ?>";
	getObj("tesoreria_moneda_db_fecha_oculto").value = "<?=  date("d/m/Y"); ?>";
});

/*
$("#tesoreria_moneda_db_btn_eliminar").click(function() {
  if (getObj('tesoreria_vista_moneda').value !=""){
	if(confirm("øDesea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/tesoreria/moneda/db/sql.eliminar.php",
			data:dataForm('form_tesoreria_db_moneda'),
			type:'POST',
			cache: false,
			success: function(html)
			{ 
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('tesoreria_moneda_db_btn_eliminar').style.display='none';
					getObj('tesoreria_moneda_db_btn_actualizar').style.display='none';
					getObj('tesoreria_moneda_db_btn_guardar').style.display='';
					clearForm('form_tesoreria_db_moneda');
					//getObj("tesoreria_moneda_db_fecha").value = "<?=  date("d/m/Y"); ?>";
					getObj("tesoreria_moneda_db_fecha_oculto").value = "<?=  date("d/m/Y"); ?>";
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
  }
});
*/
//consultas automaticas
function consulta_automatica_monedareg()
{
	if (getObj('tesoreria_moneda_db_codigo')!=" ")
	{
	$.ajax({
			url:"modulos/tesoreria/moneda/db/sql_grid_moneda.php",
            data:dataForm('form_tesoreria_db_moneda'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
					if(recordset)
				{
					recordset = recordset.split("*");
					getObj('tesoreria_vista_moneda').value =recordset[0];
					getObj('tesoreria_moneda_db_nombre').value =recordset[2];
					getobj('tesoreria_moneda_db_observacion').value=recordset[3];
					//fd=recordset[4].substr(0, 10);
					//fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4); 
					//getObj('tesoreria_moneda_db_fecha').value = fds;
					getObj('tesoreria_moneda_db_btn_cancelar').style.display='';
					getObj('tesoreria_moneda_db_btn_actualizar').style.display='';
					getObj('tesoreria_moneda_db_btn_guardar').style.display='none'
				 }
				 else
				 {
				 	setBarraEstado("");
					getObj('tesoreria_moneda_db_btn_guardar').style.display='';
					//getObj('tesoreria_moneda_db_btn_eliminar').style.display='none';
					getObj('tesoreria_moneda_db_btn_actualizar').style.display='none';
					getObj('tesoreria_moneda_db_btn_consultar').style.display='';
					a=getObj('tesoreria_moneda_db_codigo').value;
					clearForm('form_tesoreria_db_moneda');
					getObj('tesoreria_moneda_db_codigo').value=a;
					getObj("tesoreria_moneda_db_fecha").value = "<?=  date("d/m/Y"); ?>";
					getObj("tesoreria_moneda_db_fecha_oculto").value = "<?=  date("d/m/Y"); ?>";
				 }
			 }
		});	 	 
	}	
}
//
$('#tesoreria_moneda_db_codigo').change(consulta_automatica_monedareg)

</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#tesoreria_moneda_db_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_moneda_db_organismo').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_moneda_db_fecha').numeric({allow:'/-'});
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
	<img id="tesoreria_moneda_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <!--<img id="tesoreria_moneda_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/> --><img id="tesoreria_moneda_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" /><img id="tesoreria_moneda_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
	<img id="tesoreria_moneda_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
<form method="post" id="form_tesoreria_db_moneda" name="form_tesoreria_db_moneda">
<input type="hidden"  id="tesoreria_vista_moneda" name="tesoreria_vista_moneda"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Registrar Moneda  </th>
	</tr>
	<tr>
		<th>CÛdigo:</th>
		 <td>
		    	<input type="text" name="tesoreria_moneda_db_codigo" id="tesoreria_moneda_db_codigo"  style="width:6ex;" size='4' maxlength="4"
				 onchange="consulta_automatica_monedareg" onclick="consulta_automatica_monedareg"message="Introduzca el Codigo de la moneda." 
					jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/></tr>
	<tr>
		<th>Nombre:		</th>	
	    <td>	
		<input name="tesoreria_moneda_db_nombre" type="text" id="tesoreria_moneda_db_nombre"   value="" size="40" maxlength="60" message="Introduzca un Nombre del moneda. Ejem: 'BolÌvar' " 
						jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	   </td>
   </tr>
    
    
    
	<tr>
		<th>Observaci&oacute;n:</th>
		<td><textarea  name="tesoreria_moneda_db_observacion" cols="60" id="tesoreria_moneda_db_observacion" message="Introduzca una ObservaciÛn. Ejem:'Este moneda es ...' " style="width:422px"></textarea>		</td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
</table>
<input   type="hidden" name="tesoreria_moneda_db_id"  id="tesoreria_moneda_db_id" />
</form>