<script language="javascript">
$("#contabilidad_auxiliar_rp_btn_usuario_consulta").click(function() {
////
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/rp/grid_usuario_banco_cuentas.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tesoreria-reportes-busq_nombre_usuario").val(); 
					var busq_usuario= jQuery("#tesoreria-reportes-busq_nombre_usuario2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria-reportes-busq_nombre_usuario").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_reportes_dosearch();
												
					});
				$("#tesoreria-reportes-busq_nombre_usuario2").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_reportes_dosearch();
												
					});
					function tesoreria_usuario_reportes_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(tesoreria_usuario_reportes_gridReload,500)
										}
						function tesoreria_usuario_reportes_gridReload()
						{
							var busq_nombre= jQuery("#tesoreria-reportes-busq_nombre_usuario").val(); 
							var busq_usuario= jQuery("#tesoreria-reportes-busq_nombre_usuario2").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			
						}
			}
		});
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/rp/sql_grid_usuario.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Usuario','Unidad'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'unidad',index:'unidad', width:200,sortable:false,resizable:false},
								
												],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('contabilidad_auxiliar_rp_id_usuario').value = ret.id;
									getObj('contabilidad_auxiliar_rp_usuario').value = ret.nombre;
								
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
								sortname: 'id_proyecto',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	
});

