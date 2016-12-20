<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

/*$sql_usuarios="SELECT
	     			unidad_ejecutora.id_unidad_ejecutora,unidad_ejecutora.nombre as unidad
				FROM
					usuario
				INNER JOIN
					unidad_ejecutora
				ON
					usuario.id_unidad_ejecutora=unidad_ejecutora.id_unidad_ejecutora		
				where
					id_usuario=".$_SESSION['id_usuario']."";	
$row_prueba=& $conn->Execute($sql_usuarios);
$id_unidad_ejecutora=$row_prueba->fields("id_unidad_ejecutora");	
$unidad_nombre=$row_prueba->fields("unidad");										
if($id_unidad_ejecutora=='11')
{
*/	
$id_unidad_ejecutora='11';
	$combo_opcion1="Recibido/Tesoreria";
	$combo_opcion2="Caja";
	$combo_opcion3="Pagado";
/*}elseif($id_unidad_ejecutora=='4')
{
	$combo_opcion1="Recibido/Finanzas";
	$combo_opcion2="Recibido/Administracion";
	$combo_opcion3="";
}
elseif($id_unidad_ejecutora=='2')
{
	$combo_opcion1="Recibido/Contabilidad";
	$combo_opcion2="Archivado/Contabilidad";
	$combo_opcion3="";
}
*/?>
<input name="fecha_hoy" id="fecha_hoy" type="hidden" value="<?php echo date("d-m-Y"); ?>" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript">
///////////////////////////////////////////////////////////////////////////////
$("#tesoreria_estatus_btn_guardar").click(function() {
if(getObj('tesoreria_estatus_db_id_cheque_oculto').value!="" && getObj('fecha_proceso').value<=getObj('fecha_hoy').value)
{
	
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.cheques_modificar_estatus.php",
			data:dataForm('form_tesoreria_estatus_db_cheques'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//jQuery("#list_estatus").setGridParam({url:"modulos/tesoreria/cheques/pr/sql.estatus.php?"}).trigger("reloadGrid");
					getObj('tesoreria_estatus_db_id_cheque_oculto').value="";
					getObj('tesoreria_estatus_db_unidad').value="1"
					 jQuery("#list_estatus").setGridParam({url:"modulos/tesoreria/cheques/pr/sql.estatus.php?combo="+getObj('tesoreria_estatus_db_unidad').value+"fecha_proceso="+getObj('fecha_proceso').value,page:1}).trigger("reloadGrid");

				}
				else if (html=="NoRegistro")
				{
					alert("La cuenta del usuario no posee chequera registrada,por favor consulte las mismas en el modulo chequeras");
					jQuery("#list_estatus").setGridParam({url:"modulos/tesoreria/cheques/pr/sql.estatus.php"}).trigger("reloadGrid");
					getObj('tesoreria_estatus_db_id_cheque_oculto').value="";
				//	getObj(tesoreria_banco_nombre_combo_estatus).value="2"

				}
				else if(html=="Cheque_Pagado")
				{
				
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL CHEQUE SE ENCUENTRA EN ESTATUS PAGADO	</p></div>",true,true);
	
				}
					else
				{
					alert(html);
					setBarraEstado(html);
					jQuery("#list_estatus").setGridParam({url:"modulos/tesoreria/cheques/pr/sql.estatus.php"}).trigger("reloadGrid");
					getObj('tesoreria_estatus_db_id_cheque_oculto').value="";
			}
			
			}
		});
	
}else
	alert("No puede guardar el registro sin haber elegido por lo menos un cheque// La fecha no puede ser mayor a la fecha de hoy");	
});
////////////////////////////////////////////
var lastsel,idd,monto,urls;
urls='modulos/tesoreria/cheques/pr/sql.estatus.php?combo='+getObj('tesoreria_estatus_db_unidad').value;
//setBarraEstado(urls);
$("#list_estatus").jqGrid
({ 
	height:270,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",
	url:urls,
	datatype: "json",
   	colNames:['&ordm;Id Cheque','Banco','Cuenta','Id_Banco','Cheque','Beneficiario','Monto','Fecha Emitido','Ordenes','Chequera','tipo','Estatus'],
   	colModel:[
			{name:'id',index:'id',width:120,hidden:true},
			{name:'banco',index:'banco',width:120},
			{name:'n_cuenta',index:'n_cuenta',width:150},
			{name:'id_banco',index:'id_banco',width:170,hidden:true},
			{name:'n_cheque',index:'n_cheque', width:80},
			{name:'proveedor',index:'proveedor',width:120},
			{name:'monto',index:'monto',width:100},
			{name:'fecha_cheque',index:'fecha_cheque',width:100},
			{name:'ordenes',index:'ordenes',width:100,hidden:true},
			{name:'n_chequera',index:'n_chequera',width:82,hidden:true},
			{name:'tipo',index:'tipo',width:100,hidden:true},
			{name:'estatus',index:'estatus',width:100,hidden:true}
   	],rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_estatus'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	multiselect: true,
	onSelectRow: function(id){
        var ret = jQuery("#list_estatus").getRowData(id);
	   	s = jQuery("#list_estatus").getGridParam('selarrrow');
		//alert(s);
		idd = ret.id;
		
		if(id && id!==lastsel)
		{
			getObj('tesoreria_estatus_db_id_cheque_oculto').value=s;
		
		/*$.ajax({
					url:"modulos/cuentas_por_pagar/orden_pago/db/sql.consulta_sel.php?id="+idd+"&vector="+s+'&proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value,
					data:dataForm('form_cuentas_por_pagar_db_orden_pago'), 
					type:'GET',
					cache: false,
					 success:function(html)
					 {
						var recordset=html;	
						alert(html);			
						valor=parseFloat(recordset);
						valor = valor.currency(2,',','.');	
						getObj('cuentas_por_pagar_db_facturas_total').value=valor;
					}
					});	 */
		}
	},	
	/*	idd = ret.id;
								if((id && id!==lastsel)&&(s!=""))
								{
									if(getObj('id_cheque_oculto').value=="")
									{
									getObj('id_cheque_oculto').value=idd;
									}
									else
									{
									getObj('id_cheque_oculto').value=getObj('id_cheque_oculto').value+','+idd;
									
  							 		}
							  	}
								alert(getObj('id_cheque_oculto').value);
							 }*/
  onSelectAll: function(id){
        var ret = jQuery("#list_estatus").getRowData(id);
	   	s = jQuery("#list_estatus").getGridParam('selarrrow');
		//alert(s);
		idd = ret.id;
		if(id && id!==lastsel)
		{
			getObj('tesoreria_estatus_db_id_cheque_oculto').value=s;
				/*$.ajax({
					url:"modulos/cuentas_por_pagar/orden_pago/db/sql.consulta_sel.php?id="+idd+"&vector="+s+'&proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value,
					data:dataForm('form_cuentas_por_pagar_db_orden_pago'), 
					type:'GET',
					cache: false,
					 success:function(html)
					 {
						var recordset=html;	
						alert(html);			
						valor=parseFloat(recordset);
						valor = valor.currency(2,',','.');	
						getObj('cuentas_por_pagar_db_facturas_total').value=valor;
					}
					});	 */
		}
	}	
}).navGrid("#pager_estatus",{refresh:false,search :false,edit:false,add:false,del:false});
var timeoutHnd; 
var flAuto = true;
						$("#tesoreria_busqueda_usuario_estatus").keypress(function(key)
						{
							if (key.keycode==13)$("#estatus-busqueda_boton_filtro")
						});
						$("#tesoreria_busqueda_banco_estatus").keypress(function(key)
						{
							if (key.keycode==13)$("#estatus-busqueda_boton_filtro")
						});
						$("#tesoreria_busqueda_cuenta_estatus").keypress(function(key)
						{
							if (key.keycode==13)$("#estatus-busqueda_boton_filtro")
						});
						$("#tesoreria_busqueda_proveedor_estatus").keypress(function(key)
						{
							if (key.keycode==13)$("#estatus-busqueda_boton_filtro")
						});
