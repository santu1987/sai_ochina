<? if (!$_SESSION) session_start();
?>
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="	SELECT 
			tipo_nomina.id_tipo_nomina,
			tipo_nomina.nombre,
			frecuencia.descripcion
		FROM 
			tipo_nomina 
		INNER JOIN
			frecuencia
		ON
			frecuencia.id_frecuencia=tipo_nomina.id_frecuencia
		ORDER BY nombre";
$rs_concepto =& $conn->Execute($sql);
while (!$rs_concepto->EOF){
	$opt_concepto.="<option value='".$rs_concepto->fields("id_tipo_nomina")."*".$rs_concepto->fields("descripcion")."' >".$rs_concepto->fields("nombre")."</option>";
	$rs_concepto->MoveNext();
	
} 
?>
<script>
$("#nominas_db_tipo_nomina").change(function() {
	var cadena=getObj('nominas_db_tipo_nomina').value;
	var tam= cadena.length;
	var id=cadena.indexOf('*');
	var cadena1=cadena.substr(0,id);
	var cadena2=cadena.substr(id+1,tam);
	getObj('nominas_db_id_tn').value=cadena1;
	getObj('nominas_db_numero').value=cadena2;
	getObj('th_numero').style.display='';
	getObj('th_tabla').style.display='';
	getObj('nominas_db_tipo_nomina').disabled=true;
	var can=getObj('nominas_db_numero').value;
	if(can=="Quincenal"){
		anadir_quincenal();
		getObj('valor').value=24;
	}
	else{
		anadir_mensual();
		getObj('valor').value=12;
	}
});
function anadir_quincenal() {	
  	getObj('tabla_quincenal').style.display='';
  	getObj('tabla_mensual').style.display='none';
  	
}
function anadir_mensual() {
	
  	getObj('tabla_mensual').style.display='';
  	getObj('tabla_quincenal').style.display='none';
}
</script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script>
var dialog;
//----------------------------------------------------------------------------------------------------

