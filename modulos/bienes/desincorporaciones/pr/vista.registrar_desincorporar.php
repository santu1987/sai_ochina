<?php if (!$_SESSION) session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM tipo_desincorporaciones";
$rs_tipo =& $conn->Execute($sql);
while (!$rs_tipo->EOF){
	$opt_tipo.="<option value='".$rs_tipo->fields("id_tipo_desincorporaciones")."' >".$rs_tipo->fields("nombre")."</option>";
	$rs_tipo->MoveNext();
} 
?>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------

$("#desincorporacion_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/custodio/db/vista.grid_custodio_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Custodio', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#custodio_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/custodio/db/sql_custodio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#custodio_db_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#custodio_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/custodio/db/sql_custodio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/custodio/db/sql_custodio_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Bien'],
								colModel:[
									{name:'id_bienes',index:'id_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_bienes',index:'codigo_bienes', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('form_desincorporar_bien_pr_id_bienes').value=ret.id_custodio;
									//getObj('form_desincorporar_bien_pr_codigo_bien').value=ret.nombre;
									getObj('form_desincorporar_bien_pr_nombre_bien').value=ret.comentarios;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#custodio_db_nombre").focus();
								$('#custodio_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
$("#form_desincorporar_bien_pr_btn_consulta_emergente").click(function() {																	
if(getObj('form_desincorporar_bien_pr_id_sitio').value!=''){																	   
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/desincorporaciones/pr/vista.grid_bien_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Bien', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#desincorporaciones_db_nombre").val();
					var id_sitio= getObj('form_desincorporar_bien_pr_id_sitio').value;
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/desincorporaciones/pr/sql_desincorporar_bien_nombre.php?busq_nombre="+busq_nombre+"&id_sitio="+id_sitio,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#desincorporaciones_pr_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#desincorporaciones_pr_nombre").val();
							var id_sitio= getObj('form_desincorporar_bien_pr_id_sitio').value;
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/desincorporaciones/pr/sql_desincorporar_bien_nombre.php?busq_nombre="+busq_nombre+"&id_sitio="+id_sitio,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:"modulos/bienes/desincorporaciones/pr/sql_desincorporar_bien_nombre.php?id_sitio="+getObj('form_desincorporar_bien_pr_id_sitio').value,
								datatype: "json",
								colNames:['ID','Codigo','Bien'],
								colModel:[
									{name:'id_bienes',index:'id_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_bienes',index:'codigo_bienes', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_bienes').value = ret.id_bienes;
									getObj('form_desincorporar_bien_pr_id_bienes').value=ret.id_bienes;
									//getObj('form_desincorporar_bien_pr_codigo_bien').value=ret.codigo_bienes;
									getObj('form_desincorporar_bien_pr_nombre_bien').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#desincorporaciones_pr_nombre").focus();
								$('#desincorporaciones_pr_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
}
	});
//
//

$("#form_desincorporar_bien_pr_btn_consulta_sitio").click(function() {

	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/desincorporaciones/pr/vista.grid_sitio_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Bien', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#desincorporaciones_sitio_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/desincorporaciones/pr/sql_desincorporar_sitio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#desincorporaciones_sitio_pr_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#desincorporaciones_sitio_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/desincorporaciones/pr/sql_desincorporar_sitio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/desincorporaciones/pr/sql_desincorporar_sitio_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Sitio','Comentario'],
								colModel:[
									{name:'id_sitio_fisico',index:'id_sitio_fisico', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('form_desincorporar_bien_pr_id_sitio').value=ret.id_sitio_fisico;
									getObj('form_desincorporar_bien_pr_sitio_fisico').value=ret.nombre;
									//getObj('form_desincorporar_bien_pr_codigo_bien').value=ret.codigo_bienes;
									
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#desincorporaciones_sitio_pr_nombre").focus();
								$('#desincorporaciones_sitio_pr_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_sitio_fisico',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
$("#form_desincorporar_bien_pr_codigo_bien").change(function() {											
	if (getObj('form_desincorporar_bien_pr_codigo_bien').value!=''){											
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/desincorporaciones/pr/sql_auto_bien_codigo.php",
			data:dataForm('form_db_desincorporar_bien'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				//alert(html);
				recordset=html;
				if (recordset!=' '){
					recordset = recordset.split("*");
					//getObj('form_desincorporar_bien_pr_id_bienes').value = recordset[0];
					getObj('form_desincorporar_bien_pr_nombre_bien').value = recordset[2];
					setBarraEstado('');
				}
				else{
					getObj('form_desincorporar_bien_pr_nombre_bien').value='';
					setBarraEstado('');
				}
				
			}
		});
	}
});

//
//
//----------------------------------------------------------------


$("#desincorporacion_db_btn_guardar").click(function() {
	if ($('#form_db_desincorporar_bien').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/desincorporaciones/pr/sql.registrar_desincorporacion.php",
			data:dataForm('form_db_desincorporar_bien'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//limpiar();
					getObj('opt').value=2;
					document.form_desin_foto.submit();
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Error fecha"){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La fecha del valor del impuesto \n tiene que ser mayor que la fecha actual </p></div>",true,true);
					}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});



//
//
// ******************************************************************************

$("#desincorporacion_db_btn_cancelar").click(function() {
//clearForm('form_db_custodio');
limpiar();
setBarraEstado("");
});

/// ------------------------Limpiar campos--------------------------
function limpiar(){
		getObj('form_desincorporar_bien_pr_id_sitio').value='';
		getObj('form_desincorporar_bien_pr_id_bienes').value=''; 
		//getObj('form_desincorporar_bien_pr_codigo_bien').value='';
		getObj('form_desincorporar_bien_pr_nombre_bien').value='';
		getObj('form_desincorporar_bien_pr_sitio_fisico').value='';
		getObj('form_desincorporar_bien_pr_tipo').selectedIndex=0;
		getObj('form_desincorporar_bien_pr_descripcion').value='';
		getObj('form_desincorporar_bien_pr_comentario').value='';
		getObj('foto_desincorporacion1').value='';
		getObj('foto_desincorporacion2').value='';
		getObj('foto_desincorporacion3').value='';
		getObj('foto_desincorporacion4').value='';
		getObj('foto1').src='imagenes/iconos/sombra.bmp';
		getObj('foto2').src='imagenes/iconos/sombra.bmp';
		getObj('foto3').src='imagenes/iconos/sombra.bmp';
		getObj('foto4').src='imagenes/iconos/sombra.bmp';
		
}

//-------------------------------------------------------------------------------------------------------------------------------------------------
/* ******************************************************************************


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
$('#form_desincorporar_bien_pr_sitio_fisico').alpha({allow:' '});
$('#form_desincorporar_bien_pr_nombre_bien').alpha({allow:' '});
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
//
</script>
<div id="botonera">
	<img id="desincorporacion_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img style="display:none" id="custodio_db_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif"/>
	<img id="desincorporacion_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" style="display:none"/>
    <img style="display:none" id="custodio_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="desincorporacion_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_desincorporar_bien" id="form_db_desincorporar_bien">
<input type="hidden" name="form_desincorporar_bien_pr_fechact" id="form_desincorporar_bien_pr_fechact" value="<?php echo date("d-m-Y");?>"/>
<input type="hidden" name="form_desincorporar_bien_pr_id_bienes" id="form_desincorporar_bien_pr_id_bienes"/>
<input type="hidden" name="form_desincorporar_bien_pr_id_sitio" id="form_desincorporar_bien_pr_id_sitio"/>
<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Desincorporacion			</th>
	</tr>

    	<tr>
    	  <th>Sitio Fisico</th>
    	  <td><ul class="input_con_emergente">
				<li>
           <input name="form_desincorporar_bien_pr_sitio_fisico" type="text"  id="form_desincorporar_bien_pr_sitio_fisico" maxlength="60" size="30" readonly="true"  message="Seleccione el Sitio Fisico" jval="{valid:/^[a-zA-Z 0-9.áéíóúÁÉÍÓÚ]{1,60}$/, message:'Sitio Fisico Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z 0-9.áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
       </li>
				<li id="form_desincorporar_bien_pr_btn_consulta_sitio" class="btn_consulta_emergente"></li>
			</ul></td>
  	  </tr>
    	<tr>
			<th>Bien</th>
		  <td> <ul class="input_con_emergente">
				<li>
           <input name="form_desincorporar_bien_pr_nombre_bien" type="text"  id="form_desincorporar_bien_pr_nombre_bien" maxlength="60" size="30" readonly="true"  message="Seleccione el Bien." jval="{valid:/^[a-zA-Z0-9.]{1,60}$/, message:'Bien Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z0-9.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
       </li>
				<li id="form_desincorporar_bien_pr_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr>
			<th>Tipo Desincorporacion</th>
		  <td> <label>
		    <select name="form_desincorporar_bien_pr_tipo" id="form_desincorporar_bien_pr_tipo">
            <?= $opt_tipo;?>
	        </select>
	      </label></td>
		</tr>
        <tr>
			<th>Descripción General</th>
		  <td><label>
		    <textarea name="form_desincorporar_bien_pr_descripcion" id="form_desincorporar_bien_pr_descripcion" cols="60" style="width:422px" message="Introduzca una Descripción."></textarea>
		  </label></td>
		</tr>
        <tr>
			<th>Observaci&oacute;n</th>
		  <td><label>
		    <textarea name="form_desincorporar_bien_pr_comentario" id="form_desincorporar_bien_pr_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'Esta desincorporación es...'"></textarea>
		  </label></td>
		</tr>			
  </table>
</form>
<form action="modulos/bienes/desincorporaciones/pr/vista.previa_foto.php" method="post" enctype="multipart/form-data" name="form_desin_foto" target="form_vista_foto" id="form_desin_foto">
<table class="cuerpo_formulario">
<tr>
			<th colspan="2" class="titulo_td">
				<div align="center">Fotos</div></th>
	</tr>

    	<tr>
			<th><img src="imagenes/iconos/sombra.bmp" alt="" width="100" height="80" id="foto1"/>
            <input name="foto_desincorporacion1" type="file" id="foto_desincorporacion1" onchange="tiempo(1);" size="15"/></th>
		  <td><img id="foto2" src="imagenes/iconos/sombra.bmp" height="80" width="100"/>
	      <input name="foto_desincorporacion2" type="file" id="foto_desincorporacion2" onchange="tiempo(2);" size="15"/></td>
		</tr>
        <tr>
			<th><img id="foto3" src="imagenes/iconos/sombra.bmp" height="80" width="100"/>
		    <input name="foto_desincorporacion3" type="file" id="foto_desincorporacion3" onchange="tiempo(3);" size="15"/></th>
		  <td><img id="foto4" src="imagenes/iconos/sombra.bmp" height="80" width="100"/>
	      <input name="foto_desincorporacion4" type="file" id="foto_desincorporacion4" onchange="tiempo(4);" size="15"/></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame"><input type="hidden" id="posicion" name="posicion"/><input type="hidden" id="err_formato" name="err_formato" onclick="error_formato()"/>
            <input type="hidden" id="err_tamano" name="err_tamano" onclick="error_tamano()"/>
            <input type="hidden" id="opt" name="opt"/>
            <input type="hidden" id="id_bienes" name="id_bienes"/>
            <input type="hidden" id="borrar" name="borrar" onclick="limpiar();"/></td>
		</tr>			
  </table>
</form>

<iframe id="form_vista_foto" name="form_vista_foto" style="display:none"></iframe>
<iframe id="form_limpiar_cache" name="form_limpiar_cache" style="display:none"></iframe>
<script language="javascript">
	function enviar_fotos(obj){
		getObj('opt').value = 1; 
		getObj('posicion').value = obj;
		document.form_desin_foto.submit();
	}
	function error_formato(){
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/sombra.bmp />El tipo de Imagen tiene que ser: jpeg</p></div>",true,true);
	}
	function error_tamano(){
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/foto/sombra.png />El tama&ntilde;o de la imagen tiene que ser menor a 1 MB</p></div>",true,true);
	}
	var time;
	c=0;
	function loader(obj){
		getObj('foto'+obj).src='imagenes/iconos/ajax-loader2.gif';
		if(c==4){
			clearInterval(time);
			c=0;
			enviar_fotos(obj);
		}
		c++;
	}
	function tiempo(obj){
		c=0;
		time = setInterval("loader("+obj+");",100); 
	} 
</script>