function tesoreria_estatus_usuario_doSearch(ev)
{ 
	//alert("entro");
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(tesoreria_estatus_usuario_gridReload,500)
} 

function tesoreria_estatus_usuario_gridReload()
{ 
	  var tesoreria_busqueda_usuario_estatus = jQuery("#tesoreria_estatus_banco_nombre_usuario").val(); 
	  var tesoreria_busqueda_banco_estatus = jQuery("#tesoreria_estatus_banco_nombre_banco").val(); 
  	  var tesoreria_busqueda_cuenta_estatus = jQuery("#tesoreria_estatus_banco_numero_cuenta").val(); 
  	  var tesoreria_busqueda_proveedor_estatus = jQuery("#tesoreria_estatus_banco_nombre_proveedor").val(); 
	  var tesoreria_busqueda_beneficiario_estatus = jQuery("#tesoreria_estatus_banco_busqueda_beneficiario").val();
	  jQuery("#list_estatus").setGridParam({url:"modulos/tesoreria/cheques/pr/sql.estatus.php?tesoreria_busqueda_usuario_estatus="+tesoreria_busqueda_usuario_estatus+"&tesoreria_busqueda_banco_estatus="+tesoreria_busqueda_banco_estatus+"&tesoreria_busqueda_cuenta_estatus="+tesoreria_busqueda_cuenta_estatus+"&tesoreria_busqueda_proveedor_estatus="+tesoreria_busqueda_proveedor_estatus+"&tesoreria_busqueda_beneficiario_estatus="+tesoreria_busqueda_beneficiario_estatus+"&combo="+getObj('tesoreria_estatus_db_unidad').value,page:1}).trigger("reloadGrid");
	  //url="modulos/tesoreria/cheques/pr/sql.estatus.php?tesoreria_busqueda_usuario_estatus="+tesoreria_busqueda_usuario_estatus+"&tesoreria_busqueda_banco_estatus="+tesoreria_busqueda_banco_estatus+"&tesoreria_busqueda_cuenta_estatus="+tesoreria_busqueda_cuenta_estatus+"&tesoreria_busqueda_proveedor_estatus="+tesoreria_busqueda_proveedor_estatus+"&tesoreria_busqueda_beneficiario_estatus="+tesoreria_busqueda_beneficiario_estatus+"&combo="+getObj('tesoreria_estatus_db_unidad').value	
	  //alert(url);	
	 // setBarraEstado(url);
} 
	$("#estatus-busqueda_boton_filtro").click(function(){
	//alert("petrobon sansone jaaja");
	  jQuery("#list_estatus").setGridParam({url:"modulos/tesoreria/cheques/pr/sql.estatus.php?tesoreria_busqueda_usuario_estatus="+tesoreria_busqueda_usuario_estatus+"&tesoreria_busqueda_banco_estatus="+tesoreria_busqueda_banco_estatus+"&tesoreria_busqueda_cuenta_estatus="+tesoreria_busqueda_cuenta_estatus+"&tesoreria_busqueda_proveedor_estatus="+tesoreria_busqueda_proveedor_estatus+"&tesoreria_busqueda_beneficiario_estatus="+tesoreria_busqueda_beneficiario_estatus+"&combo="+getObj('tesoreria_estatus_db_unidad').value,page:1}).trigger("reloadGrid");

					        tesoreria_estatus_usuario_doSearch();
							
							
    					   	})
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}

