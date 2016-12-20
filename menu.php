<script type='text/javascript'>

function update_organismos(id_perfil)
{
	$("#organismos").removeOption(/./);
	$("#organismos").ajaxAddOption("modulos/administracion_sistema/usuario/pr/cmb.sql.organismo.php",{id_perfil:id_perfil},false,init_menu);
}

function update_perfiles(id_usuario)
{
	$("#perfiles").removeOption(/./);
	$("#perfiles").ajaxAddOption("modulos/administracion_sistema/usuario/pr/cmb.sql.perfiles.php",{id_usuario:id_usuario},false,init_organismos);
}

function act_organismo()
{
	init_menu();
}

function init_organismos()
{
	update_organismos(getSelect('perfiles'));
}

function init_menu()
{
	closeAllTabs();
	getObj('const_menu').innerHTML="";
	id_organismo=getSelect('organismos');
	if (id_organismo)
		$('#const_menu').load("const.menu.php?id_organismo="+id_organismo+"&id_usuario="+datos_usuario[0]+"&id_perfil="+getSelect('perfiles'), '', init);
}

function logon()
{
	$.ajax (
	{
		url: "logon.php",
		data:dataForm('form_logon_off'),
		type:'POST',
		cache: false,
		success: function(html)
		{
			if (html!="Fail")
			{
				//document.getElementById('cal_depre').src='modulos/bienes/depreciacion/pr/vista.calcular_depreciacion.php';
				datos_usuario = html.split(",");	//	0:id_usuario,	1:usuario,	2:nombre,	3:apellido,	4:foto
				getObj('nombre_usuario').innerHTML=datos_usuario[2]+"&nbsp;"+datos_usuario[3];
				
				if (datos_usuario[4]!="sombra.png")
				document.cargar.src="imagenes/foto/"+datos_usuario[4];
				else
				document.cargar.src="imagenes/foto/sombra.png";
				getObj('div_logon_off').style.display='none';
				getObj('div_logon_on').style.display='';
				getObj('perfiles').innerHTML=html;			
				
				update_perfiles(datos_usuario[0]);
			}
			else
			{
				setBarraEstado("Validación Fallida");
			}
		}
	});
}
</script>

<div id="div_logon_off">
	<form id="form_logon_off" >
		<table width="190">
			<tr>
				<td width="65" rowspan="3"><img src="imagenes/password64x64.png" /></td>
				<td>Usuario	<input type="text" name="usuario" value="gsantucci"  /></td>
			</tr>
			<tr>
				<td>Clave
				  <input type="password" name="clave" value="12121212"  /></td>
			</tr>
			<tr>
				<td style="padding-top:3px;">
				<a class="button" href="javascript:logon();" ><span style="width:100px; text-align:center">Acceder</span></a>
				</td>
			</tr>
		</table>
	    <input name="nombre" type="hidden" id="nombre" value="" />
	</form>
</div>

<div id="div_logon_on" style="display:none">
	<form id="form_logon_on" name="form_logon_on" action="modulos/administracion_sistema/usuario/pr/sql.fin_seccion.php">
		<table width="190">
			 <tr>
			  <td width="1%" rowspan="5" style="padding-left:10px;padding-right:10px;"><img id="cargar" name="cargar" src="" width="64" height="64" /></td>
			  <td width="99%"><div style="padding-bottom:10px;" id='nombre_usuario'></div></td>
		    </tr>
			<tr>
			  <td colspan="2">
				<select style="width:135px; min-width:135px;" name="perfiles" id="perfiles" onchange="init_organismos()">
				</select>			  
				</td>
			</tr>
			<tr>
			  <td colspan="2">
				<select style="width:135px; min-width:135px;" name="organismos" id="organismos" onchange="act_organismo()">
				</select>			  
				</td>
			</tr>
			<tr>
			  <td colspan="2"  style="padding-top:3px;">
			  <a class="button" href="." ><span style="width:90px; text-align:center">Cerrar</span></a>
			  </td>
			</tr>
		</table>
	</form>
</div>

<div id="const_menu">
<iframe id="cal_depre" name="cal_depre" src="" style="display:none"></iframe>
</div>
