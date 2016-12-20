<? if (!$_SESSION) session_start();
?>
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
/*$sql="	SELECT 
			monto
		FROM 
			escala_sueldos 
		ORDER BY id_escala_sueldo";
$rs_monto =& $conn->Execute($sql);
$row=$rs_monto->fields("monto");
$cc=0;
while (!$rs_monto->EOF){
	$cc++;
	echo $row."--".$cc."<br>";
	$rs_monto->MoveNext();
}*/
?>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script>
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
var dialog;
//----------------------------------------------------------------------------------------------------

/*$("#escala_sueldo_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/escala_sueldo/db/vista.grid_escala_sueldo.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Escala de Sueldo', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nivel= jQuery("#escala_sueldo_db_nivel_grid").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/escala_sueldo/db/sql_escala_sueldo.php?busq_nivel="+busq_nivel,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#escala_sueldo_db_nivel_grid").change(function()
				{
						//if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#escala_sueldo_db_escala_grid").change(function(key)
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
							var busq_nivel= jQuery("#escala_sueldo_db_nivel_grid").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/escala_sueldo/db/sql_escala_sueldo.php?busq_nivel="+busq_nivel,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/escala_sueldo/db/sql_escala_sueldo.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nivel','Escala','Monto','Descripcion',''],
								colModel:[
									{name:'id_escala_sueldo',index:'id_escala_sueldo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nivel',index:'nivel', width:50,sortable:false,resizable:false},
									{name:'escala',index:'escala', width:50,sortable:false,resizable:false},
									{name:'monto',index:'monto', width:100,sortable:false,resizable:false},
									{name:'descri',index:'descri', width:350,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:50,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('escala_sueldo_db_id_escala_sueldo').value=ret.id_escala_sueldo;
									if(ret.nivel==1){
										getObj('escala_sueldo_db_nivel').selectedIndex=1;
									}
									if(ret.nivel==2){
										getObj('escala_sueldo_db_nivel').selectedIndex=2;
									}
									if(ret.nivel==3){
										getObj('escala_sueldo_db_nivel').selectedIndex=3;
									}
									if(ret.nivel==4){
										getObj('escala_sueldo_db_nivel').selectedIndex=4;
									}
									if(ret.nivel==5){
										getObj('escala_sueldo_db_nivel').selectedIndex=5;
									}
									if(ret.nivel==6){
										getObj('escala_sueldo_db_nivel').selectedIndex=6;
									}
									if(ret.nivel==7){
										getObj('escala_sueldo_db_nivel').selectedIndex=7;
									}
									if(ret.nivel==8){
										getObj('escala_sueldo_db_nivel').selectedIndex=8;
									}
									if(ret.escala==1){
										getObj('escala_sueldo_db_escala').selectedIndex=1;
									}
									if(ret.escala==2){
										getObj('escala_sueldo_db_escala').selectedIndex=2;
									}
									if(ret.escala==3){
										getObj('escala_sueldo_db_escala').selectedIndex=3;
									}
									if(ret.escala==4){
										getObj('escala_sueldo_db_escala').selectedIndex=4;
									}
									if(ret.escala==5){
										getObj('escala_sueldo_db_escala').selectedIndex=5;
									}
									if(ret.escala==6){
										getObj('escala_sueldo_db_escala').selectedIndex=6;
									}
									if(ret.escala==7){
										getObj('escala_sueldo_db_escala').selectedIndex=7;
									}
									if(ret.escala==8){
										getObj('escala_sueldo_db_escala').selectedIndex=8;
									}
									getObj('escala_sueldo_db_monto').value=ret.monto;
									getObj('escala_sueldo_db_descri').value=ret.descri;
									getObj('escala_sueldo_db_comentario').value=ret.observacion;
									getObj('escala_sueldo_db_btn_guardar').style.display = 'none';
									getObj('escala_sueldo_db_btn_actualizar').style.display = '';
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
								sortname: 'id_escala_sueldo',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});*/
//----------------------------------------------------------------


/*$("#escala_sueldo_db_btn_guardar").click(function() {
	if ($('#form_db_escala_sueldo').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/escala_sueldo/db/sql.registrar_escala_sueldo.php",
			data:dataForm('form_db_escala_sueldo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar();
					getObj('escala_sueldo_db_btn_actualizar').style.display='none';
					getObj('escala_sueldo_db_btn_guardar').style.display='';
					//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
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
});*/


//----------------------------------------------------------------
//----------------------Actualizar--------------------------------
$("#escala_sueldo_db_btn_actualizar").click(function() {
	if ($('#form_db_escala_sueldo').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/escala_sueldo/db/sql.actualizar_escala_sueldo.php",
			data:dataForm('form_db_escala_sueldo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					limpiar();
					getObj('escala_sueldo_db_btn_actualizar').style.display='none';
					getObj('escala_sueldo_db_btn_guardar').style.display='';
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
// ******************************************************************************

$("#escala_sueldo_db_btn_cancelar").click(function() {
limpiar();
setBarraEstado("");
});
function limpiar(){
	var i;
	for(i=1;i<57;i++){
		var sueldo= "sueldo"+i;
		getObj(sueldo).value="0,00";
	}
	getObj('escala_sueldo_db_btn_actualizar').style.display='none';
	getObj('escala_sueldo_db_btn_guardar').style.display='';
}
//Validacion de los campos
$('#escala_sueldo_db_monto').numeric({allow:' ,.'});
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
	<img id="escala_sueldo_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img style="display:none" id="escala_sueldo_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="escala_sueldo_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="escala_sueldo_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_escala_sueldo" id="form_db_escala_sueldo">
<input type="hidden" name="escala_sueldo_db_id_escala_sueldo" id="escala_sueldo_db_id_escala_sueldo"/>
<table width="521" class="cuerpo_formulario">
  <tr>
			<th class="titulo_frame">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Escala de Sueldos</th>
	</tr>
        <tr>
			<th>
            <table width="100%" class="cuerpo_form" border="0">
			  <tr>
			    <th width="22%" style="border-left:none"><div align="center">Sueldos/Niveles</div></th>
			    <th width="9%"><div align="center">
			      <p>MIN	I		    </p>
			    </div></th>
			    <th width="10%"><div align="center">II</div></th>
			    <th width="8%"><div align="center">III</div></th>
			    <th width="12%"><div align="center">PROM IV</div></th>
			    <th width="6%"><div align="center">V</div></th>
			    <th width="8%"><div align="center">VI</div></th>
			    <th width="25%"><div align="center">VII</div></th>
		      </tr>
			  <?php
			 $c=0;
			  for($j=1;$j<9;$j++){
				?>
              <tr>
			    <th style="border-left:none"><div align="center"><?php echo $j; ?></div></th>
                 <?php 
				 $sql="	SELECT 
								monto
							FROM 
								escala_sueldos 
							ORDER BY id_escala_sueldo
						";
				$rs_monto =& $conn->Execute($sql);
				 while (!$rs_monto->EOF){
					 $row=$rs_monto->fields("monto");
					//echo $row."--".$cc."<br>";
					$rs_monto->MoveNext();
				 }
			  		for($i=0;$i<7;$i++){
						$c++;
					
				?>
			    <td>
			      <div align="center">
			        <input name="sueldo<?php echo $c; ?>" type="text" id="sueldo<?php echo $c; ?>" size="4" alt="signed-decimal" style="text-align:right" value="<?php echo $row; ?>"/>
		          </div>
			    </td>
                <?php
				
					}
				?>
		      </tr>
              <?php
			 }
			  ?>
		    </table></th>
	    </tr>
		<tr>
			<td class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>