$("#contabilidad_auxiliar_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_contabilidad_rp_auxiliares');
	getObj('balance_saldos_aux_rp_fecha_desde').value="<?=  date("d/m/Y"); ?>";
});
$("#contabilidad_auxiliar_rp_btn_imprimir").click(function() {
	opcion=1;
	url="pdf.php?p=modulos/contabilidad/auxiliares/rp/vista.lst.auxiliares_usuarios_saldos.php?id_usuario="+getObj('contabilidad_auxiliar_rp_id_usuario').value+"@cuenta="+getObj('contabilidad_auxiliares_rp_id_cuenta').value+"@fecha="+getObj('balance_saldos_aux_rp_fecha_desde').value+"@opcion="+opcion+"@aux="+getObj('contabilidad_auxiliares_id').value;
	//|alert(url);
	openTab("Balance De Aux",url);
	
	//alert(url);
	//	openTab("Balance",url);
		//
		/*$fecha=getObj('balance_saldos_aux_rp_fecha_desde').value;
		url="pdf.php?p=modulos/contabilidad/balance/rp/vista.lst.balance_saldos_r.php?fecha="+$fecha;
		openTab("Balance Saldos",url);*/
		
});		
$("#contabilidad_vista_btn_consultar_auxiliar_rp").click(function() {
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
///
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
									$('#contabilidad_auxiliar_rp_cuenta_contable').val(ret.cuenta_contable);
									getObj('contabilidad_auxiliares_rp_id_cuenta').value=ret.id;
									getObj('contabilidad_auxiliares_rp_desc').value=ret.nombre;
					
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
$("#contabilidad_vista_btn_consultar_auxiliar_rp_aux").click(function() {
if(getObj('contabilidad_auxiliares_rp_id_cuenta').value!=="")
{	
				var nd=new Date().getTime();
				setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
				$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
									function(data)
									{								
											dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
											setTimeout(crear_grid,100);
									});
									function crear_grid()
									{
										jQuery("#list_grid_"+nd).jqGrid
										({
											width:800,
											height:300,
											recordtext:"Registro(s)",
											loadtext: "Recuperando Información del Servidor",		
											url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?cuenta='+getObj('contabilidad_auxiliares_rp_id_cuenta').value,							
											datatype: "json",
											colNames:['id','c&oacute;digo','Denominaci&oacute;n'],
											colModel:[
												{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
												{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
												{name:'denominacion',index:'denominacion', width:50,sortable:false,resizable:false},

													],
											pager: $('#pager_grid_'+nd),
											rowNum:20,
											rowList:[20,50,100],
											imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
											onSelectRow: function(id){
											var ret = jQuery("#list_grid_"+nd).getRowData(id);
												$('#contabilidad_auxiliares_rp_aux').val(ret.cuenta_contable);
												$('#contabilidad_auxiliares_id').val(ret.id);
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
}
});
function cuenta_contable_cod_rp()
{//alert("entro");
$.ajax({
			url:"modulos/contabilidad/auxiliares/rp/sql_contabilidad_auxiliar_rp_cuenta_contable.php",
            data:dataForm('form_contabilidad_rp_auxiliares'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
					recordset = recordset.split("*");
					getObj('contabilidad_auxiliares_rp_id_cuenta').value=recordset[0];
					getObj('contabilidad_auxiliar_rp_cuenta_contable').value=recordset[1];
					getObj('contabilidad_auxiliares_rp_desc').value=recordset[2];
				}
				else
				{
					getObj('contabilidad_auxiliar_rp_cuenta_contable').value="";
				}
			 }
		});		
}

function auxiliares_consulta_mov_aux()
{
		url="modulos/contabilidad/auxiliares/rp/sql_grid_auxi.php";
		//alert(url);

		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/auxiliares/rp/sql_grid_auxi.php",
						data:dataForm('form_contabilidad_rp_auxiliares'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								//getObj('').value = recordset[0];
								getObj('contabilidad_auxiliares_id').value=recordset[0];
								//getObj('contabilidad_auxiliar_db_cuenta_contable').value=recordset[2];
								/*getObj('contabilidad_auxiliares_db_nombre').value=recordset[4];
								getObj('contabilidad_auxiliares_db_comentario').value=recordset[5];
								getObj('contabilidad_auxiliares_db_btn_eliminar').style.display='';
								getObj('contabilidad_auxiliares_db_desc').value=recordset[6];*/
							}
							else
							if(recordset=='vacio')
							{	
								getObj('contabilidad_auxiliares_rp_aux').value='';
								getObj('contabilidad_auxiliares_id').value='';

							}
							
						 }
					});	 	
	
}
//////////////////////////////////
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>


<div id="botonera">
	<img id="contabilidad_auxiliar_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
	<img id="contabilidad_auxiliar_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
	</div>
<form method="post" id="form_contabilidad_rp_auxiliares" name="form_contabilidad_rp_auxiliares">
<input type="hidden"  id="contabilidad_vista_auxiliares" name="contabilidad_vista_auxiliares"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Auxiliares</th>
	</tr>
		<tr>
			<th width="118">
				  Hasta:			</th>
		<td width="124">
		            <input readonly="true" type="text" name="balance_saldos_aux_rp_fecha_desde" id="balance_saldos_aux_rp_fecha_desde" size="7" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="balance_saldos_aux_rp_fecha_desde_oculto" id="balance_saldos_aux__rp_fecha_desde_oculto" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>"/>
				  <button type="reset" id="balance_saldos_aux_rp_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "balance_saldos_aux_rp_fecha_desde",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "balance_saldos_aux_rp_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("balance_saldos_aux_rp_fecha_desde").value.MMDDAAAA() );
										//f2=new Date( getObj("balance_saldos_aux__rp_fecha_hasta").value.MMDDAAAA() );
										
									}
							});
					</script>		</td>
			
	  </tr>	
    <tr>
		<th>Cuenta Contable:</th>
		 <td>
		 <ul class="input_con_emergente">
		 <li>
		    	<input type="text" name="contabilidad_auxiliar_rp_cuenta_contable" id="contabilidad_auxiliar_rp_cuenta_contable"  size='12' maxlength="12"
				message="Introduzca la cuenta contable"  onblur="cuenta_contable_cod_rp()"
				/>
		       <input type="text" id="contabilidad_auxiliares_rp_desc"  name="contabilidad_auxiliares_rp_desc" readonly="readonly">
                <input type="hidden" id="contabilidad_auxiliares_rp_id_cuenta" name="contabilidad_auxiliares_rp_id_cuenta" />
		 </li>
		<li id="contabilidad_vista_btn_consultar_auxiliar_rp" class="btn_consulta_emergente"></li>
	    </ul>	  </td>	
    </tr> 
	<tr>
		<th>Auxiliares:</th>
		<td>
		<ul class="input_con_emergente">
		 <li>
				<input type="text" id="contabilidad_auxiliares_rp_aux" name="contabilidad_auxiliares_rp_aux" onblur="auxiliares_consulta_mov_aux()" />
				<input type="hidden" id="contabilidad_auxiliares_id" name="contabilidad_auxiliares_id" />
		 </li>
		<li id="contabilidad_vista_btn_consultar_auxiliar_rp_aux" class="btn_consulta_emergente"></li>
	    </ul>	
		</td>
	</tr>   
	 <tr style="display:none">
      <th>Usuario</th>
      <td><ul class="input_con_emergente">
          <li>
            <input name="contabilidad_auxiliar_rp_usuario" type="text" id="contabilidad_auxiliar_rp_usuario"    size="50" maxlength="80" message="Seleccione el Nombre de un usuario" readonly 
			 />
            <input type="hidden" id="contabilidad_auxiliar_rp_id_usuario" name="contabilidad_auxiliar_rp_id_usuario"/>
          </li>
        <li id="contabilidad_auxiliar_rp_btn_usuario_consulta" class="btn_consulta_emergente"></li>
      </ul></td>
    </tr>
	 <tr style="display:none">
	  <th>
	  		Tipo:
	  </th>
	  <td>
	  		<select name="balance_auxiliares_opcion_tipo" id="balance_auxiliares_opcion_tipo">
					<option id="0" value="0">------SELECCIONE ------</option>
					<option id="1" value="1">Saldos Iniciales</option>
					<option id="2" value="2"> Movimientos </option>
			</select>
	  </td>
	  </tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>
<input   type="hidden" name="contabilidad_auxiliar_rp_usuario_consulta"  id="contabilidad_auxiliar_rp_usuario_consulta" />
</form>