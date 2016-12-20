<? if (!$_SESSION) session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

	$sql="SELECT * FROM ramo WHERE (id_organismo = ".$_SESSION["id_organismo"].") ORDER BY nombre";
	$rs_ramos =& $conn->Execute($sql);
	while (!$rs_ramos->EOF) {
		$opt_ramos.="<option value='".$rs_ramos->fields("id_ramo")."' >".$rs_ramos->fields("nombre")."</option>";
		$rs_ramos->MoveNext();
	}
?>
<script type="text/javascript">
var dialog;
		$("#proveedor_db_rif_check").click(function(){
			if(getObj('proveedor_db_rif_check').value!="uno")getObj('proveedor_db_rif_check').value="uno";
				else
				getObj('proveedor_db_rif_check').value="";
			});
				
			$("#proveedor_db_nit_check").click(function(){
			if(getObj('proveedor_db_nit_check').value!="dos")getObj('proveedor_db_nit_check').value="dos";
				else
				getObj('proveedor_db_nit_check').value="";
			});
			
			$("#proveedor_db_rnc_check").click(function(){
			if(getObj('proveedor_db_rnc_check').value!="tres")getObj('proveedor_db_rnc_check').value="tres";
				else
				getObj('proveedor_db_rnc_check').value="";
			});


/*--------------------------------------   GUARDAR ----------------------------------------------------*/
$("#proveedor_db_btn_guardar").click(function() {
	if($('#form_db_proveedor').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/proveedor/db/sql.proveedor.php",
			data:dataForm('form_db_proveedor'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('proveedor_db_rif_check').checked='';
					getObj('proveedor_db_nit_check').checked='';
					getObj('proveedor_db_rnc_check').checked='';
					clearForm('form_db_proveedor');
					
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);

				}/*else
					{
					alert(html);
					setBarraEstado(html);*/
				//}
			}
		});
	}
});
/*--------------------------------------   BUSCAR ----------------------------------------------------*/

