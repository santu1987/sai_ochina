<script type="text/javascript">
$("#tesoreria_archivados_db_btn_consultar_banco_chequeras").click(function() {
		
		
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/pr/grid_banco_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Bancos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_cheques_busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_cheques_busqueda_bancos").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_cheque_banc_dosearch();
					});
				
						function consulta_cheque_banc_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_cheque_banc_gridReload,500)
										}
						function consulta_cheque_banc_gridReload()
						{
							var busq_banco= jQuery("#tesoreria_cheques_busqueda_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/sql_grid_banco.php?busq_banco="+busq_banco;
						}

			}
		});					

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/pr/sql_grid_banco.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo Área','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:130,sortable:false,resizable:false},
									{name:'sucursal' ,index:'sucursal', width:130,sortable:false,resizable:false,hidden:true},
									{name:'direccion',index:'direccion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigoarea',index:'codigoarea', width:50,sortable:false,resizable:false,hidden:true},
									{name:'telefono',index:'telefono', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fax',index:'fax',width:50,sortable:false,resizable:false,hidden:true},
									{name:'persona_contacto',index:'persona_contacto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cargo_contacto',index:'cargo_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'email_contacto',index:'email_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'pagina_banco',index:'pagina_banco', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true },
									{name:'cuentas',index:'cuentas', width:100,sortable:false,resizable:false,hidden:true}
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_archivados_db_banco_id_banco').value=ret.id;
									getObj('tesoreria_archivados_db_nombre_banco').value=ret.nombre;
									getObj('tesoreria_archivados_db_n_cuenta').value=ret.cuentas;
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
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
		}				
});

$("#tesoreria_archivados_rp_btn_cancelar").click(function() {
	clearForm("form_tesoreria_archivados_rp_documentos");
	getObj('tesoreria_archivados_rp_fecha_desde').value="<?=  date("d/m/Y"); ?>";

});
$("#tesoreria_archivados_db_doc_btn_imprimir").click(function() {
	url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_archivados.php¿desde="+getObj('tesoreria_archivados_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_archivados_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_archivados_db_banco_id_banco').value+"@n_cuenta="+getObj('tesoreria_archivados_db_n_cuenta').value
				openTab("Cheques/Archivados",url);
			//	alert(url);

});



$("#tesoreria_archivados_db_btn_consultar_cuentas_chequeras").click(function() {
if((getObj('tesoreria_archivados_db_banco_id_banco').value!=""))
{
	urls='modulos/tesoreria/cheques/pr/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_archivados_db_banco_id_banco').value;
//alert(urls);
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuentas Activas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
								//'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','N Cuenta','Estatus'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:50,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_archivados_db_n_cuenta').value=ret.ncuenta;
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
								sortname: 'ncuenta',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});

</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
</script>

   <div id="botonera"><img id="tesoreria_archivados_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  /><img id="tesoreria_archivados_db_doc_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />	</div>
	
	</div>
<form method="post" id="form_tesoreria_archivados_rp_documentos" name="form_tesoreria_archivados_db_docuemntos">
<input type="hidden"  id="tesoreria_archivados_vista_documentos" name="tesoreria_archivados_vista_documentos"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Listado de Cheuques Archivados </th>
	</tr>
	    <th>Desde :</th>
	      <td><label>
	      <input readonly="true" type="text" name="tesoreria_archivados_rp_fecha_desde" id="tesoreria_archivados_rp_fecha_desde" size="7" value="<? echo date("d/m/Y") ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="tesoreria_archivados_rp_fecha_desde_oculto" id="tesoreria_archivados_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
	      <button type="reset" id="tesoreria_archivados_rp_fecha_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_archivados_rp_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_archivados_rp_fecha_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						
					});
			</script>
	      </label></td>
		  </tr>
	<tr>
	<th width="167">Hasta:</th>
	<td><input readonly="true" type="text" name="tesoreria_cheques_archivados_rp_fecha_hasta" id="tesoreria_cheques_archivados_rp_fecha_hasta" size="7" value="<?   $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="tesoreria_cheques_archivados_rp_fecha_hasta_oculto" id="tesoreria_cheques_archivados_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
	      <button type="reset" id="tesoreria_cheques_archivados_rp_fecha_boton_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_cheques_archivados_rp_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_cheques_archivados_rp_fecha_boton_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("tesoreria_cheques_archivados_rp_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("tesoreria_cheques_archivados_rp_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("tesoreria_cheques_archivados_rp_fecha_hasta").value =getObj("tesoreria_cheques_archivados_rp_fecha_hasta_oculto").value;
									}
							}
					});
			</script></td>		
	      	
			
	  </tr>	  
	<tr>	  
	<th>Banco:</th>
	 	    <td>
		 <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_archivados_db_nombre_banco" type="text" id="tesoreria_archivados_db_nombre_banco"   value="" size="50" maxlength="80" message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
						jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ-.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    	<input type="hidden"  id="tesoreria_archivados_db_banco_id_banco" name="tesoreria_archivados_db_banco_id_banco"/>
		</li>
		<li id="tesoreria_archivados_db_btn_consultar_banco_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	</tr>
   <tr>
		<th>N&ordm; Cuenta: </th>	
	    <td>	
		<ul class="input_con_emergente">
		<li>
				<input name="tesoreria_archivados_db_n_cuenta" type="text" id="tesoreria_archivados_db_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el Número de cuenta. " readonly=""
				jVal="{valid:/^[0123456789]{1,20}$/, message:'N&uacute;mero de cuenta Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
		</li>
		<li id="tesoreria_archivados_db_btn_consultar_cuentas_chequeras" class="btn_consulta_emergente">
		</li>
		</ul>		</td>
	

	
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table> 
  <input  name="tesoreria_archivados_rp_id" type="hidden" id="tesoreria_archivados_rp_id"  />
</form>

   
