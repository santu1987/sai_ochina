<? if (!$_SESSION) session_start();
?>
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql="SELECT * FROM tipo_nomina ORDER BY id_tipo_nomina";
$rs_tipo =& $conn->Execute($sql);
while (!$rs_tipo->EOF) {
	$opt_tipo.="<option value='".$rs_tipo->fields("id_tipo_nomina")."' >".$rs_tipo->fields("nombre")."</option>";
$rs_tipo->MoveNext();
}
?>
<script>
var dialog;
//------------------------------para llenar el combo numero nomina -------------------
/*$(document).ready(function(){
	// Parametros para e combo1
   $("#tipo_nomina_pronomi").change(function () {
   		$("#tipo_nomina_pronomi option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("modulos/rrhh/nomina/pr/sql.combo.php", { elegido: elegido }, function(data){
				$("#numero_nomina_pronomi").html(data);
			});			
        });
   })
});*/
//----------------------------------------------------------------
//----------------------------- limpiar el campo numero nomina ------------------------////
$(document).ready(function(){
	// Parametros para e combo1
   $("#nomina_pr_btn_cancelar").click(function () {
		elegido=0;
		$.post("modulos/rrhh/nomina/pr/sql.combo.php", { elegido: elegido }, function(data){
				$("#numero_nomina_pronomi").html(data);
			});			
   })
});
///--------------------------- fin de limpiar ---------------------------------------////
//-------------------------------------------------------------------------------/////
$("#tipo_nomina_pronomi").change(function () {
		var id_tn=getObj('tipo_nomina_pronomi').value;
		getObj('id_tipo_nomina').value=id_tn;
});
$("#nume_nomina").change(function () {
		getObj('nomina_pr_lista').style.display='';
});
$("#nomina_pr_btn_guardar").click(function() {
	if ($('#form_pr_nomina').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/nomina/pr/sql.registrar_nomina.php",
			data:dataForm('form_pr_nomina'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar()
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
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
function limpiar(){
	getObj('tipo_nomina_pronomi').selectedIndex=0;
	//getObj('nomina_pr_lista').style.display='none';
	getObj('id_tipo_nomina').value="";
	getObj('nume_nomina').value="";
	getObj('th_fechas').style.display='none';
	getObj('fecha_desde').value='';
	getObj('fecha_hasta').value='';
	getObj('nomina_pr_numero_nomina').value='';
	setBarraEstado("");
}
$("#nomina_pr_btn_cancelar").click(function() {
limpiar();
});
//-----------------------
$("#nomina_pr_btn_consulta_emergente_numero_nomina").click(function() {
if(getObj('id_tipo_nomina').value!=""){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/nomina/pr/vista.grid_numero_nomina.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente Numero de Nomina', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var tipo_nomina= jQuery("#id_tipo_nomina").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/pr/sql.numero_nomina.php?tipo_nomina="+tipo_nomina,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#nomina_pr_descripcion").keypress(function(key)
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
							var tipo_nomina= jQuery("#id_tipo_nomina").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/pr/sql.numero_nomina.php?tipo_nomina="+tipo_nomina,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		function crear_grid()
						{
							
							var id_tipo_nomina=getObj('id_tipo_nomina').value;
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:550,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/nomina/pr/sql.numero_nomina.php?id_tipo_nomina='+id_tipo_nomina,
								datatype: "json",
								colNames:['ID','Numero','Fecha Desde','Fecha Hasta'],
								colModel:[
									{name:'id_nominas',index:'id_nominas',hidden:true},
									{name:'numero',index:'numero', width:25},
									{name:'desde',index:'desde', width:50},
									{name:'hasta',index:'hasta', width:50}],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									dialog.hideAndUnload();
									//var nomin=ret.numero+")"+" "+ret.desde+"-"+ret.hasta;
									getObj('nomina_pr_numero_nomina').value=ret.numero;
									getObj('th_fechas').style.display='';
									var fecha_desde=ret.desde;
									var ano_desde=fecha_desde.substr(0,4);
									var mes_desde=fecha_desde.substr(5,2);
									var dia_desde=fecha_desde.substr(8,2);
									var fecha_hasta=ret.hasta;
									var ano_hasta=fecha_hasta.substr(0,4);
									var mes_hasta=fecha_hasta.substr(5,2);
									var dia_hasta=fecha_hasta.substr(8,2);
									//getObj('nomina_pr_lista').style.display='';
									consulta_automatica_nomina();
									if(mes_desde==1 && mes_hasta==1){
										mes_desde="ENE";
										mes_hasta="ENE";
									}
									if(mes_desde==2 && mes_hasta==2){
										mes_desde="FEB";
										mes_hasta="FEB";
									}
									if(mes_desde==3 && mes_hasta==3){
										mes_desde="MAR";
										mes_hasta="MAR";
									}
									if(mes_desde==4 && mes_hasta==4){
										mes_desde="ABR";
										mes_hasta="ABR";
									}
									if(mes_desde==5 && mes_hasta==5){
										mes_desde="MAY";
										mes_hasta="MAY";
									}
									if(mes_desde==6 && mes_hasta==6){
										mes_desde="JUN";
										mes_hasta="JUN";
									}
									if(mes_desde==7 && mes_hasta==7){
										mes_desde="JUL";
										mes_hasta="JUL";
									}
									if(mes_desde==8 && mes_hasta==8){
										mes_desde="AGO";
										mes_hasta="AGO";
									}
									if(mes_desde==9 && mes_hasta==9){
										mes_desde="SEP";
										mes_hasta="SEP";
									}
									if(mes_desde==10 && mes_hasta==10){
										mes_desde="OCT";
										mes_hasta="OCT";
									}
									if(mes_desde==11 && mes_hasta==11){
										mes_desde="NOV";
										mes_hasta="NOV";
									}
									if(mes_desde==12 && mes_hasta==12){
										mes_desde="DIC";
										mes_hasta="DIC";
									}
									
									fecha_desde=dia_desde+"-"+mes_desde+"-"+ano_desde;
									fecha_hasta=dia_hasta+"-"+mes_hasta+"-"+ano_hasta;
									getObj('fecha_desde').value=fecha_desde;
									getObj('fecha_hasta').value=fecha_hasta;
									var numero=getObj('nomina_pr_numero_nomina').value;
									var id_tipo_nomina=getObj('id_tipo_nomina').value;
									//var url= "modulos/rrhh/nomina/pr/prueba.php?fecha_desde="+ret.desde+"&fecha_hasta="+ret.hasta+"&numero="+numero+"&id_tipo_nomina="+id_tipo_nomina+"&id_nominas="+ret.id_nominas;
									var url= "pdfb.php?p=modulos/rrhh/nomina/rp/vista.lst.calculo_pre_nomina.php!fecha_desde="+ret.desde+"@fecha_hasta="+ret.hasta+"@id_tipo_nomina="+id_tipo_nomina+"@id_nominas="+ret.id_nominas;
									
						openTab("Rep. Pre-Nomina ",url);
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								/*$("#nomina_pr_descripcion").focus();
								$('#nomina_pr_descripcion').alpha({allow:' '});*/
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_nominas',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
}});
//Validacion de los cam
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
//****************************************************************************************
//*                        CODIGO PARA CONSULTA AUTOMATICA                               *
function consulta_automatica_nomina()
{	

		/*$.ajax({
			url:"modulos/rrhh/nomina/pr/sql_busca_calculos_nomina.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_nomina'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		  var recordset=html
				if(recordset)
				{
				recordset = recordset.split("*");
				id = recordset[0];
				concepto =recordset[1];
				total =recordset[4];
				alert("ID: "+id+" CONCEPTO: "+concepto+" TOTAL: "+total);
				}
				else
			   	{  
					alert('noooo');
			    }
			 }
		});	 */	 
}
//****************************************************************************************
</script><style type="text/css">
<!--
.cuerpo_formu{
	min-width:600px;
	padding:0px;
	border-spacing:0px;
	width:1%;
	margin:auto;
}
.cuerpo_formu th{
	vertical-align:top;
	border-bottom:1px #BADBFC solid;
	border-left:1px #BADBFC solid;			
	padding:2px;
	text-align:justify;
	width:1%;
	white-space: nowrap;
}
-->
</style>
<div id="botonera">
	<img id="nomina_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="nomina_pr_btn_guardar" src="imagenes/iconos/cerrar_orden_cxp.png" />
</div>


<form name="form_pr_nomina" id="form_pr_nomina">
  <table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Procesar Nomina</th>
	</tr>
         <tr>
			<th width="289">Tipo de Nomina:</th>
		  <td><select name="tipo_nomina_pronomi" id="tipo_nomina_pronomi">
		    <option value="0">--SELECCIONE--</option>
		    <?=$opt_tipo?>
	       </select>
	       <input type="hidden" name="id_tipo_nomina" id="id_tipo_nomina" /></td>
		</tr>
         <tr>
           <th>Num. de Nomina:</th>
           <td>
           <ul class="input_con_emergente">
				<li>
           <input name="nomina_pr_numero_nomina" type="text" id="nomina_pr_numero_nomina" maxlength="2" size="4" readonly="true" style="text-align:center"/>
           <input type="hidden" name="nume_nomina" id="nume_nomina" />
			 </li>
				<li id="nomina_pr_btn_consulta_emergente_numero_nomina" class="btn_consulta_emergente"></li>
		   </ul></td>
         </tr>
         <tr>
           <th colspan="2" id="th_fechas" style="display:none">
           <div align="center">Fecha Desde:
             <label for="fecha_hasta"></label>
               <input name="fecha_desde" type="text" id="fecha_desde" style="border:none; background:none; font-weight:bold; color:#666;" size="10" readonly="readonly"/>
&nbsp;&nbsp;&nbsp;Fecha Hasta:
<label for="textfield3"></label>
<input name="fecha_hasta" type="text" id="fecha_hasta" size="10" readonly="readonly" style="border:none; background:none; font-weight:bold; color:#666;"/>
           </div></th>
         </tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>