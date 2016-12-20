<script type="text/javascript">
$('#contabilidad_auxiliares_nombre_con').focus();
$("#list_consulta_contabilidad_auxiliares").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",
	url:'modulos/contabilidad/auxiliares/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Cuenta Contable','Auxiliar','Nombre','Usuario'],
   	colModel:[
			{name:'cuenta_contable',index:'cuenta_contable', width:100},
			{name:'auxiliar',index:'auxiliar',width:170},
			{name:'nombre',index:'nombre',width:170},
			{name:'Usuario',index:'Usuario',width:100}			
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_contabilidad_auxiliares'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function contabilidad_auxiliares_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(contabilidad_auxiliares_gridReload,500)
} 

function contabilidad_auxiliares_gridReload()
{ 
	var busq_nombre = jQuery("#contabilidad_auxiliares_nombre_con").val(); 
	//var busq_usuario = jQuery("#contabilidad_auxiliares_usuario").val(); 
	var busq_cod=jQuery("#contabilidad_auxiliares_cod").val(); 
	var busq_aux_nom=jQuery("#contabilidad_auxiliares_nom_aux").val(); 
	/*var presupuesto_ley_busqueda_codigo = jQuery("#presupuesto_ley_busqueda_codigo").val(); 
	var presupuesto_ley_busqueda_partida=jQuery("#presupuesto_ley_busqueda_partida").val(); 
	*/jQuery("#list_consulta_contabilidad_auxiliares").setGridParam({url:"modulos/contabilidad/auxiliares/co/sql.consulta.php?busq_nombre="+busq_nombre+"&busq_cod="+busq_cod+"&busq_aux_nom="+busq_aux_nom,page:1}).trigger("reloadGrid"); 
	url="modulos/contabilidad/auxiliares/co/sql.consulta.php?busq_nombre="+busq_nombre+"&busq_cod="+busq_cod+"&busq_aux_nom="+busq_aux_nom;
	//alert(url);
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}//$('#tesoreria_banco_nombre').apha({nocaps:true});
$('#contabilidad_auxiliares_nombre_con').numeric({allow:'.'});
$('#contabilidad_auxiliares_cod').numeric({allow:'.'});
$('#contabilidad_auxiliares_nom_aux').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});


//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'¥'});
//$('#contabilidad_auxiliares_nombre_con').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
</script>
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Auxiliares </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
		
     
        		<label id="" for="contabilidad_auxiliares_nombre_con">Cuenta Contable:</label> &nbsp; <input type="text" name="contabilidad_auxiliares_nombre_con" id="contabilidad_auxiliares_nombre_con" onKeyDown="contabilidad_auxiliares_doSearch(arguments[0]||event)" maxlength="25" size="25"  />
        	<label id="" for="contabilidad_auxiliares_cod">Cuenta auxiliar:</label> &nbsp;
        <input type="text" name="contabilidad_auxiliares_cod" id="contabilidad_auxiliares_cod"  maxlength="25" size="25" onKeyDown="contabilidad_auxiliares_doSearch(arguments[0]||event)" /></br>
           </div>    
               <label id="" for="contabilidad_auxiliares_nom_aux">
               
                 <p>&nbsp;&nbsp;&nbsp; Nombre auxiliar:</label> 
		 &nbsp; 
		 <input type="text" name="contabilidad_auxiliares_nom_aux" id="contabilidad_auxiliares_nom_aux"  maxlength="25" size="25" onKeyDown="contabilidad_auxiliares_doSearch(arguments[0]||event)" />
         
			</div>
			<table id="list_consulta_contabilidad_auxiliares" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_contabilidad_auxiliares" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>