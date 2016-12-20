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
	$sql_proveedor="select  * from documento where (id_organismo = ".$_SESSION["id_organismo"].") AND estatus=0 ORDER BY id_documento_proveedor";
	$rs_proveedor=& $conn->Execute($sql_proveedor);
	
	?>
    <link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>

<script type="text/javascript">
var dialog;
$("#proveedor_db_btn_imprimir").click(function() {
//alert(getObj('covertir_numero_cotizacion').value);
	if(getObj('proveedor_db_codigo').value != ""){
		url="pdf.php?p=modulos/adquisiones/proveedor/rp/vista.lst.proveedor.php¿codigo="+getObj('proveedor_db_codigo').value 
		//alert(url);
		openTab("Ficha Proveedor",url);
	}
});	
			
/*--------------------------------------   GUARDAR ----------------------------------------------------*/
$("#proveedor_db_btn_guardar").click(function() {
		//verProps("opt_92");
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
				//dialog=new Boxy(html, { title: 'Consulta Emergente de Proveedor',modal: true,center:false,x:0,y:0});
				
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar_proveedor();
					
					getObj('rnc_db_fecha_rif').value = "<?php echo date("d-m-Y");?>";
					getObj('rnc_db_fecha').value = "<?php echo date("d-m-Y");?>";
					getObj('rnc_db_fecha_sol').value = "<?php echo date("d-m-Y");?>";
					//clearForm('form_db_proveedor');
					
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);

				}else
					{
					//alert(html);
					setBarraEstado(html);
				}
			}
		});
	}
});



///////////////////////////////////////
/*--------------------------------------   BUSCAR ----------------------------------------------------*/
//
//
//
$("#proveedor_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/proveedor/db/vista.grid_proveedor.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Proveedor', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#proveedor_db_nombre_proveedor").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/proveedor/db/sql_proveedor.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#proveedor_db_nombre_proveedor").keypress(function(key)
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
							var busq_nombre= jQuery("#proveedor_db_nombre_proveedor").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/proveedor/db/sql_proveedor.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/adquisiones/proveedor/db/sql_proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Proveedor','Direcci&oacute;n','Tel&eacute;fono','Fax','Rif','Nit','Persona Contacto','Cargo','Email','Pagina Web','RNC','FECHA RNC','Ramo','Comentario','Tipo Rif','Numero Rif','fecha_sol','Solvencia','objetivo','covertura','fecha_rif'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proveedor',index:'codigo_proveedor', width:30,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'direccion',index:'direccion', width:100,sortable:false,resizable:false,hidden:true},
									{name:'telefono',index:'telefono', width:50,sortable:false,resizable:false},
									{name:'fax',index:'fax', width:100,sortable:false,resizable:false,hidden:true},
									{name:'rif',index:'rif', width:50,sortable:false,resizable:false},
									{name:'nit',index:'nit', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre_persona_contacto',index:'nombre_persona_contacto', width:50,sortable:false,resizable:false},
									{name:'cargo_persona_contacto',index:'cargo_persona_contacto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'email_contacto',index:'email_contacto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'paginaweb',index:'paginaweb', width:100,sortable:false,resizable:false,hidden:true},
									{name:'rnc',index:'rnc', width:100,sortable:false,resizable:false,hidden:true},
									{name:'fecha_rnc',index:'fecha_rnc', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_ramo',index:'id_ramo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true},
									{name:'riftipo',index:'riftipo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'rifnumero',index:'rifnumero', width:100,sortable:false,resizable:false,hidden:true},
									{name:'fecha_sol',index:'fecha_sol', width:100,sortable:false,resizable:false,hidden:true},
									{name:'solvencia',index:'solvencia', width:100,sortable:false,resizable:false,hidden:true},
									{name:'objetivo',index:'objetivo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'distribucion',index:'distribucion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fecha_rif',index:'fecha_rif', width:100,sortable:false,resizable:false,hidden:true}
//covertura_distribucion
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									limpiar_proveedor();
								getObj('proveedor_db_id').value = ret.id_proveedor;
									getObj('proveedor_db_nombre_prove').value = ret.nombre;
									//getObj('proveedor_db_nombre_prove').disabled = true;
									getObj('proveedor_db_codigo').value = ret.codigo_proveedor;
									getObj('proveedor_db_direccion').value = ret.direccion;
									getObj('proveedor_db_telefono').value = ret.telefono;
									getObj('proveedor_db_fax').value = ret.fax;
									getObj('proveedor_db_tipo').value = ret.riftipo;
									getObj('proveedor_db_rif').value = ret.rifnumero;
									getObj('proveedor_db_nit').value = ret.nit;
									getObj('proveedor_db_persona_contacto').value = ret.nombre_persona_contacto;
									getObj('proveedor_db_cargo_contacto').value = ret.cargo_persona_contacto;
									getObj('proveedor_db_email_contacto').value = ret.email_contacto;
									getObj('proveedor_db_pagina_web').value = ret.paginaweb;
									getObj('proveedor_db_rnc').value = ret.rnc;
									getObj('rnc_db_fecha').value = ret.fecha_rnc;									
									getObj('proveedor_db_ramo').value = ret.id_ramo;
									getObj('proveedor_db_comentario').value = ret.comentario;
									
									getObj('rnc_db_fecha_sol').value = ret.fecha_sol;									
									getObj('proveedor_db_sol_laboral').value = ret.solvencia;
									getObj('proveedor_db_objetivo').value = ret.objetivo;
									getObj('proveedor_db_covertura_dis').value = ret.distribucion;
									getObj('rnc_db_fecha_rif').value = ret.fecha_rif;
									
									
									getObj('proveedor_db_btn_actualizar').style.display='';
									getObj('proveedor_db_btn_guardar').style.display='none';	
								     consulta_automatica_documento();
							
							dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#proveedor_db_nombre_proveedor").focus();
								$('#proveedor_db_nombre_proveedor').alpha({allow:' '});
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

//
//
//
/*$("#proveedor_db_btn_consultar").click(function() {
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
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/proveedor/db/sql_grid_proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo','Proveedor','Direcci&oacute;n','Tel&eacute;fono','Fax','RIF','NIT','Persona Contacto','Cargo','Email','Pagina Web','RNC','Ramo','Comentario','tiporif','rif_numero'],
								colModel:[
									{name:'id',index:'id', width:40,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
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
									getObj('proveedor_db_codigo').value = ret.codigo;
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
								    consulta_automatica_documento();
							
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
});*/
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
					getObj('proveedor_db_comentario').value=html;
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					alert(html);
					//setBarraEstado(html);
				}
			}
		});
	}
});

