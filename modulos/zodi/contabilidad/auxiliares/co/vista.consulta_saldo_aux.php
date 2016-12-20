<?
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
<script language="javascript">
$("#saldo_aux_co_consultar_cuenta").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_cuenta_contable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-cuenta-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload2,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-cuenta-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom;	
					//alert(url);
				}
				//
				$("#consulta-cuenta-contable-busqueda-nombre").keypress(
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
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta;
                 // ¿ alert(url);				
				}
			}
		}
	);
///						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Cuenta', 'Denominacion','Tipo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#saldo_aux_cuenta').val(ret.cuenta_contable);
									getObj('saldo_aux_cuenta_id').value=ret.id;
								//	getObj('contabilidad_auxiliares_db_desc').value=ret.nombre;
					
//									$('#contabilidad_auxiliares_db_id_cuenta').val(ret.id);
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
						}
});
///////////////////////
$("#contabilidad_saldos_auxiliares_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	
				$.ajax ({
					url:"modulos/contabilidad/auxiliares/co/grid_auxiliares.php",
					data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
					type:'POST', 
					cache: false,
					success: function(data)
					{
						dialog=new Boxy(data,{ title: 'Consulta Emergente de auxiliares', modal: true,center:false,x:0,y:0,show:false});
						dialog_reload=function gridReload(){ 
							var busq_nombre= jQuery("#contabilidad_auxiliares_saldos_nombre_consulta").val(); 
							var busq_cod= jQuery("#contabilidad_auxiliares_saldos_cod").val(); 

							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/co/sql_contabilidad_auxiliares_cod_cuenta.php?busq_nombre="+busq_nombre+"&fecha_desde="+getObj('saldo_auxi_desde').value+"&fecha_hasta="+getObj('saldo_auxi_hasta').value+"&cod_cuenta"+busq_cod,page:1}).trigger("reloadGrid"); 
					}	
						crear_grid();
						var timeoutHnd; 
						var flAuto = true;
						$("#contabilidad_auxiliares_saldos_nombre_consulta").keypress(function(key)
						{
								auxiliares_saldos_dosearch();
		
							});
							$("#contabilidad_auxiliares_saldos_cod").keypress(function(key)
						{
								auxiliares_saldos_dosearch();
		
							});
							function auxiliares_saldos_dosearch()
							{
								if(!flAuto) return; 
									// var elem = ev.target||ev.srcElement; 
								if(timeoutHnd) 
									clearTimeout(timeoutHnd) 
									timeoutHnd = setTimeout(auxiliares_saldos_gridReload,500)
												}
								function auxiliares_saldos_gridReload()
								{
									var busq_nombre= jQuery("#contabilidad_auxiliares_saldos_nombre_consulta").val(); 
									var busq_cod= jQuery("#contabilidad_auxiliares_saldos_cod").val(); 

									jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/co/sql_contabilidad_auxiliares_cod_cuenta.php?busq_nombre="+busq_nombre+"&fecha_desde="+getObj('saldo_auxi_desde').value+"&fecha_hasta="+getObj('saldo_auxi_hasta').value+"&busq_cod="+busq_cod,page:1}).trigger("reloadGrid"); 
									url="modulos/contabilidad/auxiliares/co/sql_contabilidad_auxiliares_cod_cuenta.php?busq_nombre="+busq_nombre+"&fecha_desde="+getObj('saldo_auxi_desde').value+"&fecha_hasta="+getObj('saldo_auxi_hasta').value+"&busq_cod="+busq_cod;
								//alert(url);
								}
					}
				});
				
		
				
				function crear_grid()
								{
									
									jQuery("#list_grid_"+nd).jqGrid
									({
										width:500,
										height:300,
										recordtext:"Registro(s)",
										loadtext: "Recuperando Información del Servidor",		
										url:'modulos/contabilidad/auxiliares/co/sql_contabilidad_auxiliares_cod_cuenta.php?nd='+nd+"&fecha_desde="+getObj('saldo_auxi_desde').value+"&fecha_hasta="+getObj('saldo_auxi_hasta').value,
										datatype: "json",
										colNames:['ID','Cuenta Aux','Auxiliar','Cuenta Contable','desc','debe','haber','saldo_ant','saldo_mov','saldo_actual','mes','mes_ant'],
										colModel:[
											{name:'id_aux',index:'id_aux', width:50,sortable:false,resizable:false,hidden:true},
																		
											{name:'cuenta_auxiliares',index:'cuenta_auxiliares', width:50,sortable:false,resizable:false},
											{name:'nombre',index:'nombre', width:50,sortable:false,resizable:false},											
											{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
											{name:'desc',index:'desc', width:50,sortable:false,resizable:false},

											{name:'debe',index:'debe', width:50,sortable:false,resizable:false,hidden:true},
											{name:'haber',index:'haber', width:50,sortable:false,resizable:false,hidden:true},
											{name:'saldo_ant',index:'saldo_ant', width:50,sortable:false,resizable:false,hidden:true},
											{name:'saldo_mov',index:'saldo_mov', width:50,sortable:false,resizable:false,hidden:true},
											{name:'saldo_actual',index:'saldo_actual', width:50,sortable:false,resizable:false,hidden:true},
											{name:'mes',index:'saldo_actual', width:50,sortable:false,resizable:false,hidden:true},
											{name:'mes_ant',index:'saldo_actual', width:50,sortable:false,resizable:false,hidden:true}
											
										],
										pager: $('#pager_grid_'+nd),
										rowNum:20,
										rowList:[20,50,100],
										imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
										onSelectRow: function(id){
											var ret = jQuery("#list_grid_"+nd).getRowData(id);
											getObj('saldo_aux_cuenta_aux_id').value = ret.id_aux;
											getObj('saldo_aux_cuenta_aux').value=ret.cuenta_auxiliares;
											getObj('saldo_ant_nombre_cuenta').value=ret.cuenta_contable;
											getObj('saldo_ant_desc_cuenta').value=ret.desc;
											getObj('saldo_ux_anterior').value=ret.saldo_ant;
											getObj('saldo_aux_debe').value=ret.debe;
											getObj('saldo_aux_haber').value=ret.haber;
											getObj('saldo_aux_mov').value=ret.saldo_mov;
											getObj('saldo_aux_actual').value=ret.saldo_actual;
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
										sortname: 'id_aux',
										viewrecords: true,
										sortorder: "asc"
								
					});	
		}
	});
	
	$("#contabilidad_saldos_auxiliar_db_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_saldos_aux');
	getObj('saldo_auxi_desde').value="<?=  $fecha; ?>";
	getObj('saldo_auxi_hasta').value="<?=  date("d/m/Y"); ?>";
	getObj('saldo_ux_anterior').value='0,00';
	getObj('saldo_aux_debe').value='0,00';
	getObj('saldo_aux_mov').value='0,00';
	getObj('saldo_aux_actual').value='0,00';
	getObj('saldo_aux_haber').value='0,00';
	
	
});
	///
	/////////////////////////////////////////////////////