$("#proveedor_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado(mensaje[esperando_respuesta]);
	$.post("modulos/adquisiones/proveedor/db/grid_proveedor.php", { },
                        function(data)
                        {								
							dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedor',modal: true,center:false,x:0,y:0,show:false});								
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
								url:'modulos/adquisiones/proveedor/db/sql_grid_proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Proveedor','Direcci&oacute;n','Tel&eacute;fono','Fax','RIF','NIT','Persona Contacto','Cargo','Email','Pagina Web','RNC','Ramo','Comentario','tiporif','rif_numero','rif2','nit2','rnc2'],
								colModel:[
									{name:'id',index:'id', width:40,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:220,sortable:false,resizable:false},
									{name:'direccion',index:'direccion', width:100,sortable:false,resizable:false,hidden:true},
									{name:'telefono',index:'telefono', width:100,sortable:false,resizable:false},
									{name:'fax',index:'fax', width:100,sortable:false,resizable:false,hidden:true},
									{name:'rif',index:'rif', width:100,sortable:false,resizable:false},
									{name:'nit',index:'nit', width:100,sortable:false,resizable:false,hidden:true},
									{name:'persona_contacto',index:'persona_contacto', width:220,sortable:false,resizable:false},
									{name:'cargo',index:'cargo', width:100,sortable:false,resizable:false,hidden:true,hidden:true},
									{name:'email',index:'email', width:100,sortable:false,resizable:false,hidden:true},
									{name:'pagina',index:'pagina', width:100,sortable:false,resizable:false,hidden:true},
									{name:'rnc',index:'rnc', width:80,sortable:false,resizable:false,hidden:true},
									{name:'ramo',index:'ramo', width:80,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:200,sortable:false,resizable:false,hidden:true},
									{name:'tiporif',index:'tiporif', width:50,sortable:false,resizable:false,hidden:true},
									{name:'rifnumero',index:'rifnumero', width:50,sortable:false,resizable:false,hidden:true},
								    {name:'rif2',index:'rif2', width:50,sortable:false,resizable:false,hidden:true},
								    {name:'nit2',index:'nit2', width:50,sortable:false,resizable:false,hidden:true},
		        					 {name:'rnc2',index:'rnc2', width:50,sortable:false,resizable:false,hidden:true}
	
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('proveedor_db_id').value = ret.id;
									getObj('proveedor_db_nombre_prove').value = ret.proveedor;
									//getObj('proveedor_db_nombre_prove').disabled = true;
									getObj('proveedor_db_direccion').value = ret.direccion;
									getObj('proveedor_db_telefono').value = ret.telefono;
									getObj('proveedor_db_fax').value = ret.fax;
									getObj('proveedor_db_tipo').value = ret.tiporif;
									getObj('proveedor_db_rif').value = ret.rifnumero;
									getObj('proveedor_db_nit').value = ret.nit;
									getObj('proveedor_db_persona_contacto').value = ret.persona_contacto;
									getObj('proveedor_db_cargo_contacto').value = ret.cargo;
									getObj('proveedor_db_email_contacto').value = ret.email;
									getObj('proveedor_db_pagina_web').value = ret.pagina;
									getObj('proveedor_db_rnc').value = ret.rnc;
									getObj('proveedor_db_ramo').value = ret.ramo;
									getObj('proveedor_db_comentario').value = ret.comentario;
									getObj('proveedor_db_btn_actualizar').style.display='';
									getObj('proveedor_db_btn_guardar').style.display='none';	
									if(ret.rif2!='0')
						     	    { 
									   getObj('proveedor_db_rif_check').checked=1;
										getObj('proveedor_db_rif_check').value="uno";
									}
											
									if(ret.nit2!='0')
						     	   {
								    getObj('proveedor_db_nit_check').checked=1;
									getObj('proveedor_db_nit_check').value="dos";
									}
											
									if(ret.rnc2!='0')
						     	    {
									 getObj('proveedor_db_rnc_check').checked=1;
									 getObj('proveedor_db_rnc_check').value="tres";
									}
				           		//			 
								 //  if(ret.nit2==1) getObj('proveedor_db_nit2_check').checked=true;
								//	 if(ret.rnc2==1) {getObj('proveedor_db_rnc2_check').checked=true;}
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
								sortname: 'id_proveedor',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
/*--------------------------------------   ACTULIZAR ----------------------------------------------------*/

$("#proveedor_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_proveedor').jVal())
	{
		//check_modificado();	
		$.ajax (
		{
			url: "modulos/adquisiones/proveedor/db/sql.actualizar.php",
			data:dataForm('form_db_proveedor'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('proveedor_db_btn_actualizar').style.display='none';
					getObj('proveedor_db_btn_guardar').style.display='';
			       	getObj('proveedor_db_rif_check').checked='';
					getObj('proveedor_db_nit_check').checked='';
					getObj('proveedor_db_rnc_check').checked='';
					clearForm('form_db_proveedor');
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

$("#proveedor_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('proveedor_db_btn_cancelar').style.display='';
	getObj('proveedor_db_btn_actualizar').style.display='none';
	getObj('proveedor_db_btn_guardar').style.display='';
	getObj('proveedor_db_rif_check').checked='';
	getObj('proveedor_db_nit_check').checked='';
	getObj('proveedor_db_rnc_check').checked='';
	clearForm('form_db_proveedor');
});

/*---------------------------------------------  validaciones ----------------------------------------------------------------------------*/
$('#proveedor_db_nombre_prove').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ 1234567890'});
$('#proveedor_db_telefono').numeric({allow:''});
$('#proveedor_db_fax').numeric({allow:''});
$('#proveedor_db_rif').numeric({allow:'JGV-'});
$('#proveedor_db_nit').numeric({allow:''});
$('#proveedor_db_rnc').numeric({allow:''});
$('#proveedor_db_persona_contacto').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ '});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});

</script>
<div id="botonera">
	<img id="proveedor_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="proveedor_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="proveedor_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="proveedor_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form name="form_db_proveedor" id="form_db_proveedor">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame"  colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Documentos </th>
		</tr>
		<th>Nombre </th>
			<td><input type="text" name="proveedor_db_nombre_prove" id="proveedor_db_nombre_prove" style="width:62ex;" 
				message="Introduzca el Nombre del Proveedor." 
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄1234567890]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/></td>
		</tr>
		<tr> 
		 <th>Estatus:</th>
		 <td>
		 	<label>
		   	<input id="documento_proveedor_estatus_opt_act" name="documento_proveedor_estatus_opt"  type="radio" value="0"  checked="checked"/>Activo
	      	</label>
			<label>
		   	<input id="documento_proveedor_estatus_opt_inact" name="documento_proveedor_estatus_opt_inact"  type="radio" value="1" />Inactivo
	      	</label>
		</td>
		</tr>
	</table>

</form>