$("#proveedor_db_btn_cancelar").click(function() {
limpiar_proveedor();
	getObj('rnc_db_fecha_rif').value = "<?php echo date("d-m-Y");?>";
	getObj('rnc_db_fecha').value = "<?php echo date("d-m-Y");?>";
	getObj('rnc_db_fecha_sol').value = "<?php echo date("d-m-Y");?>";

});
function limpiar_proveedor(){
$cd=0;
	while($cd< getObj('numero_check').value)
	{
		document.form_db_proveedor.check[$cd]. checked="";
		$cd++;
	}
	setBarraEstado("");
	ncheck=getObj('numero_check').value;
    getObj('proveedor_db_btn_cancelar').style.display='';
	getObj('proveedor_db_btn_actualizar').style.display='none';
	getObj('proveedor_db_btn_guardar').style.display='';
	getObj('proveedor_db_ramo').value="0";    
	clearForm('form_db_proveedor');
	getObj('numero_check').value=ncheck;
}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_proveedor()
{
	if (getObj('proveedor_db_codigo')!=" ")
	{
	$.ajax({
			url:"modulos/adquisiones/proveedor/db/sql_grid_codigo.php",
            data:dataForm('form_db_proveedor'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
					if(recordset!=" ")
				{
				recordset = recordset.split("*");
					getObj('proveedor_db_id').value = recordset[0];
					getObj('proveedor_db_nombre_prove').value = recordset[1];
					//getObj('proveedor_db_nombre_prove').disabled = true;
					getObj('proveedor_db_ramo').value =recordset[11];
					getObj('proveedor_db_direccion').value = recordset[2];
					getObj('proveedor_db_telefono').value=recordset[3];
					getObj('proveedor_db_fax').value = recordset[4];
					getObj('proveedor_db_tipo').value = recordset[14];
					getObj('proveedor_db_rif').value = recordset[15];
					getObj('proveedor_db_nit').value = recordset[6];
					getObj('proveedor_db_persona_contacto').value = recordset[7];
					getObj('proveedor_db_cargo_contacto').value =recordset[13];
					getObj('proveedor_db_email_contacto').value = recordset[8];
					getObj('proveedor_db_pagina_web').value = recordset[9];
					getObj('proveedor_db_rnc').value = recordset[10];
					getObj('proveedor_db_ramo').value = recordset[12];
					getObj('proveedor_db_comentario').value = recordset[12];
					getObj('proveedor_db_btn_actualizar').style.display='';
					getObj('proveedor_db_btn_guardar').style.display='none';	
					 consulta_automatica_documento();
				 }
				 else
				 {
				 	getObj('proveedor_db_id').value ="";
					getObj('proveedor_db_nombre_prove').value ="";
					/*getObj('proveedor_db_nombre_prove').disabled = true;*/
					getObj('proveedor_db_direccion').value = "";
					getObj('proveedor_db_telefono').value="";
					getObj('proveedor_db_fax').value= "";
					getObj('proveedor_db_tipo').value = "";
					getObj('proveedor_db_rif').value ="";
					getObj('proveedor_db_nit').value ="";
					getObj('proveedor_db_persona_contacto').value = "";
					getObj('proveedor_db_cargo_contacto').value ="";
					getObj('proveedor_db_email_contacto').value = "";
					getObj('proveedor_db_pagina_web').value = "";
					getObj('proveedor_db_rnc').value = "";
					getObj('proveedor_db_ramo').value ="";
					getObj('proveedor_db_comentario').value ="";
					getObj('proveedor_db_btn_actualizar').style.display='none';
					getObj('proveedor_db_btn_guardar').style.display='';	
				 
				 }
			 }
		});	 	 
	}	
}
////////funcion que busca los documentos almacenados
function consulta_automatica_documento()
{

 id=getObj('proveedor_db_id').value
	$.ajax({
					url:"modulos/adquisiones/proveedor/db/sql.grid_documento_proveedor.php?id="+id,
					data:dataForm('form_db_proveedor'),
					type:'GET',
					cache: false,
					 success:function(html)
					 {
						var recordset=html;	
						if(recordset)
						{
							recordset = recordset.split("*");
						et=0;
						a=1;
						et2=0;
						ncheck=getObj('numero_check').value;
						//alert(ncheck);
						if(html=='error'){alert("error");}
						else{
								while(et<ncheck)
								{
								//('proveedor_db_direccion').value=html;
								//alert(et);		
											//alert(recordset[a+1]);
													if(recordset[a+1]=="t")
												 	{
												     
												//	getObj(et).value=recordset[a];	
													//getObj('proveedor_db_direccion').value=recordset[a];
													document.form_db_proveedor.check[et]. checked="checked";
												
													}
													if(recordset[a]!=null)
													{document.form_db_proveedor.ide[et].value=recordset[a];}
													/*getObj('documento_proveedor_db_id').value = recordset[0];
													getObj('documento_proveedor_db_nombre').value=recordset[2];
													getObj('documento_proveedor_db_observacion').value=recordset[3];*/
								et=et+1;
								a=a+3;
								//alert(et);
								}				
                              }
						  }	
					}
		});	 	 
		
}	
//////////////////////////////////////
$('#proveedor_db_codigo').change(consulta_automatica_proveedor)
/*---------------------------------------------  validaciones ----------------------------------------------------------------------------*/
$('#proveedor_db_nombre_prove').alpha({allow:'áéíóúÁÉÍÓÚ 1234567890.,'});
$('#proveedor_db_telefono').numeric({allow:' -'});
$('#proveedor_db_fax').numeric({allow:' -'});
$('#proveedor_db_rif').numeric({allow:'JGV-'});
$('#proveedor_db_nit').numeric({allow:''});
$('#proveedor_db_rnc').numeric({allow:''});
$('#proveedor_db_persona_contacto').alpha({allow:'áéíóúÁÉÍÓÚ '});

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
	<img id="proveedor_db_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"/>
	<img id="proveedor_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="proveedor_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="proveedor_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form name="form_db_proveedor" id="form_db_proveedor">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame"  colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Proveedor</th>
		</tr>
		<tr>
		<th colspan="2" align="right">Fecha:&nbsp;&nbsp;<?
		if (date("l")=="Monday")
			$dia = "Lunes";
		if (date("l")=="Tuesday")
			$dia = "Martes";
		if (date("l")=="Wednesday")
			$dia = "Miercoles";
		if (date("l")=="Thursday")
			$dia = "Jueves";
		if (date("l")=="Friday")
			$dia = "Viernes";
		if (date("l")=="Saturday")
			$dia = "Sabado";
		if (date("l")=="Sunday")
			$dia = "Domingo";
        $fech = $dia .", ".date("d-m-Y");
		echo $fech;
		?></th>
		</tr>
        <th>Código:</th>
		 <td>
		    	<input type="text" name="proveedor_db_codigo" id="proveedor_db_codigo"  style="width:6ex;"  maxlength="4"
				 onchange="consulta_automatica_proveedor" onclick="consulta_automatica_proveedor"message="Introduzca el Codigo del proveedor." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{1,4}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		 </td>
		</tr>
		<tr>
			<th>Nombre Proveedor:</th>
			<td><input name="proveedor_db_nombre_prove" type="text" id="proveedor_db_nombre_prove" style="width:62ex;" 
				message="Introduzca el Nombre del Proveedor." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890.,]{1,180}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890..,,]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/></td>	
		</tr>
        <tr>
			<th>Objeto de la Compa&ntilde;ia:</th>
			<td>
            	<textarea name="proveedor_db_objetivo" id="proveedor_db_objetivo" rows="3" cols="65" 
				message="Introduzca el Objeto de la Compa&ntilde;ia." ></textarea>
            </td>
		</tr>
		<tr>
			<th>RIF:</th>
		    <td><select name="proveedor_db_tipo" id="proveedor_db_tipo" style="width:40px; min-width:40px;">
					<option value="J">J</option>
					<option value="V">V</option>
					<option value="G">G</option>
				</select>-	<input name="proveedor_db_rif" type="text" id="proveedor_db_rif" size="10" maxlength="10" 
				message="Introduzca el N&uacute;mero de R.I.F. del proveedor. Ejemplo:12345678" 
				jVal="{valid:/^[0-9-]{7,10}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}" /></td>
		</tr>
        <tr>
			<th>Fecha Vencimiento RIF</th>
		  <td><label>
		    <input name="rnc_db_fecha_rif" type="text" id="rnc_db_fecha_rif" size="8" maxlength="10"  readonly="true" value="<?php echo date("d-m-Y");?>" message="hola"/><button id="boton_fecha3">...</button>
            <script type="text/javascript">
					Calendar.setup({
						inputField     :    "rnc_db_fecha_rif",      // id of the input field
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "boton_fecha3",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
		  </label></td>
		</tr>
		<tr>
			<th>NIT:</th>
		    <td><input name="proveedor_db_nit" type="text" id="proveedor_db_nit" size="10" maxlength="10" 
				message="Introduzca el N&uacute;mero de N.I.T. del proveedor."/></td>
		</tr>
		<tr>
			<th>Tel&eacute;fono:</th>
			<td><input name="proveedor_db_telefono" type="text" id="proveedor_db_telefono" size="30" maxlength="40" 
				message="Introduzca el N&uacute;mero de Tel&eacute;fono del proveedor." 
				jVal="{valid:/^[0123456789 -]{1,30}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jValKey="{valid:/[0123456789 -]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/></td>
		</tr>
		<tr>
			<th>Fax:</th>
			<td><input name="proveedor_db_fax" type="text" id="proveedor_db_fax" size="30" maxlength="40" 
				message="Introduzca el N&uacute;mero de Fax del proveedor." 
				jValKey="{valid:/[0123456789 -]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/></td>
		</tr>
		<tr>
			<th>RNC:</th>
		    <td><input name="proveedor_db_rnc" type="text" id="proveedor_db_rnc"  style="width:20ex;"  maxlength="16"
				message="Introduzca el N&uacute;mero de Registro Nacional de Contratista."/>
			  <label></label></td>
		</tr>
        <tr>
			<th>Fecha Vencimiento RNC</th>
		  <td><label>
		    <input name="rnc_db_fecha" type="text" id="rnc_db_fecha" size="8" maxlength="10"  readonly="true" value="<?php echo date("d-m-Y");?>" message="hola"/><button id="boton_fecha">...</button>
            <script type="text/javascript">
					Calendar.setup({
						inputField     :    "rnc_db_fecha",      // id of the input field
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "boton_fecha",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
		  </label></td>
		</tr>
        <tr>
			<th>Solvencia Laboral:</th>
		    <td><input name="proveedor_db_sol_laboral" type="text" id="proveedor_db_sol_laboral"  style="width:19ex;"  maxlength="18"
				message="Introduzca el N&uacute;mero de Solvencia Laboral."/>
			  <label></label></td>
		</tr>
        <tr>
			<th>Vencimiento Solvencia Laboral</th>
		  <td><label>
		    <input name="rnc_db_fecha_sol" type="text" id="rnc_db_fecha_sol" size="8" maxlength="10"  readonly="true" value="<?php echo date("d-m-Y");?>" message="hola"/><button id="boton_fecha2">...</button>
            <script type="text/javascript">
					Calendar.setup({
						inputField     :    "rnc_db_fecha_sol",      // id of the input field
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "boton_fecha2",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
		  </label></td>
		</tr>
		<tr>
			<th>Ramo:</th>
			<td>
				<select name="proveedor_db_ramo" id="proveedor_db_ramo" style="width:370px; min-width:370px;">
					<option value="0">--SELECCIONE--</option>
					<?=$opt_ramos;?>
				</select>			</td>
		</tr>
         <tr>
			<th>Cobertura de distribuci&oacute;n:</th>
			<td><input name="proveedor_db_covertura_dis" type="text" id="proveedor_db_covertura_dis" style="width:62ex;"
				message="Cobertura de distribucion del Proveedor." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/></td>
		</tr>
		<tr>
			<th>Persona Contacto:</th>
			<td><input name="proveedor_db_persona_contacto" type="text" id="proveedor_db_persona_contacto" style="width:62ex;"
				message="Introduzca el Nombre de la Persona de Contacto con el Proveedor." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/></td>
		</tr>
       
		<tr>
			<th>Cargo Contacto:</th>
			<td><input name="proveedor_db_cargo_contacto" type="text" id="proveedor_db_cargo_contacto" style="width:62ex;" maxlength="60" 
				message="Introduzca el Cargo de la Persona de Contacto con el Proveedor."  /></td>
		</tr>
		<tr>
			<th>Email Contacto:</th>
			<td><input name="proveedor_db_email_contacto" type="text" id="proveedor_db_email_contacto" style="width:62ex;" maxlength="50" 
				message="Introduzca el Email de la Persona de Contacto con el Proveedor."
				jVal="{valid:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/, message:'Direcci&oacute;n de Email Invalidad', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z0-9._%+-@]/, cFunc:'alert', cArgs:['Email Direcci&oacute;n: '+$(this).val()]}" /></td>
		</tr>
		<tr>
			<th>Pagina Web:</th>
			<td><input name="proveedor_db_pagina_web" type="text" id="proveedor_db_pagina_web" style="width:62ex;" maxlength="50" 
				message="Introduzca el Pagina Web si posee."/></td>
		</tr>
		<tr>
			<th>Direccion:</th>
			<td><textarea name="proveedor_db_direccion" id="proveedor_db_direccion" rows="3" cols="65" 
				message="Introduzca la  Direcci&oacute;n del proveedor." ></textarea></td>
		</tr>
		<tr>
			<th>Comentario:</th>
			<td><textarea name="proveedor_db_comentario" id="proveedor_db_comentario" rows="3" cols="65"></textarea></td>
		</tr>
		
		<tr>
				<th>Documentos a consignar : </th>
		        <td>
			  	<? $i=0;
								while(!$rs_proveedor->EOF){?>
								<label>
								<? // "check[]" ?>
								<input type="checkbox" name="check[]" id="check" value=<?= $rs_proveedor->fields("id_documento_proveedor") ?>/>
								<input  type="hidden" name="ide[]" id="ide" maxlength="7" style="width:2ex";/>
								<?= $rs_proveedor->fields("nombre") ?>
                                <br />
				  </label>
								<?	  $rs_proveedor->MoveNext();
								 $i=$i+1;	}?>
		  <input name="numero_check" id="numero_check" type="hidden" value="<?= $i?>"/>	  </td>	
		</tr>		 		
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="proveedor_db_id" id="proveedor_db_id" />
</form>