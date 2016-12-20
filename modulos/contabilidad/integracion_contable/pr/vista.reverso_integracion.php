<? session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql="SELECT * FROM modulo";
$rs_modulo =& $conn->Execute($sql);
while (!$rs_modulo->EOF) {
	$opt_modulo.="<option value='".$rs_modulo->fields("id")."' >".$rs_modulo->fields("nombre")."</option>";
	$rs_modulo->MoveNext();
}
if(date("d")=="31")
{
	$dia=date("d")-1;
	$mes=date("m")-1;
	$ayo=date("Y");
}
	else
	{
		$dia=date("d");	
	}
if(date("m")=="1")
{
	$mes="12";
	$ayo=date("Y")-1;
}
else
	{
	$mes=date("m")-1;
	$ayo=date("Y");
	}
$fecha=date("d/m/Y",mktime(0,0,0,$mes,$dia,$ayo));
?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$("#contabilidad_integracion_reversar_pr_btn_reversar").click(function (){
if(($('#form_contabilidad_rp_integracion_contable_reverso').jVal())&&(getObj('contabilidad_integracion_reverso_mod').value!='0'))
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax(
		{
			url:"modulos/contabilidad/integracion_contable/pr/sql.reverso_integracion.php",
			data:dataForm('form_contabilidad_rp_integracion_contable_reverso'),
			type:'POST',
			cache: false,
			success:function(html)
			{
			
			alert(html);
			//setBarraEstado(html);
			recordset=html;
			recordset = recordset.split("*");
				if (recordset[0]=="Registrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REVERSO CONCLUIDO. N&deg; COMPROBANTE INTEGRACI&Oacute;N :"+recordset[1]+"</p></div>",true,true);
					limpiar_reverso();
				}
				else if (recordset[0]=="no_reverso")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REVERSO FALLIDO</p></div>",true,true);
					limpiar_reverso();
					
				}
				else if (recordset[0]=="no_cheque")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REVERSO FALLIDO:Cambiar modulo</p></div>",true,true);
					limpiar_reverso();
					
				}
				else if (recordset[0]=="no_modulo")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REVERSO FALLIDO:Modulo origen incorrecto</p></div>",true,true);
					limpiar_reverso();
					
				}
					/*else
				{
					alert(html);
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				
//
				}*/
			
			}
		});
	}
	});
	
	
