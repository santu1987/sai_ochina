<?php
session_start();
$nombre="";
$codigo="";
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
if($_REQUEST['id_bienes']!=""){
$sql="SELECT codigo_bienes, bienes.nombre FROM bienes inner join organismo on bienes.id_organismo= organismo.id_organismo WHERE bienes.id_organismo = $_SESSION[id_organismo] AND bienes.id_bienes= $_REQUEST[id_bienes]";
$row =& $conn->Execute($sql);
$codigo = $row->fields("codigo_bienes");
$nombre = $row->fields("nombre");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 

<html> 
<head>
<title>Crear input file</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- -->
<script>
var dialog;

//----------------------------------------------------------------------------------------------------

$("#fotos_bien_pr_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/pr/vista.grid_fotos_bien.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente del Activo', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#fotos_bien_pr_nombre").val(); 
					var busq_codigo= jQuery("#fotos_bien_pr_codigo").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/pr/sql_fotos_bien_nombre.php?busq_nombre="+busq_nombre+"&busq_codigo="+busq_codigo,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#fotos_bien_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#fotos_bien_pr_codigo").keypress(function(key)
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
							var busq_nombre= jQuery("#fotos_bien_pr_nombre").val();
							var busq_codigo= jQuery("#fotos_bien_pr_codigo").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/pr/sql_fotos_bien_nombre.php?busq_nombre="+busq_nombre+"&busq_codigo="+busq_codigo,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/bienes/bien/pr/sql_fotos_bien_nombre.php?nd='+nd,
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
									limpiar(0);
									getObj('fotos_bien_pr_id_bienes').value=ret.id_bienes;
									getObj('fotos_bien_pr_codigo_bien').value=ret.codigo_bienes;
									getObj('fotos_bien_pr_nombre_bien').value=ret.nombre;
									getObj('fotos_bien_pr_btn_agregar').style.display='';
									consulta_automatica_fotos();
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#fotos_bien_pr_nombre").focus();
								$('#fotos_bien_pr_nombre').alpha({allow:' '});
								$('#fotos_bien_pr_codigo').alpha({allow:'0123456789 '});
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
function consulta_automatica_codigo_bien()
{
	if (getObj('fotos_bien_pr_codigo_bien')!=" ")
	{limpiar(0); getObj('fotos_bien_pr_nombre_bien').value='';
	$.ajax({
			url:"modulos/bienes/bien/pr/sql_auto_fotos_bienes.php",
            data:dataForm('form_foto'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
			
				//alert(html);
					if(recordset!=' ')
				{
					recordset = recordset.split("*");
					getObj('fotos_bien_pr_id_bienes').value = recordset[0];
					getObj('fotos_bien_pr_codigo_bien').value = recordset[1];
					getObj('fotos_bien_pr_nombre_bien').value = recordset[2];
					getObj('fotos_bien_pr_btn_agregar').style.display='';
					limpiar(0);
					consulta_automatica_fotos();
				 }
				 else
				 {
					limpiar(0); 
					setBarraEstado("");
				 }
			 }
		});	 	 
	}	
}
//
//
function consulta_automatica_fotos(){
	$.ajax({
			url:"modulos/bienes/bien/pr/sql_auto_fotos_bienes2.php",
            data:dataForm('form_foto'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
					if(recordset!='')
				{
					recordset = recordset.split("*");
					for (i=0; i<(recordset.length)-1; i++){
						crear(this,1);
						ev = eval ("document.form_foto.vista_foto"+num)
						ev.src = 'imagenes/bienes/'+recordset[i];
						ev = eval ("document.form_foto.div_"+num)
						ev.style.display='none';
						ev = eval ("document.form_foto.eliminar"+num)
						ev.style.display='';
						ev = eval ("document.form_foto.eliminar_nombre_foto_vie"+num)
						ev.value=recordset[i];
					}
					crear(this,0);
					getObj('eliminar_todos').style.display='';
					getObj('fotos_bien_pr_btn_eliminar').style.display='';
				 }
				 else
				 {
					 crear(this,0);
				 	setBarraEstado("");
				 }
			 }
		});	 	 
}
//
//
//----------------------------------------------------------------


$("#fotos_bien_pr_btn_guardar").click(function() {
if ($('#form_foto').jVal()){	
	if(num!=0){
		getObj('fotos_bien_pr_num').value = num;
		document.form_foto.action = 'modulos/bienes/bien/pr/sql.registrar_fotos_bien.php';
		document.form_foto.submit();
	}
	if(num==0){
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Tiene que Agregar por lo menos 1 Foto</p></div>",true,true);
	}
}
});


//----------------------------------------------------------------
//----------------------Actualizar--------------------------------
$("#custodio_db_btn_actualizar").click(function() {
	if ($('#form_db_custodio').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/custodio/db/sql.actualizar_custodio.php",
			data:dataForm('form_db_custodio'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('custodio_db_id_custodio').value='';
					getObj('custodio_db_nombre_custodio').value='';
					getObj('custodio_db_comentario').value = '';
					getObj('custodio_db_btn_actualizar').style.display='none';
					getObj('custodio_db_btn_guardar').style.display='';
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
//
$("#fotos_bien_pr_btn_eliminar").click(function() {
		var count=0;
		for(i=1; i<=num; i++){
				text = eval("document.form_foto.eliminar"+i);
				if (text.value){
					if(text.checked==true){
						count=1;
					}
				}
		}
		if(count==0){
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Tiene que Seleccionar por lo menos 1 Foto</p></div>",true,true);
		}
			if(count==1){
		getObj('fotos_bien_pr_num').value = num;
		document.form_foto.action = 'modulos/bienes/bien/pr/sql.eliminar_fotos_bien.php';
		document.form_foto.target='eliminar_foto';
		document.form_foto.submit();
		setBarraEstado("<iframe id='mensaje' scrolling='no' style='width:250px; height:120px; border:0' src='modulos/bienes/bien/pr/msj_eliminar.php'></iframe>",true,true);
		
			}
});


//
//
function eliminar_todos(){
	if(getObj('eliminar_all').checked==true){
		for(i=1; i<=num; i++){
			text = eval("document.form_foto.eliminar"+i);
			if(text.value){
				text.checked=true;
				eliminar(i);
			}
		}
	}
	if(getObj('eliminar_all').checked==false){
		for(i=1; i<=num; i++){
			text = eval("document.form_foto.eliminar"+i);
			if(text.value){
				text.checked=false;
				text = eval("document.form_foto.eliminar_nombre_foto"+i);
				text.value='';
			}
		}
	}
}
//
//
// ******************************************************************************

$("#fotos_bien_pr_btn_cancelar").click(function() {
//clearForm('form_db_custodio');
limpiar(1);
setBarraEstado("");
});
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------
/* ******************************************************************************


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
$('#fotos_bien_pr_codigo_bien').alpha({allow:'0123456789-_ '});
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
<!-- -->
<div id="botonera">
	<img id="fotos_bien_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="fotos_bien_pr_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif" style="display:none"/>
    <img style="display:none" id="custodio_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
    <img id="fotos_bien_pr_btn_agregar" style="display:none; overflow:hidden; cursor:pointer" src="imagenes/add.png" onclick="crear(this,0)"/>
	<img id="fotos_bien_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>

<div id="index" style="position:absolute; width:100%; height:100%; top:0%; left:0%; opacity:0.5; display:none; z-index:1">
<img src="imagenes/iconos/fondo.gif" style="height:100%; width:100%"/>
</div>
<div id="prueba" align="center" style="position:absolute; top:50%; left:40%; border:3px solid; border-color:#CCC; background:#FFF; display:none; z-index:2">
<img id="foto_logo" src="imagenes/iconos/logo1.jpg" onclick="change_picture();"/>
<div><img id="foto_loading" src="imagenes/iconos/ajax-loader.gif"/></div>
</div>
</head>
<body>
<iframe id="foto_cache" name="foto_cache" style="display:none"></iframe>
<iframe id="limpiar_foto" name="limpiar_foto" style="display:none"></iframe>
<iframe id="eliminar_foto" name="eliminar_foto" style="display:none"></iframe>
<form name="form_foto" id="form_foto" enctype="multipart/form-data" method="POST" action="modulos/bienes/bien/pr/vista_previa.php" target="foto_cache">
<input type="hidden" id="fotos_bien_pr_id_bienes" name="fotos_bien_pr_id_bienes" />
<input type="hidden" id="pos" name="pos" value="" />
<input type="hidden" id="error_formato" name="error_formato" onclick="error_f();"/>
<input type="hidden" id="error_tamano" name="error_tamano" onclick="error_t()" />
<input type="hidden" id="fotos_bien_pr_num" name="fotos_bien_pr_num" />
<input type="hidden" id="fotos_bien_pr_guardar" name="fotos_bien_pr_guardar" onclick="guardar();" />
<input type="hidden" id="fotos_bien_pr_limpiar" name="fotos_bien_pr_limpiar" onclick="limpiar(1)" />
<input type="hidden" id="fotos_bien_pr_fechact" name="fotos_bien_pr_fechact" value="<?php echo date("d-m-Y");?>" />

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="3">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Foto(s) del Activo			</th>
	</tr>
    	<tr>
			<th>Bien</th>
		  <td>
          <ul class="input_con_emergente">
				<li>
		   <input readonly="true" name="fotos_bien_pr_codigo_bien" type="text" id="fotos_bien_pr_codigo_bien" size="15" maxlength="20" onchange="consulta_automatica_codigo_bien();" message="Introduzca el Codigo del Bien." value="<?= $codigo;?>"/>
           <input name="fotos_bien_pr_nombre_bien" type="text"  id="fotos_bien_pr_nombre_bien" maxlength="60" size="30" readonly="true" value="<?=$nombre;?>"/>
   </li>
				<li id="fotos_bien_pr_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		  <td id="eliminar_todos" style="display:none"><label>
		    <input type="checkbox" name="eliminar_all" id="eliminar_all" value="1" onclick="eliminar_todos()"/>
	      </label>Seleccionar Todos</td>
		</tr>
		<tr>
			<th>Foto(s): </th>
		  <td colspan="2"><label>
		    <fieldset id="fiel" style="border:0">

</fieldset>
		  </label></td>
		</tr>

 
		<tr>
			
		</tr>
		<tr>
			<td colspan="3" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
<label>
</label>
</form>
<form id="form_limpiar_cache" name="form_limpiar_cache" method="post" action="modulos/bienes/bien/pr/limpiar_cache.php" target="limpiar_foto">
  <label>
    <input type="hidden" name="nombre" id="nombre" />
  </label>
</form>
<script type="text/javascript">
<!--
num=0;
function crear(obj,obj2) {
  num++;
  fi = document.getElementById('fiel'); 
  contenedor = document.createElement('div'); 
  contenedor.id = 'div_'+num; 
  fi.appendChild(contenedor); 

  //vista de la Imagen
  //
  ele = document.createElement('img'); 
  ele.name = 'vista_foto'+num; 
  ele.src = 'imagenes/iconos/sombra.bmp';
  ele.style.height = '80px';
  ele.style.width = '100px';
  ele.onclick = function(){ver(this.name)}
  contenedor.appendChild(ele); 
  //
  
  ele = document.createElement('input'); 
  ele.type = 'file'; 
  ele.name = 'foto_'+num;
  ele.onchange = function (){tiempo(this.name)}
  ele.onclick = function(){change_picture2(this.name)}
  contenedor.appendChild(ele); 
  
  ele = document.createElement('img'); 
  ele.name = 'div_'+num; 
  ele.style.cursor = 'pointer';
  ele.style.overflow = 'hidden';
  ele.src = 'imagenes/tab_close-on.gif';

  ele.onclick = function () {borrar(this.name)} 
  contenedor.appendChild(ele); 
  
  //
  //if(obj2==1){
  
  //}
  //
  ele = document.createElement('input'); 
  ele.type = 'checkbox'; 
  ele.name = 'eliminar'+num;
  ele.value = num;
  ele.style.display='none';
  //ele.onchange = function (){tiempo(this.name)}
  ele.onclick = function(){eliminar(this.value)}
  contenedor.appendChild(ele);
  //
  ele = document.createElement('input');
  ele.type = 'hidden';
  ele.name = 'eliminar_nombre_foto'+num;
  contenedor.appendChild(ele);
  //
  ele = document.createElement('input');
  ele.type = 'hidden';
  ele.name = 'eliminar_nombre_foto_vie'+num;
  contenedor.appendChild(ele);
  //
  ele = document.createElement('input');
  ele.type = 'hidden';
  ele.name = 'nombre_foto'+num;
  contenedor.appendChild(ele);
}
//
//
if(getObj('fotos_bien_pr_codigo_bien').value!=''){
	getObj('fotos_bien_pr_btn_agregar').style.display='';
	crear(this,1);

}
//
//
function borrar(obj) {
  fi = document.getElementById('fiel'); 
  fi.removeChild(document.getElementById(obj)); 
}
//
//
function eliminar(obj){
	concad = eval("document.form_foto.vista_foto"+obj);
	text = concad.src;
	tam = text.length;
	pos = text.lastIndexOf('/');
	concad = eval("document.form_foto.eliminar_nombre_foto"+obj);
	text = text.substr(pos+1,tam);
	text = text.replace('%20',' ');
	concad.value = text;
}
//
//document.getElementById('prueba').style.opacity='0.75';
	var c=0;
	var timer;
	var sust;
	var sust2;
	//
	//
	function change_picture(){
		var text = sust;
		//var text = document.foto_logo.src;
		var tam = text.length;
		var pos = text.lastIndexOf('_');
		//var pos = text.lastIndexOf('/');
		text = text.substr(pos+1, tam-1);
		text = eval("document.form_foto.vista_foto"+text);
		text.src = 'imagenes/iconos/ajax-loader2.gif';
		//if (text=='logo1.jpg')
		//	document.foto_logo.src='imagenes/iconos/logo2.jpg';
		//if (text=='logo2.jpg')
		//	document.foto_logo.src='imagenes/iconos/logo1.jpg';
		
			
		c++;
		if (c==4){
		clearInterval(timer);
		//document.getElementById('prueba').style.display='none';
		//document.getElementById('index').style.display='none';
		c=0;
		cargar_cache(sust);
		}
		
	}
	function tiempo(obj){
		sust = obj;
		//document.getElementById('prueba').style.display='';
		//document.getElementById('index').style.display='';
		timer =	setInterval("change_picture();",150);
	}
	
	function cargar_cache(obj){
		document.form_foto.pos.value = obj;
		
		//document.getElementById('foto_cache').src = 'modulos/bienes/bien/pr/vista_previa.php';
		document.form_foto.submit();
	}
	function ver(obj){
		alert( document.body.clientWidth);
	}
	function error_f(){
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/sombra.bmp />El tipo de Imagen tiene que ser: jpeg</p></div>",true,true);
	}
	function error_t(){
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/sombra.bmp />El tama&ntilde;o de la imagen tiene que ser menor a 1 MB</p></div>",true,true);
	}
	function guardar(){
		setBarraEstado("<iframe id='mensaje' scrolling='no' style='width:250px; height:120px; border:0' src='modulos/bienes/bien/pr/msj_actualizar.php'></iframe>",true,true);
		limpiar(1);
	}
	function limpiar(obj){
		fi = document.getElementById('fiel'); 
		if (num>0){
			for (i=1; i<=num; i++){
				if (document.getElementById('div_'+i))
  				fi.removeChild(document.getElementById('div_'+i));
			}
		}
		num=0;
		getObj('eliminar_all').checked=false;
		getObj('eliminar_todos').style.display='none';
		if(obj==1){
		getObj('fotos_bien_pr_id_bienes').value='';
		getObj('fotos_bien_pr_codigo_bien').value='';
		getObj('fotos_bien_pr_nombre_bien').value='';
		getObj('fotos_bien_pr_btn_eliminar').style.display='none';
		getObj('fotos_bien_pr_btn_agregar').style.display='none';
		}
	}
</script>
</body>
</html>