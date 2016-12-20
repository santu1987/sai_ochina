
<?php
// El siguiente codigo realiza la consulta de los estados para llenar el campo que se refiere al mismo
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM estado";
$rs_estado =& $conn->Execute($sql);

while (!$rs_estado->EOF) {
	
	$opt_estado.="<option value='".$rs_estado->fields("id_es")."' >".$rs_estado->fields("nom_es")."</option>";
$rs_estado->MoveNext();
}
?>

<script type='text/javascript'>

//------------------------------------------------Codigo para Consultar las Agencias Navieras 

var dialog;
$("#sareta_agencia_naviera_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/agencia_naviera/db/grid_agencia_naviera.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Agencias Navieras', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/agencia_naviera/db/sql_grid_agencia_naviera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/agencia_naviera/db/sql_grid_agencia_naviera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/agencia_naviera/db/sql_grid_agencia_naviera.php?nombre="+busq_nombre;
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
								loadtext: "Recuperando Informaci√≥n del Servidor",		
								url:'modulos/sareta/agencia_naviera/db/sql_grid_agencia_naviera.php?nd='+nd,
								datatype: "json",
								colNames:['id_agencia_naviera','id_delegacion','Nombre','nom','RIF','NIT','Direcci&oacute;n','id_estado','Estado','&Aacute;rea','Zona','Apartado','Telefono ','Telefono 2','Fax ','Fax 2','Pag Web','pag_web','Correo','Contacto','cont','Cedula','Cargo','Codigo Auxiliar','Comentario'],
								colModel:[
										{name:'id_agencia_naviera',index:'id_agencia_naviera', width:220,sortable:false,resizable:false,hidden:true},
										{name:'id_delegacion',index:'id_delegacion', width:220,sortable:false,resizable:false,hidden:true},
										{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
										{name:'nom',index:'nom', width:220,sortable:false,resizable:false,hidden:true},
										{name:'rif',index:'rif', width:220,sortable:false,resizable:false},
										{name:'nit',index:'nit', width:220,sortable:false,resizable:false,hidden:true},
										{name:'direccion',index:'di2  Äion', width:220,sortable:false,resizable:false,hidden:true},
										{name:'id_estado',index:'id_estado', width:220,sortable:false,resizable:false,hidden:true},
										{name:'estado',index:'estado', width:220,sortable:false,resizable:false,hidden:true},
										{name:'area',index:'area', width:220,sortable:false,resizable:false},
										{name:'zona',index:'zona', width:220,sortable:false,resizable:false,hidden:true},
										{name:'apartado',index:'apartado', width:220,sortable:false,resizable:false,hidden:true},
										{name:'telefono1',index:'telefono1', width:220,sortable:false,resizable:false},
										{name:'telefono2',index:'telefono2', width:220,sortable:false,resizable:false,hidden:true},
										{name:'fax1',index:'fax1', width:220,sortable:false,resizable:false,hidden:true},
										{name:'fax2',index:'fax2', width:220,sortable:false,resizable:false,hidden:true},
										{name:'pag',index:'pag', width:220,sortable:false,resizable:false},
										{name:'pag_web',index:'pag_web', width:220,sortable:false,resizable:false,hidden:true},
										{name:'correo',index:'correo', width:220,sortable:false,resizable:false,hidden:true},
										{name:'contacto',index:'contacto', width:220,sortable:false,resizable:false},
										{name:'cont',index:'cont', width:220,sortable:false,resizable:false,hidden:true},
										{name:'cedula',index:'cedula', width:220,sortable:false,resizable:false,hidden:true},
										{name:'cargo',index:'cargo', width:220,sortable:false,resizable:false,hidden:true},
										{name:'auxiliar',index:'auxiliar', width:220,sortable:false,resizable:false,hidden:true},
										{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								//trasferi los datos a los campos del formulario
									getObj('vista_id_agencia_naviera').value = ret.id_agencia_naviera;
									
									getObj('sareta_agencia_naviera_db_nombre').value = ret.nom;
									getObj('sareta_agencia_naviera_db_rif').value = ret.rif;
									getObj('sareta_agencia_naviera_db_nit').value =ret.nit;
									getObj('sareta_agencia_naviera_db_direccion').value =ret.direccion;
									getObj('sareta_agencia_naviera_db_estado').value = ret.id_estado;
									getObj('sareta_agencia_naviera_db_codigo_area').value =ret.area;
									getObj('sareta_agencia_naviera_db_zona').value =ret.zona;
									getObj('sareta_agencia_naviera_db_apartado').value = ret.apartado;
									getObj('sareta_agencia_naviera_db_telefono').value = ret.telefono1;
									getObj('sareta_agencia_naviera_db_telefono1').value = ret.telefono2;
									getObj('sareta_agencia_naviera_db_fax').value = ret.fax1;
									getObj('sareta_agencia_naviera_db_fax1').value = ret.fax2;	
									getObj('sareta_agencia_naviera_db_pag_web').value = ret.pag_web;
									getObj('sareta_agencia_naviera_db_correo').value = ret.correo;
									getObj('sareta_agencia_naviera_db_contacto').value = ret.cont;
									getObj('sareta_agencia_naviera_db_cedula').value = ret.cedula;
									getObj('sareta_agencia_naviera_db_cargo').value = ret.cargo;
									getObj('sareta_agencia_naviera_db_codigo_auxiliar').value = ret.auxiliar;
									getObj('sareta_agencia_naviera_db_obs').value = ret.obs;
									getObj('sareta_agencia_naviera_db_btn_cancelar').style.display='';
									getObj('sareta_agencia_naviera_db_btn_actualizar').style.display='';
									getObj('sareta_agencia_naviera_db_btn_eliminar').style.display='';
									getObj('sareta_agencia_naviera_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_db_agencia_naviera').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre_organismo").focus();
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

//-----------------------------Codigo para Atualizar las Agencias Navieras Registradas 

$("#sareta_agencia_naviera_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_agencia_naviera').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/agencia_naviera/db/sql.actualizar.php",
			data:dataForm('form_db_agencia_naviera'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('sareta_agencia_naviera_db_btn_eliminar').style.display='none';
						getObj('sareta_agencia_naviera_db_btn_actualizar').style.display='none';
						getObj('sareta_agencia_naviera_db_btn_guardar').style.display='';
						clearForm('form_db_agencia_naviera');
						getObj('sareta_agencia_naviera_db_estado').selectedIndex =0;
					});															
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if (html=="area_telefono")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Es necesario un Codigo de &Aacute;rea y un Tel√©fono</p></div>",true,true);
				}
				else if (html=="codigo_area")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Es necesario un Codigo de &Aacute;rea </p></div>",true,true);
				}
				else if (html=="no_telefono")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Es necesario un Tel√©fono</p></div>",true,true);
				}
				else if(html=="Abreviatura_Existe"){
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Es necesario un tel√©fono </p></div>",true,true);

				}
				else if(html=="El_rif_Exite"){
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>El rif Exite para otra Agencia Naviera</p></div>",true,true);

				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

//------------------------------------------------Codigo para guardar las Agencias Navieras 

$("#sareta_agencia_naviera_db_btn_guardar").click(function() {
	if($('#form_db_agencia_naviera').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/agencia_naviera/db/sql.registrar.php",
			data:dataForm('form_db_agencia_naviera'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_db_agencia_naviera');
					});					
				}
				else if (html=="area_telefono")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Es necesario un Codigo de &Aacute;rea y un Tel√©fono</p></div>",true,true);
				}
				else if (html=="codigo_area")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Es necesario un Codigo de &Aacute;rea </p></div>",true,true);
				}
				else if (html=="no_telefono")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Es necesario un Tel√©fono</p></div>",true,true);
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> <br>El rif Exite para otra Agencia Naviera</p></div>",true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

//------------------------------------------------Codigo para eliminar las Agencias Navieras 

$("#sareta_agencia_naviera_db_btn_eliminar").click(function() {
  if (getObj('vista_id_agencia_naviera').value !=""){
	if(confirm("¬øDesea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/agencia_naviera/db/sql.eliminar.php",
			data:dataForm('form_db_agencia_naviera'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_agencia_naviera_db_btn_eliminar').style.display='none';
					getObj('sareta_agencia_naviera_db_btn_actualizar').style.display='none';
					getObj('sareta_agencia_naviera_db_btn_guardar').style.display='';
					clearForm('form_db_agencia_naviera');
					getObj('sareta_agencia_naviera_db_estado').selectedIndex =0;
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion con esta Agencia Naviera</p></div>",true,true); 
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

//-----------------------------------------------------Codigo para los mensajes emergentes

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("#sareta_agencia_naviera_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_agencia_naviera_db_btn_cancelar').style.display='';
	getObj('sareta_agencia_naviera_db_btn_eliminar').style.display='none';
	getObj('sareta_agencia_naviera_db_btn_actualizar').style.display='none';
	getObj('sareta_agencia_naviera_db_btn_guardar').style.display='';
	clearForm('form_db_agencia_naviera');
	getObj('sareta_agencia_naviera_db_estado').selectedIndex =0;
});


$('#sareta_agencia_naviera_db_nombre').alpha({allow:' √°√©√≠√≥√∫√Å√â√ç√ì√ö√ë√±-.1234567890()""'})
$('#sareta_agencia_naviera_db_rif').alpha({allow:'A-Z.1234567890'});
$('#sareta_agencia_naviera_db_nit').alpha({allow:'A-Z.1234567890'});
$('#sareta_agencia_naviera_db_codigo_area').numeric({allow:'0123456789'});
$('#sareta_agencia_naviera_db_zona').numeric({allow:'0123456789'});
$('#sareta_agencia_naviera_db_apartado').numeric({allow:'0123456789'});
$('#sareta_agencia_naviera_db_zona').numeric({allow:'0123456789'});
$('#sareta_agencia_naviera_db_telefono').numeric({allow:'0123456789-'});
$('#sareta_agencia_naviera_db_telefono1').numeric({allow:'0123456789-'});
$('#sareta_agencia_naviera_db_fax').numeric({allow:'0123456789-'});
$('#sareta_agencia_naviera_db_fax1').numeric({allow:'0123456789-'});
$('#sareta_agencia_naviera_db_contacto').alpha({allow:' √°√©√≠√≥√∫√Å√â√ç√ì√ö√ë√±'});
$('#sareta_agencia_naviera_db_cedula').numeric({allow:'0123456789'});
$('#sareta_agencia_naviera_db_cargo').alpha({allow:' √°√©√≠√≥√∫√Å√â√ç√ì√ö√ë√±'});
$('#sareta_agencia_naviera_db_codigo_auxiliar').numeric({allow:'0123456789'});
</script>


<div id="botonera">
	<img id="sareta_agencia_naviera_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_agencia_naviera_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_agencia_naviera_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_agencia_naviera_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_agencia_naviera_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form method="post" id="form_db_agencia_naviera" name="form_db_agencia_naviera">
<input type="hidden" name="vista_id_agencia_naviera" id="vista_id_agencia_naviera" />

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Agencia Naviera</th>
	</tr>
	<tr>
	<th>Nombre:		</th>	
		<td>
            <input name="sareta_agencia_naviera_db_nombre" type="text" id="sareta_agencia_naviera_db_nombre"   value="" size="63" maxlength="60"  
            message="Introduzca el Nombre para la Agencia Naviera. Ejem: ''AGEMAR, C.A.'' " 
            jVal="{valid:/^[a-z A-Z ·ÈÌÛ˙¡…Õ”⁄Ò 0-9]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
            jValKey="{valid:/[a-z A-Z ·ÈÌÛ˙¡…Õ”⁄Ò 0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
    	<tr>
	<th>RIF:</th>
		<td>
            <input name="sareta_agencia_naviera_db_rif" type="text" id="sareta_agencia_naviera_db_rif"   
            value="" size="20" maxlength="20"  
            message="Introduzca un RIF " 
            jVal="{valid:/^[a-zA-Z·ÈÌÛ˙¡…Õ”⁄Ò 0-9 -]{1,60}$/, message:'RIF Invalido', styleType:'cover'}"
            jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò 0-9 -]/, cFunc:'alert', cArgs:['RIF: '+$(this).val()]}" />
		</td>
	</tr>	
	<tr>
	<tr>
		<th>NIT:</th>	
        <td >
        <input name="sareta_agencia_naviera_db_nit" type="text" id="sareta_agencia_naviera_db_nit"   
        value="" size="20" maxlength="20"  
		message="Introduzca un NIT" 
		 />
        </td>
	</tr>
    <tr>
	<th>Direcci&oacute;n:	
    	<td>
   		<textarea name="sareta_agencia_naviera_db_direccion" cols="60" id="sareta_agencia_naviera_db_direccion" 
        message="Introduzca una direcci&oacute;n"></textarea>
	  	</td>
	</tr>
    <tr>
	<th>Estado:</th>	
	<td>
    	<select name="sareta_agencia_naviera_db_estado" id="sareta_agencia_naviera_db_estado">
        <?=$opt_estado ?>
        </select>
		</td>
	</tr>	
    <tr>
    <th>Codigo &Aacute;rea:</th>
    <td>
      	<input name="sareta_agencia_naviera_db_codigo_area" type="text" id="sareta_agencia_naviera_db_codigo_area"   
        value="" size="10" maxlength="4"  
		message="Introduzca el Codigo de &Aacute;rea. Ejem: ''281'' " 
		jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo de &Aacute;rea: '+$(this).val()]}" /> 
   	   
       <strong>Zona:</strong>
       <input name="sareta_agencia_naviera_db_zona" type="text" id="sareta_agencia_naviera_db_zona"   
        value="" size="10" maxlength="4"  
		message="Introduzca la Zona. Ejem: ''175'' "
        jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Zona: '+$(this).val()]}"/> 
   	   <strong>Apartado:</strong>
       <input name="sareta_agencia_naviera_db_apartado" type="text" id="sareta_agencia_naviera_db_apartado"   
        value="" size="10" maxlength="4"  
		message="Introduzca un Apartado Ejem: ''445'' " 
        jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Apartado: '+$(this).val()]}"/>
    </td>		
	</tr>
    <tr>
	<th>Tel&eacute;fonos:</th>	
		<td>
        <input name="sareta_agencia_naviera_db_telefono" type="text" id="sareta_agencia_naviera_db_telefono"   
        value="" size="25" maxlength="10"  
		message="Introduzca un Tel&eacute;fono" 
		jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Tel&eacute;fono: '+$(this).val()]}" />
        
        
        <input name="sareta_agencia_naviera_db_telefono1" type="text" id="sareta_agencia_naviera_db_telefono1"   
        value="" size="25" maxlength="10"  
		message="Introduzca un Tel&eacute;fono "
		jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Tel&eacute;fono: '+$(this).val()]}" />
		</td>
        
	</tr>
    <tr>
			<th>Fax :			</th>
			<td>
        <input name="sareta_agencia_naviera_db_fax" type="text" id="sareta_agencia_naviera_db_fax"   
        value="" size="25" maxlength="10"  
		message="Introduzca Fax " 
		jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Fax: '+$(this).val()]}" />
        
        
        <input name="sareta_agencia_naviera_db_fax1" type="text" id="sareta_agencia_naviera_db_fax1"   
        value="" size="25" maxlength="10"  
		message="Introduzca un Fax " 
		jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Fax: '+$(this).val()]}" />
		
		</td>
	</tr>	
 <tr>
	<th>Pagina Web:</th>
		<td>
		<input name="sareta_agencia_naviera_db_pag_web" type="text" id="sareta_agencia_naviera_db_pag_web"   
        value="" size="63" maxlength="60"  
		message="Introduzca una Pagina Web. Ejem: ''http://www.ejemplo.com'' " 
        jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò]/, cFunc:'alert', cArgs:['Pagina Web: '+$(this).val()]}" />
		</td>
	</tr>	
	<tr>
	<tr>
		<th>Correo Electr&oacute;nico:</th>	
        <td >
        <input name="sareta_agencia_naviera_db_correo" type="text" id="sareta_agencia_naviera_db_correo"   
        value="" size="63" maxlength="60"  
		message="Introduzca un Correo Electr&oacute;nico ''Ejemplo@hotmail.com'' " 
        jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
        </td>
	</tr>
    <tr>
		<th>Contacto:</th>	
        <td >
        <input name="sareta_agencia_naviera_db_contacto" type="text" id="sareta_agencia_naviera_db_contacto"   
        value="" size="63" maxlength="45"  
		message="Introduzca un Contacto " 
		jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò]{1,60}$/, message:'Contacto Invalido', styleType:'cover'}"
		jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò]/, cFunc:'alert', cArgs:['Contacto: '+$(this).val()]}" />
        </td>
	</tr>
    <tr>
		<th>C&eacute;dula:</th>	
        <td >
        <input name="sareta_agencia_naviera_db_cedula" type="text" id="sareta_agencia_naviera_db_cedula"   
        value="" size="30" maxlength="15"  
		message="Introduzca un n&uacute;mero de cedula" 
		jVal="{valid:/^[0-9]{1,15}$/, message:'Cedula Invalido', styleType:'cover'}"
		jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Cedula: '+$(this).val()]}" />
        </td>
	</tr>
    <tr>
		<th>Cargo:</th>	
        <td >
        <input name="sareta_agencia_naviera_db_cargo" type="text" id="sareta_agencia_naviera_db_cargo"   
        value="" size="30" maxlength="30"  
		message="Introduzca un Cargo. Ejem: ''GERENTE GENERAL'' " 
		jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò]{1,30}$/, message:'Cargo Invalido', styleType:'cover'}"
        jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò]/, cFunc:'alert', cArgs:['Cargo: '+$(this).val()]}" />
        </td>
	</tr>
    <tr>
		<th>C&oacute;digo Auxiliar:</th>	
        <td >
        <input name="sareta_agencia_naviera_db_codigo_auxiliar" type="text" id="sareta_agencia_naviera_db_codigo_auxiliar"   
        value="" size="30" maxlength="8"  
		message="Introduzca un C&oacute;digo Auxiliar. Ejem: ''24'' " 
        jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['C&oacute;digo Auxiliar:: '+$(this).val()]}" />
       
        
        </td>
	</tr>
    <tr>
	<th>Comentario:	
    	<td>
   		<textarea name="sareta_agencia_naviera_db_obs" cols="60" id="sareta_agencia_naviera_db_obs" 
        message="Introduzca un Observaci√≥n"></textarea>
	  	</td>
	</tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>