$("#nominas_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/nominas/db/vista.grid_nominas_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Nominas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#nominas_db_nombre").val();
					var busq_fechad= jQuery("#nominas_db_fechad").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nominas/db/sql_nominas_nombre.php?busq_nombre="+busq_nombre+"&busq_fechad="+busq_fechad,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#nominas_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#nominas_db_fechad").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#nominas_db_fechah").keypress(function(key)
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
							var busq_nombre= jQuery("#nominas_db_nombre").val();
							var busq_fechad= jQuery("#nominas_db_fechad").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nominas/db/sql_nominas_nombre.php?busq_nombre="+busq_nombre+"&busq_fechad="+busq_fechad,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:550,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/nominas/db/sql_nominas_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Tipo Nomina','Numero Nomina','Fecha Desde','Fecha Hasta','','',''],
								colModel:[
									{name:'id_nominas',index:'id_nominas',hidden:true},
									{name:'nomina',index:'nomina', width:70},
									{name:'numero',index:'numero', width:70},
									{name:'desde',index:'desde',width:70},
									{name:'hasta',index:'hasta',width:70},
									{name:'procesada',index:'procesada',hidden:true},
									{name:'id_tipo_nomina',index:'id_tipo_nomina', hidden:true},
									{name:'frecu',index:'frecu', hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//aqui van los .ret
									getObj('numero_nomina').value=ret.numero;
									getObj('nominas_db_fecha_desdem').value=ret.desde;
									getObj('nominas_db_fecha_hastam').value=ret.hasta;
									getObj('nominas_db_id_nominas').value=ret.id_nominas;
									getObj('nominas_db_id_tn').value=ret.id_tipo_nomina;
									getObj('nom_tipo_nomina').value=ret.nomina;
									getObj('nominas_db_numero').value=ret.frecu;
									getObj('nom_tipo_nomina').style.display='';
									getObj('tabla_quincenal').style.display='none';
  									getObj('tabla_mensual').style.display='none';
									getObj('nominas_db_tipo_nomina').style.display='none';
									getObj('th_numero').style.display='';
									getObj('th_tabla').style.display='';
									var procesa=ret.procesada;
									//alert(procesa);
									if(procesa==1){ getObj('precesada_modifica').value="Si";}
									if(procesa==0){ getObj('precesada_modifica').value="No";}
									getObj('tabla_modificar').style.display='';
									getObj('nominas_db_btn_guardar').style.display = 'none';
									getObj('nominas_db_btn_actualizar').style.display = '';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$('#nominas_db_nombre').alpha({allow:' '});
								$('#nominas_db_fechad').numeric({allow:'/- '});
								$('#nominas_db_fechah').numeric({allow:'/- '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_nominas',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});




//
//

//
//
//----------------------------------------------------------------


$("#nominas_db_btn_guardar").click(function() {
	if ($('#form_db_nominas').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/nominas/db/sql.registrar_nominas.php",
			data:dataForm('form_db_nominas'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar_campo();
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[existe_nomina],true,true);
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


//----------------------------------------------------------------
//----------------------Actualizar--------------------------------
$("#nominas_db_btn_actualizar").click(function() {
	if ($('#form_db_nominas').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/nominas/db/sql.actualizar_nominas.php",
			data:dataForm('form_db_nominas'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					limpiar_campo();
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
$("#nominas_db_btn_eliminar").click(function() {
	if ($('#form_db_nominas').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/nominas/db/sql.eliminar_nominas.php",
			data:dataForm('form_db_nominas'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('nominas_db_id_nominas').value='';
					getObj('nominas_db_nombre_nominas').value='';
					getObj('nominas_db_comentario').value = '';
					getObj('nominas_db_btn_actualizar').style.display='none';
					getObj('nominas_db_btn_eliminar').style.display='none';
					getObj('nominas_db_btn_guardar').style.display='';
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Relacion_Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
//					clearForm('form_db_valor_impuesto');
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
function limpiar_campo(){
	getObj('form_db_nominas').reset();
	getObj('nominas_db_tipo_nomina').disabled=false;
	getObj('th_numero').style.display='none';
	getObj('th_tabla').style.display='none';
	getObj('tabla_modificar').style.display='none';
	getObj('nom_tipo_nomina').style.display='none';
	getObj('nominas_db_tipo_nomina').style.display='';
	getObj('nominas_db_btn_actualizar').style.display='none';
	getObj('nominas_db_btn_eliminar').style.display='none';
	getObj('nominas_db_btn_guardar').style.display='';
	getObj('nominas_db_frecuencia').selectedIndex=0;
}
$("#nominas_db_btn_cancelar").click(function() {
//clearForm('form_db_nominas');
limpiar_campo();
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
$('#nominas_db_cedula_nominas').numeric({allow:' '});
$('#nominas_db_nombre_nominas').alpha({allow:'() '});
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
	<img id="nominas_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img style="display:none" id="nominas_db_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif"/>
	<img id="nominas_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" style="display:none"/>
    <img style="display:none" id="nominas_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="nominas_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>

<?php  
$ano= date("Y");;
$mes=1;
$bisiesto=date("L");
?>    
<?php
	echo "<script> frecuencia; </script>";
?>
<form name="form_db_nominas" id="form_db_nominas">
  <table width="489" class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="4">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Número de Nominas
				<input type="hidden" name="nominas_db_id_nominas" id="nominas_db_id_nominas"/></th>
	</tr>
        <tr>
			<th width="118">Tipo Nomina:</th>
		  <td width="445" colspan="3"><select name="nominas_db_tipo_nomina" id="nominas_db_tipo_nomina">
		    <option value="0">-- SELECCION --</option>
		    <?= $opt_concepto?>
	      </select>
		    <label>
		      <input name="nom_tipo_nomina" type="text" id="nom_tipo_nomina" readonly="readonly" style="display:none" />
		    </label>
		    <label>
		      <input type="hidden" name="nominas_db_id_tn" id="nominas_db_id_tn" />
	          <input type="hidden" name="valor" id="valor" />
		    </label></td>
		</tr>
        <tr>
          <th>Frecuencia:</th>
          <td colspan="3"><input name="nominas_db_numero" type="text" disabled="disabled" id="nominas_db_numero" value="" size="10" readonly="readonly" /></td>
        </tr>
        <tr>
          <th id="th_numero" colspan="4" bgcolor="#4c7595" style="color:#FFF; font-weight:bold; display:none" align="center">
          		<div align="center">Fecha de las Nominas</div></th>
        </tr>
        <tr>
          <th colspan="4" id="th_tabla" style="display:none"> 
          <div align="center">
            <table id="tabla_quincenal" width="80%" border="0" class="cuerpo_formulario" style="display:none">
               <tr>
          <th style=" border-left:none" width="29%"><div align="center">Nº Nomina</div></th>
          <th style=" border-left:none" width="33%"><div align="center">Desde</div></th>
          <th style=" border-left:none" width="38%"><div align="center">Hasta</div></th>
          <th style=" border-left:none; border-right:none" width="38%"><div align="center">¿Elaborada?</div></th>
        </tr>
        <?php 
		$nomina=0;
	  	
		for($mes=1;$mes<=12;$mes++){
			for($i=1;$i<=2;$i++){
			$dia=30; 
			if($mes==2){
				if($bisiesto==0){
					$dia=28;
				}else{
					$dia=29;
				}
			} 
			if($mes==1 || $mes==3 || $mes==5 || $mes==7 || $mes==8 || $mes==10 || $mes==12){
				$dia=31;
			}
			$nomina++;
			if ($nomina==1 || $nomina==3 || $nomina==5 || $nomina==7 || $nomina==9 || $nomina==11 || $nomina==13 || $nomina==15 || $nomina==17 || $nomina==19 || $nomina==21 || $nomina==23 ){
				$desde="01/".$mes."/".$ano;
				$hasta="15/".$mes."/".$ano;
			}
			else{
				$desde="16/".$mes."/".$ano;
				$hasta=$dia."/".$mes."/".$ano;
			}
	  ?>
        <tr>
          <th style=" border-left:none"><div align="center"><?php echo $nomina; ?></div></th>
          <td><div align="center"><input name="desde_q<?php echo $nomina; ?>" type="text" value="<?php echo $desde; ?>" style="background-color:#ffffff; border:none; text-align:center; color:#555; font-weight:bold;" readonly="readonly"/></div></td>
          <td><div align="center"><input name="hasta_q<?php echo $nomina; ?>" type="text" value="<?php echo $hasta; ?>" style="background-color:#ffffff; border:none; text-align:center; color:#555; font-weight:bold;" readonly="readonly"/></div></td>
          <td style="border-right:none"><div align="center"><p style="color:#555; font-weight:bold;">No</p></div></td>
        </tr>
      <?php }}//fin del for ?>
            </table>
          </div>
<div align="center">
  <table id="tabla_mensual" width="100%" border="0" class="cuerpo_formulario" style="display:none" >
    <tr>
      <th style=" border-left:none;" width="29%"><div align="center">Nº Nomina</div></th>
      <th style=" border-left:none;" width="33%"><div align="center">Desde</div></th>
      <th style=" border-left:none;" width="38%"><div align="center">Hasta</div></th>
      <th style=" border-left:none; border-right:none" width="38%"><div align="center">¿Elaborada?</div></th>
      </tr>
	  <?php 
	  for($i=1;$i<=12;$i++){
		$dia=30; 
	  	if($i==2){
			if($bisiesto==0){
				$dia=28;
			}else{
				$dia=29;
			}
		} 
		if($i==1 || $i==3 || $i==5 || $i==7 || $i==8 || $i==10 || $i==12){
			$dia=31;
		}
	  ?>
        <tr>
          <th style=" border-left:none;"><div align="center"><?php echo $i; ?></div></th>
          <td><div align="center"><input name="desde_m<?php echo $i; ?>" type="text" value="<?php echo "01"."/".$i."/".$ano ?>" style="background-color:#ffffff; border:none; text-align:center; color:#555; font-weight:bold;" readonly="readonly"/></div></td>
          <td><div align="center"><input name="hasta_m<?php echo $i; ?>" type="text" value="<?php echo $dia."/".$i."/".$ano ?>" style="background-color:#ffffff; border:none; text-align:center; color:#555; font-weight:bold;" readonly="readonly"/></div></td>
          <td style=" border-right:none"><div align="center"><p style="color:#555; font-weight:bold;">No</p></div></td>
        </tr>
      <?php }//fin del for ?>
  </table>
</div>
	</th>
        </tr>
		<tr>
			<td colspan="4" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
  <p>&nbsp;</p>
</form>