function consulta_auxiliar_saldo_manual()
{
	$.ajax({
			url:"modulos/contabilidad/auxiliares/co/sql_aux_consulta_cod.php",
            data:dataForm('form_saldos_aux'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
			alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
				colNames:['ID','Cuenta Aux','Auxiliar','Cuenta Contable','desc','debe','haber','saldo_ant','saldo_mov','saldo_actual','mes','mes_ant'],
					getObj('saldo_aux_cuenta_aux_id').value = recordset[0];
					getObj('saldo_aux_cuenta_aux').value=recordset[1];
					getObj('saldo_ant_nombre_cuenta').value=recordset[3];
					getObj('saldo_ant_desc_cuenta').value=recordset[4];
					getObj('saldo_ux_anterior').value=recordset[11];
					getObj('saldo_aux_debe').value=recordset[5];
					getObj('saldo_aux_haber').value=recordset[6];
					getObj('saldo_aux_mov').value=recordset[7];
					getObj('saldo_aux_actual').value=recordset[8];

				}
				else
				if(recordset=='vacio')
				{	
						setBarraEstado("");
						clearForm('form_saldos_aux');
						getObj('saldo_auxi_desde').value="<?=  $fecha; ?>";
						getObj('saldo_auxi_hasta').value="<?=  date("d/m/Y"); ?>";
						getObj('saldo_ux_anterior').value='0,00';
						getObj('saldo_aux_debe').value='0,00';
						getObj('saldo_aux_mov').value='0,00';
						getObj('saldo_aux_actual').value='0,00';
						getObj('saldo_aux_haber').value='0,00';
				}
				
			 }
		});	 	 
}
/////////////////////////////////////////////////////
function consulta_cta_contable_manual()
{
//alert("entro");
	$.ajax({
			url:"/modulos/contabilidad/auxiliares/co/sql.grid_cuentas_cont.php",
            data:dataForm('form_saldos_aux'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
								
				getObj('saldo_ant_desc_cuenta').value = recordset[0];
				}
				else
				{
				getObj('saldo_ant_nombre_cuenta').value = "";
				getObj('saldo_ant_desc_cuenta').value="";
				//getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value="";
				}
				
			 }
		});	 	 
}
///
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#saldo_aux_cuenta_aux').numeric({});
//////////////////////
</script>
<div id="botonera">
	<img id="contabilidad_saldos_auxiliar_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
	<img id="contabilidad_saldos_auxiliares_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