function estatus_combo()
{
		getObj('tesoreria_cheque_estatus_imp').style.display='';
 jQuery("#list_estatus").setGridParam({url:"modulos/tesoreria/cheques/pr/sql.estatus.php?combo="+getObj('tesoreria_estatus_db_unidad').value,page:1}).trigger("reloadGrid");
url="modulos/tesoreria/cheques/pr/sql.estatus.php?combo="+getObj('tesoreria_estatus_db_unidad').value;
//alert(url);
//setBarraEstado(url);
}
function estatus_boton()
{	  
 	jQuery("#list_estatus").setGridParam({url:"modulos/tesoreria/cheques/pr/sql.estatus.php?tesoreria_busqueda_usuario_estatus="+getObj('tesoreria_estatus_banco_nombre_usuario').value+"&tesoreria_busqueda_banco_estatus="+getObj('tesoreria_estatus_banco_nombre_banco').value+"&tesoreria_busqueda_cuenta_estatus="+getObj('tesoreria_estatus_banco_numero_cuenta').value+"&tesoreria_busqueda_proveedor_estatus="+getObj('tesoreria_estatus_banco_nombre_proveedor').value+"&tesoreria_busqueda_beneficiario_estatus="+getObj('tesoreria_estatus_banco_busqueda_beneficiario').value+"&combo="+getObj('tesoreria_estatus_db_unidad').value,page:1}).trigger("reloadGrid");
	url="modulos/tesoreria/cheques/pr/sql.estatus.php?tesoreria_busqueda_usuario_estatus="+getObj('tesoreria_estatus_banco_nombre_usuario').value+"&tesoreria_busqueda_banco_estatus="+getObj('tesoreria_estatus_banco_nombre_banco').value+"&tesoreria_busqueda_cuenta_estatus="+getObj('tesoreria_estatus_banco_numero_cuenta').value+"&tesoreria_busqueda_proveedor_estatus="+getObj('tesoreria_estatus_banco_nombre_proveedor').value+"&tesoreria_busqueda_beneficiario_estatus="+getObj('tesoreria_estatus_banco_busqueda_beneficiario').value+"&combo="+getObj('tesoreria_estatus_db_unidad').value;
// 	alert(url);
setBarraEstado('');
}