function consulta_manual_mov_contables_reverso()
{
anos=getObj('contabilidad_reverso_int_pr_ayo').value;
	$.ajax({
			url:"modulos/contabilidad/integracion_contable/pr/sql_grid_mov_cod_int.php",
            data:dataForm('form_contabilidad_rp_integracion_contable_reverso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				recordset = recordset.split("*");
				//alert(recordset);
					if(html!="vacio")
					{
						$('#contabilidad_integracion_reverso_numero_c_desde2').val(html);
						ncomp2=html;
					
					}else
					if(html=="vacio")
					{
						getObj('contabilidad_integracion_reverso_numero_c_desde2').value="";
						getObj('contabilidad_integracion_reverso_numero_c_desde').value="";
					}
			 }
		});	 	 
}
function limpiar_reverso()
{
	setBarraEstado("");
	clearForm('form_contabilidad_rp_integracion_contable_reverso');
	//getObj('contabilidad_integracion_reverso_mod').value=0;
	getObj('contabilidad_integracion_reverso_mod').value='0';
	getObj('contabilidad_reverso_int_pr_ayo').value=<?= date("Y")?>;
}
$("#contabilidad_integracion_reverso_rp_cancelar").click(function() {
limpiar_reverso();

});
$("#contabilidad_integracion_reverso_btn_consultar_cuenta").click(function() {

var nd=new Date().getTime();
anos=getObj('contabilidad_reverso_int_pr_ayo').value;
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_comprobante2.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Comprobantes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta_comprobante2").val(); 
					var ano=$("#consulta_ano_comp2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/integracion_contable/pr/sql_grid_numero_comprobante_reverso.php?busq_cuenta="+busq_cuenta+"&ano="+ano,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/integracion_contable/pr/sql_grid_numero_comprobante_reverso.php?busq_cuenta="+busq_cuenta+"&ano="+ano;
			//alert(url);
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#consulta_ano_comp2").change(
					function()
					{
						dosearch();													
					}											
				);
				$("#consulta_comprobante2").keypress(
					function(key)
					{
						dosearch();													
					}
				);
				$("#consulta_comprobante_tipo2").keypress(
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
					//alert("entro");
					var busq_cuenta= $("#consulta_comprobante2").val();
					var ano=$("#contabilidad_reverso_int_pr_ayo").val();
					var tipo=$("#consulta_comprobante_tipo2").val();
					//getObj('contabilidad_comp_pr_fecha_boton_d').disabled="true";
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/integracion_contable/pr/sql_grid_numero_comprobante_reverso.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/integracion_contable/pr/sql_grid_numero_comprobante_reverso.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
                	//alert(url);				
				}
			}
		}
	);

	function crear_grid()
	{
		jQuery("#list_grid_"+nd).jqGrid
		({
			width:350,
			height:300,
			recordtext:"Registro(s)",
			loadtext: "Recuperando Información del Servidor",		
			url:'modulos/contabilidad/integracion_contable/pr/sql_grid_numero_comprobante_reverso.php?nd='+nd+"&ano="+anos,
			datatype: "json",
			colNames:['Id','N Comprobante ',"",'Fecha'],
			colModel:[
				{name:'id',index:'id', width:200,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false},
				{name:'numero_comprobante2',index:'numero_comprobante2', width:120,sortable:false,resizable:false,hidden:true},
				{name:'fecha',index:'fecha', width:200,sortable:false,resizable:false}

			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#contabilidad_integracion_reverso_numero_id').val(ret.id);
				$('#contabilidad_integracion_reverso_numero_c_desde').val(ret.numero_comprobante);
				$('#contabilidad_integracion_reverso_numero_c_desde2').val(ret.numero_comprobante2);
	
					
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
	}//

});

$('#contabilidad_integracion_reverso_numero_c_desde').numeric({allow:',.-'});

</script>
<div id="botonera">
<img id="contabilidad_integracion_reverso_rp_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
<img id="contabilidad_integracion_reversar_pr_btn_reversar" src="imagenes/iconos/reversar.png"  style="width:100px height:100px"/>

	</div>
<form method="post" id="form_contabilidad_rp_integracion_contable_reverso" name="form_contabilidad_rp_integracion_contable_reverso">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame"  colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Reverso a Integraci&oacute;n </th>
		</tr>
		<tr>
		<th colspan="4" align="center"><p align="center">Datos del Comprobante:</p>		</tr>
	<tr>
		<th>M&oacute;dulo origen de la Integraci&oacute;n:</th>
		<td><select  id="contabilidad_integracion_reverso_mod" name="contabilidad_integracion_reverso_mod" message="Seleccione un Modulo">
		<option value='0'>--SELECCIONE--</option>
			<?= $opt_modulo ?>
		</select></td>
	</tr>	
	<tr>
			<th>A&ntilde;o:</th>
			<td>
				<select  name="contabilidad_reverso_int_pr_ayo" id="contabilidad_reverso_int_pr_ayo">
					<?
					$anio_inicio=date("Y");
					$anio_fin=$anio_inicio+1;
					$anio_ant=$anio_inicio-1;
					$anio_inicio=$anio_ant;
					while($anio_inicio <= $anio_fin)
					{
					?>
					<option value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
					<?
						$anio_inicio++;
					}
					?>
		  </select>
			</td>
			</tr>
	<tr>
			<th width="118">
			    N &ordm; de Comprobante Contable:			 </th>
			  <td width="124"> 
			 <ul class="input_con_emergente">
			 <li>
			  	    <input  type="text" id="contabilidad_integracion_reverso_numero_c_desde"  name="contabilidad_integracion_reverso_numero_c_desde" size="20" maxlength="20"
					message="Introduzca un número de comprobante ejm:50042"
				jval="{valid:/^[,.-_123456789]{1,7}$/,message:'Número Comprobante Invalido', styleType:'cover'}"
				jvalkey="{valid:/^[,.-_123456789]{1,7}$/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
				onblur="consulta_manual_mov_contables_reverso();"  onchange="consulta_manual_mov_contables_reverso();"
					>
             <input type="hidden" id="contabilidad_integracion_reverso_numero_c_desde2" name="contabilidad_integracion_reverso_numero_c_desde2" />       
			 <input type="hidden" id="contabilidad_integracion_reverso_numero_id" name="contabilidad_integracion_reverso_numero_id" />
			 </li>
			<li id="contabilidad_integracion_reverso_btn_consultar_cuenta" class="btn_consulta_emergente"></li>
			</ul> 	  </td>
	
	</tr>	
		<tr>
			<td colspan="4" class="bottom_frame">&nbsp;</td>
		</tr>	
	</table>
</form>