</div>
<form id="form_saldos_aux" name="form_saldos_aux"  method="post">
 <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Saldos Auxiliares</th>
	</tr>
   <tr>
     <th colspan="4" align="center"><label  ><strong>Fecha</strong>:</label></th></tr>
    <tr>  
    <th>Desde :</th>
	      <td><label>
	      <input readonly="true" type="text" name="saldo_auxi_desde" id="saldo_auxi_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="saldo_auxi_desde_oculto" id="saldo_auxi_desde_oculto" value="<? echo $fecha ?>"/>
	      <button type="reset" id="saldo_auxi_fecha_btn_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "saldo_auxi_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "saldo_auxi_fecha_btn_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("saldo_auxi_desde").value.MMDDAAAA());
								f2=new Date( getObj("saldo_auxi_hasta").value.MMDDAAAA());
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
									getObj("saldo_auxi_desde").value =getObj("saldo_auxi_desde_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
		
	
	  <th>Hasta :</th>
	      <td><label>
	      <input readonly="true" type="text" name="saldo_auxi_hasta" id="saldo_auxi_hasta" size="7" value="<?   $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="saldo_auxi_hasta_oculto" id="saldo_auxi_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
	      <button type="reset" id="saldo_auxi_hasta_btn_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "saldo_auxi_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "saldo_auxi_hasta_btn_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("saldo_auxi_desde").value.MMDDAAAA() );
								f2=new Date( getObj("saldo_auxi_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
									getObj("saldo_auxi_hasta").value =getObj("saldo_auxi_hasta_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
     </tr> 
	 <tr>
    <th>Cuenta:</th>
        <td colspan="3"><input type="text" name="saldo_ant_nombre_cuenta" id="saldo_ant_nombre_cuenta" onblur="consulta_cta_contable_manual()"  />
        <input type="hidden" name="saldo_ant_desc_cuenta" id="saldo_ant_desc_cuenta" readonly="readonly" /></td>
        </tr>    
      <tr	 >
    	<th>Auxiliar:</th>
        <td colspan="3">
        
        	<input type="text" name="saldo_aux_cuenta_aux" id="saldo_aux_cuenta_aux" onblur="consulta_auxiliar_saldo_manual()"   />
            <input type="hidden" name="saldo_aux_cuenta_aux_id" id="saldo_aux_cuenta_aux_id" />
      
       </td>
    </tr>
    
    <tr>
   		<th>Saldo Anterior:</th>
        
        <td colspan="3"><input type="text" id="saldo_ux_anterior" name="saldo_ux_anterior" value="0,00" readonly="readonly"/></td>
    </tr>
    <tr>
        <th>Debe:</th>
        <td><input type="text" name="saldo_aux_debe" id="saldo_aux_debe"  value="0,00" readonly="readonly"/></td>
        <th>Haber:</th>
        <td><input type="text" name="saldo_aux_haber" id="saldo_aux_haber"  value="0,00" readonly="readonly"/></td>
    </tr>
    <tr>
    	<th>Saldo Movimientos:</th>
                <td colspan="3"><input type="text" id="saldo_aux_mov" name="saldo_aux_mov"  value="0,00" readonly="readonly"/></td>
    </tr>
    <tr>
    	<th>Saldo Actual:</th>
                <td colspan="3"><input type="text" id="saldo_aux_actual" name="saldo_aux_mactual" value="0,00" readonly="readonly"/></td>
    </tr>
 <tr>
        <td height="22" colspan="4" class="bottom_frame">&nbsp;</td>
   </tr>
</table>
</form>