$('#tesoreria_estatus_banco_nombre_usuario').numeric({});
$('#tesoreria_estatus_banco_nombre_banco').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄.,-'});
$('#tesoreria_estatus_banco_nombre_proveedor').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄.,-'});
$('#tesoreria_estatus_banco_busqueda_beneficiario').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_estatus_banco_numero_cuenta').numeric({allow:'-'});
//$('#tesoreria_numero_cheque_estatus').numeric({allow:'-'});
//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'¥'});
$("#tesoreria_estatus_btn_cancelar").click(function() {

	setBarraEstado("");
	getObj('tesoreria_estatus_banco_nombre_usuario').value="";
	getObj('tesoreria_estatus_banco_nombre_banco').value="";
	getObj('tesoreria_estatus_banco_numero_cuenta').value="";
	getObj('tesoreria_estatus_banco_nombre_proveedor').value="";
	getObj('tesoreria_estatus_banco_busqueda_beneficiario').value="";
	getObj('tesoreria_estatus_db_unidad').value=1;
	estatus_combo();
										
});
$("#tesoreria_cheque_estatus_imp").click(function() {
url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.generico_cheques_emitidos2.phpønumeros_cheques="+getObj('tesoreria_estatus_db_id_cheque_oculto').value+"@combo="+getObj('tesoreria_estatus_db_unidad').value; 
								//	alert(url);
									openTab("Cheques",url);

	
});

function asigna_valor()
{
	if((getObj('tesoreria_nombre_unidad').value=='11')||(getObj('tesoreria_nombre_unidad').value=='4')||(getObj('tesoreria_nombre_unidad').value=='2'))
	{
		getObj('tesoreria_estatus_db_unidad').style.display='';
	}
}
</script>
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>

<div id="botonera">
<img id="tesoreria_estatus_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
  <img id="tesoreria_estatus_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
	  	<img id="tesoreria_cheque_estatus_imp"  class="btn_imprimir" src="imagenes/null.gif"  style="display:none" />

</div>
<form method="POST" id="form_tesoreria_estatus_db_cheques" name="form_tesoreria_estatus_db_cheques">
  <table class="cuerpo_formulario">
    <tr>
      <th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Seguimiento de Cheques 
      </th>
    </tr>
    <tr>
      <td class="celda_consulta"><div class="div_busqueda">&nbsp;Unidad:TESORERIA</div>
          <input type="hidden" name="tesoreria_nombre_unidad" id="tesoreria_nombre_unidad" value="11" onselect="" />
          <label>
          <select name="tesoreria_estatus_db_unidad"  id="tesoreria_estatus_db_unidad"  onchange="estatus_combo()"style="display:none">
          <option id="1" value='1'><?php echo($combo_opcion1); ?></option>
		  <option id="2" value='2'><?php echo($combo_opcion2); ?></option>
		  <option id="3" value='3'><?php echo($combo_opcion3); ?></option>
		  </select>
          </label>
		  <script type="text/JavaScript">
	    	asigna_valor();
			</script>
          <table  class="cuerpo_formulario" width="658" border="0">
            <tr>
             <td class="celda_consulta">
			 <div class="div_busqueda">
			 <label id="" for="tesoreria_banco_nombre_usuario">N Cheque:</label> 
			 &nbsp; 
			 <input type="text" name="tesoreria_estatus_banco_nombre_usuario" id="tesoreria_estatus_banco_nombre_usuario"  maxlength="25" size="25" onKeyDown="" />
			 <label id="" for="tesoreria_estatus_banco_nombre_banco">Banco:</label> &nbsp; <input type="text" name="tesoreria_estatus_banco_nombre_banco" id="tesoreria_estatus_banco_nombre_banco" maxlength="25" size="25"  />
			 <label id="" for="tesoreria_estatus_banco_numero_cuenta">Cuenta:</label> &nbsp; <input type="text" name="tesoreria_estatus_banco_numero_cuenta" id="tesoreria_estatus_banco_numero_cuenta" maxlength="20" size="20" " /><br/>
			 </div>
			 <div><br/>
			 <label id="" for="tesoreria_estatus_banco_nombre_proveedor">Proveedor:</label>
			 <input type="text" name="tesoreria_estatus_banco_nombre_proveedor" id="tesoreria_estatus_banco_nombre_proveedor"  maxlength="25" size="25""/>
			 <label id="" for="tesoreria_estatus_banco_busqueda_beneficiario">Benef :</label>
			<input type="text" name="tesoreria_estatus_banco_busqueda_beneficiario" id="tesoreria_estatus_banco_busqueda_beneficiario"  maxlength="25" size="25" />
			 		        <input name="button" type="button" id="estatus-busqueda_boton_filtro" value="Buscar"  onclick="estatus_boton()"/>			   
			 &nbsp;&nbsp;Fecha Proceso: <input name="fecha_proceso" type="text" id="fecha_proceso" value="<?php echo date("d-m-Y"); ?>" size="7" readonly="true" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			/>
          <button type="reset" id="fecha_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "fecha_proceso",      
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script></div>
			
          <table id="list_estatus" class="scroll" cellpadding="0" cellspacing="0" >
          </table>
        <div id="pager_estatus" class="scroll" style="text-align:center;"> </div>
        <br/>
          <input type="hidden" name="tesoreria_estatus_db_id_cheque_oculto" id="tesoreria_estatus_db_id_cheque_oculto" /></td>
    </tr>
  </table>
</form>
