<?php
require_once('../../controladores/db.inc.php');
require_once('../../utilidades/adodb/adodb.inc.php');

$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$Sql="SELECT
					a.attname,
					pg_catalog.format_type(a.atttypid, a.atttypmod) as type, 
					a.atttypmod,
					a.attnotnull, a.atthasdef, adef.adsrc,
					a.attstattarget, a.attstorage, t.typstorage,
					(
						SELECT 1 FROM pg_catalog.pg_depend pd, pg_catalog.pg_class pc
						WHERE pd.objid=pc.oid 
						AND pd.classid=pc.tableoid 
						AND pd.refclassid=pc.tableoid
						AND pd.refobjid=a.attrelid
						AND pd.refobjsubid=a.attnum
						AND pd.deptype='i'
						AND pc.relkind='S'
					) IS NOT NULL AS attisserial,
					pg_catalog.col_description(a.attrelid, a.attnum) AS comment 

				FROM
					pg_catalog.pg_attribute a LEFT JOIN pg_catalog.pg_attrdef adef
					ON a.attrelid=adef.adrelid
					AND a.attnum=adef.adnum
					LEFT JOIN pg_catalog.pg_type t ON a.atttypid=t.oid
				WHERE 
					a.attrelid = (SELECT oid FROM pg_catalog.pg_class WHERE relname='$_GET[table]'
						AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE
						nspname = '$_GET[schema]'))
					AND a.attnum > 0 AND NOT a.attisdropped
				ORDER BY a.attnum";

$row=& $conn->Execute($Sql);

while (!$row->EOF) 
{
	/*foreach ($row->fields as $key => $val) 
	{
		$i++;
		if (!($i%2)) echo "$key : $val <br />";
	}*/
	$items.= "
						<table>
						<tr>
						<td><input type='checkbox' /></td>
						<td>
							<table border=1 width='1000px'>
								<tr>
									<td width='200px'><input type='text' id='titleinput_".$row->fields("attname")."' /></td>
									<td width='200px'>".$row->fields("attname")."<input type='hidden' id='nameinput_".$row->fields("attname")."' /></td>
									<td width='200px'>".$row->fields("type")."</td>
									<td>
											<select id='typeinput_".$row->fields("attname")."' name='typeinput_".$row->fields("attname")."'>
												<option>Alpha Numerico</option>
												<option>Alpha Numerico Oculto</option>
												<option>Alphabetico</option>
												<option>Alphabetico Oculto</option>
												<option>Numerico</option>
												<option>Numerico Oculto</option>
												<option>Moneda</option>
												<option>timestamp oculto</option>
												<option>timestamp selecionable</option>
												<option>Lista Selecionable</option>
												<option>Lista Selecionable Enlazada a Base de Datos</option>
												<option>Lista Despeglable</option>
												<option>Lista Despeglable Enlazada a Base de Datos</option>
												<option>MiltiLinea</option>
											</select>
									</td>
									<td width='50px'><input maxlength='2' size='2' type='text' /></td>
								</tr>
							</table>						
						</td>
						</tr>
						</table>";
	$row->MoveNext();
}

?>
<table>
<form>
Nombre del Formulario:<input type="text"  id="nombre_formulario" />

<?=$items?>
</form>
</table>