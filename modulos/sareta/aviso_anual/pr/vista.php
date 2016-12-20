<?php 
$fechaAct =date("Y");
$fechas;
for($i=1994;$i<=$fechaAct;$i++){
	$fecha.="<option value=".$i.">".$i."</option>";
	}
?>
<script type='text/javascript'>
var dialog;
//------------------------------------------------------------------------------------------------

$("#sareta_aviso_anual_pr_btn_consultar_buque").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/aviso_anual/pr/grid_buque.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Buques', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/aviso_anual/pr/sql_grid_buque.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/aviso_anual/pr/sql_grid_buque.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/aviso_anual/pr/sql_grid_buque.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/aviso_anual/pr/sql_grid_buque.php?nd='+nd,
								datatype: "json",
								colNames:['id','Matricula','Call Sign','Nombre','nombre','id_bandera','Bandera','bandera',
								'R. Bruto','id_actividad','Actividad','actividad','id_clase','Clase','clase','Nac/Ext','Pago Anual',
								'id_ley','Ley','ley','Exonerado','com','ley_tarifa_buque'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'matricula',index:'matricula', width:220,sortable:false,resizable:false},
									{name:'call_sign',index:'call_sign', width:220,sortable:false,resizable:false},
							
									{name:'nombre1',index:'nombre1', width:220,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_bandera',index:'id_bandera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera1',index:'bandera1', width:220,sortable:false,resizable:false},
									{name:'bandera',index:'bandera', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'r_bruto',index:'r_bruto', width:220,sortable:false,resizable:false},
									
									{name:'id_actividad',index:'id_actividad', width:220,sortable:false,resizable:false,hidden:true},
									{name:'actividad1',index:'actividad1', width:220,sortable:false,resizable:false},
									{name:'actividad',index:'actividad', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_clase',index:'id_clase', width:220,sortable:false,resizable:false,hidden:true},
									{name:'clase1',index:'clase1', width:220,sortable:false,resizable:false},
									{name:'clase',index:'clase', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'nac',index:'nac', width:220,sortable:false,resizable:false},
									{name:'pago_anual',index:'pago_anual', width:220,sortable:false,resizable:false},
									
									{name:'id_ley',index:'id_ley', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ley1',index:'ley1', width:220,sortable:false,resizable:false},
									{name:'ley',index:'ley', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'exonerado',index:'exonerado', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ley_tarifa_buque',index:'ley_tarifa_buque', width:220,sortable:false,resizable:false,hidden:true}
								
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
										getObj('sareta_aviso_anual_id_buque').value = ret.id;
										getObj('sareta_aviso_anual_id_ley_buque').value = ret.id_ley;
										getObj('sareta_aviso_anual_id_bandera_buque').value = ret.id_bandera;
										getObj('sareta_aviso_anual_id_clase_buque').value = ret.id_clase;
										getObj('sareta_aviso_anual_id_actividad_buque').value = ret.id_actividad;
										getObj('sareta_aviso_anual_tarifa_buque').value = ret.ley_tarifa_buque;
										getObj('sareta_aviso_anual_pr_buque').value = ret.nombre;
										getObj('sareta_aviso_anual_pr_matricula').value = ret.matricula;
										getObj('sareta_aviso_anual_pr_call_sign').value = ret.call_sign;
										getObj('sareta_aviso_anual_pr_rb').value = ret.r_bruto;
										getObj('sareta_aviso_anual_pr_clase').value = ret.clase;
										getObj('sareta_aviso_anual_pr_actividad').value = ret.actividad;
										getObj('sareta_aviso_anual_pr_bandera').value = ret.bandera;
										getObj('sareta_aviso_anual_pr_rb').value = ret.r_bruto;
									
									
									
								dialog.hideAndUnload();
									$('#form_pr_aviso_anual').jVal();
									
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre").focus();
								$('#parametro_cxp_pr_nombre').alpha({allow:'0123456789- '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
	//------------------------------------------------------------------------------------------------

$("#sareta_aviso_anual_pr_btn_consultar").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/aviso_anual/pr/grid_aviso_anual.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Aviso Anual', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/aviso_anual/pr/sql_grid_aviso_anual.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/aviso_anual/pr/sql_grid_aviso_anual.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/aviso_anual/pr/sql_grid_aviso_anual.php?nombre="+busq_nombre;
							
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/aviso_anual/pr/sql_grid_aviso_anual.php?nd='+nd,
								datatype: "json",
								colNames:['id_buque','A&ntilde;o','Matricula','Call Sign','Buque','','id_clase_buque','Clase','','id_actividad_buque','Actividad','','id_bandera_buque','Bandera','','R. Bruto','id_ley','Tarifa','obs','F. Recalada'],
								colModel:[
									{name:'id_buque',index:'id_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ano',index:'ano', width:220,sortable:false,resizable:false},
									{name:'matricula',index:'matricula', width:220,sortable:false,resizable:false},
									{name:'call_sign',index:'call_sign', width:220,sortable:false,resizable:false},
									{name:'nombre1',index:'nombre1', width:220,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_Clase_buque',index:'id_Clase_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'Clase1',index:'Clase1', width:220,sortable:false,resizable:false},
									{name:'Clase',index:'Clase', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_actividad_buque',index:'id_actividad_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'actividad1',index:'actividad1', width:220,sortable:false,resizable:false,hidden:true},
									{name:'actividad',index:'actividad', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_bandera_buque',index:'id_bandera_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera1',index:'bandera1', width:220,sortable:false,resizable:false},
									{name:'bandera',index:'bandera', width:220,sortable:false,resizable:false,hidden:true},
								
									{name:'registro_bruto_buque',index:'registro_bruto_buque', width:220,sortable:false,resizable:false},
									{name:'id_ley',index:'id_ley', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'tarifa_buque',index:'tarifa_buque', width:220,sortable:false,resizable:false},
		
									{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true},
									{name:'fecha_recalada',index:'fecha_recalada', width:220,sortable:false,resizable:false}
					
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
										getObj('sareta_aviso_anual_id_buque').value = ret.id_buque;
										getObj('sareta_aviso_anual_id_ley_buque').value = ret.id_ley;
										getObj('sareta_aviso_anual_id_bandera_buque').value = ret.id_bandera_buque;
										getObj('sareta_aviso_anual_id_clase_buque').value = ret.id_Clase_buque;
										getObj('sareta_aviso_anual_id_actividad_buque').value = ret.id_actividad_buque;
										getObj('sareta_aviso_anual_tarifa_buque').value = ret.ley_tarifa_buque;
										getObj('sareta_aviso_anual_pr_buque').value = ret.nombre;
										getObj('sareta_aviso_anual_pr_matricula').value = ret.matricula;
										getObj('sareta_aviso_anual_pr_call_sign').value = ret.call_sign;
										
										getObj('sareta_aviso_anual_pr_clase').value = ret.Clase;
										getObj('sareta_aviso_anual_pr_actividad').value = ret.actividad;
										getObj('sareta_aviso_anual_pr_bandera').value = ret.bandera;
										getObj('sareta_aviso_anual_pr_rb').value = ret.registro_bruto_buque;
										getObj('sareta_aviso_anual_pr_vista_observacion').value = ret.obs;
										getObj('aviso_anual_pr_desde').value =ret.ano;
										getObj('aviso_anual_pr_hasta').value =ret.ano;
										getObj('sareta_aviso_anual_pr_btn_guardar').style.display='none';
								dialog.hideAndUnload();
									$('#form_pr_aviso_anual').jVal();
									
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre").focus();
								$('#parametro_cxp_pr_nombre').alpha({allow:'0123456789- '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
	

//------------------------------------------------------------------------------------------------
	
	
$("#sareta_aviso_anual_pr_btn_guardar").click(function() {
	if($('#form_pr_aviso_anual').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/aviso_anual/pr/sql.registrar.php",
			data:dataForm('form_pr_aviso_anual'),
			
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
									
				}
				else if (html=="ErrorDeFechas")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La A&ntilde;o Hasta no Puede Ser Menor a la A&ntilde;o Desde…</p></div>",true,true);
				}
				else
				{
					
				
					setBarraEstado(html,true,true,function(){
					clearForm('form_pr_aviso_anual');
						getObj('sareta_aviso_anual_pr_rb').value ="0,00";
						getObj('aviso_anual_pr_desde').selectedIndex =0;
						getObj('aviso_anual_pr_hasta').selectedIndex =0;
			
					});	
					
				}
			}
		});
	}
});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("#sareta_aviso_anual_pr_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_aviso_anual_pr_btn_cancelar').style.display='';
	//getObj('sareta_aviso_anual_pr_btn_eliminar').style.display='none';
	//getObj('sareta_aviso_anual_pr_btn_actualizar').style.display='none';
	getObj('sareta_aviso_anual_pr_btn_guardar').style.display='';
	clearForm('form_pr_aviso_anual');
	getObj('sareta_aviso_anual_pr_rb').value ="0,00";
	getObj('aviso_anual_pr_desde').selectedIndex =0;
	getObj('aviso_anual_pr_hasta').selectedIndex =0;
});


	$('#sareta_aviso_anual_pr_matricula').alpha({allow:'0123456789 áéíóúÁÉÍÓÚñ'});
	$('#sareta_aviso_anual_pr_call_sign').alpha({allow:'0123456789 áéíóúÁÉÍÓÚñ'});
	$('#sareta_aviso_anual_pr_nombre').alpha({allow:'0123456789 áéíóúÁÉÍÓÚñ'});

</script>
<style type="text/css">
<!--
.style4 {color: #33CCFF}
-->
</style>



<div id="botonera">
	<img id="sareta_aviso_anual_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
   <img id="sareta_aviso_anual_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_aviso_anual_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form method="post" id="form_pr_aviso_anual" name="form_pr_aviso_anual">
<input type="hidden" name="vista_id_aviso_anual" id="vista_id_aviso_anual" />
<input type="hidden" name="sareta_aviso_anual_id_buque" id="sareta_aviso_anual_id_buque" />
<input type="hidden" name="sareta_aviso_anual_id_ley_buque" id="sareta_aviso_anual_id_ley_buque" />
<input type="hidden" name="sareta_aviso_anual_id_bandera_buque" id="sareta_aviso_anual_id_bandera_buque" />
<input type="hidden" name="sareta_aviso_anual_id_clase_buque" id="sareta_aviso_anual_id_clase_buque" />
<input type="hidden" name="sareta_aviso_anual_id_actividad_buque" id="sareta_aviso_anual_id_actividad_buque" />
<input type="hidden" name="sareta_aviso_anual_tarifa_buque" id="sareta_aviso_anual_tarifa_buque" />
<input type="hidden" name="sareta_aviso_anual_pr_matricula" id="sareta_aviso_anual_pr_matricula" />

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Aviso Anual</th>
	</tr>
<tr>
    	<th>Buque:</th>
    	<td><ul class="input_con_emergente">
              <li>
		<p>
		<input name="sareta_aviso_anual_pr_buque" type="text" class="style4" id="sareta_aviso_anual_pr_buque"
        value="" size="60" maxlength="1000"  readonly
						message="Introduzca una Buque para el Aviso Anual." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñÑ-]{1,60}$/, message:'Buque  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñÑ-]/, cFunc:'alert', cArgs:['buque: '+$(this).val()]}" />
         
                        
        <li id="sareta_aviso_anual_pr_btn_consultar_buque" class="btn_consulta_emergente"></li>
		    </ul></td>
    </tr>    <tr>                    
      <th>Call Sign:</th>
      <td>
      <input name="sareta_aviso_anual_pr_call_sign" type="text" id="sareta_aviso_anual_pr_call_sign" 
      size="15" maxlength="10" readonly="readonly"/>
       </td>
	</tr>
        <tr>
    	<th>Tipo de Buque:</th>
    	<td>
		<input name="sareta_aviso_anual_pr_clase" type="text" class="style4" id="sareta_aviso_anual_pr_clase"  		value="" size="60" maxlength="60"  readonly/>
         </td>
    </tr>
	
    <tr>
    	<th>Actividad:</th>
    	<td>
		  <input name="sareta_aviso_anual_pr_actividad" type="text" class="style4" id="sareta_aviso_anual_pr_actividad"  value="" size="60" maxlength="60"  readonly/>
         </td>
    </tr>
    <tr>
           <th>Bandera:		</th>	
	       <td>
           <input name="sareta_aviso_anual_pr_bandera" type="text" class="style4" id="sareta_aviso_anual_pr_bandera"  value="" size="60" maxlength="60"  readonly />
         
          </td>
	</tr>
    <tr>
	<th>R. Bruto: </th>	
	<td><input  name="sareta_aviso_anual_pr_rb" type="text" id="sareta_aviso_anual_pr_rb"  size="8"  value="0,00" readonly="readonly"/></td>
	</tr>
	   <tr>
    	<th>A&ntilde;o Desde:</th>
    	<td>
        <select name="aviso_anual_pr_desde" id="aviso_anual_pr_desde" style="width:60px; min-width:60px;" >
        <?=$fecha ?>
		</select> 
        </td>
      </tr>
      <tr>
    	<th>A&ntilde;o Hasta:</th>
    	<td>
        <select name="aviso_anual_pr_hasta" id="aviso_anual_pr_hasta" 
        style="width:60px; min-width:60px;" >
	<?=$fecha ?>			
		</select> 
        </td>
      </tr>
	<tr>
		<th><blockquote>
		  <p>Comentario:</p>
	    </blockquote></th>			
      <td ><textarea name="sareta_aviso_anual_pr_vista_observacion" cols="60" 
        id="sareta_aviso_anual_pr_vista_observacion"  
        message="Introduzca una Observación. "></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>