<? session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha=$_POST['cuentas_por_pagar_db_fecha_v'];	
$sql_porcen = "SELECT id_val_impu, porcentaje_impuesto FROM valor_impuesto INNER JOIN impuesto ON valor_impuesto.id_impuesto = impuesto.id_impuesto WHERE fecha_valor >= '$fecha' AND nombre = 'IVA' ORDER BY fecha_valor asc";
$bus =& $conn->Execute($sql_porcen);
if ($bus->EOF) 
{
		if ($bus->fields==""){
			$sql_porcen = "SELECT id_val_impu, porcentaje_impuesto FROM valor_impuesto INNER JOIN impuesto ON valor_impuesto.id_impuesto = impuesto.id_impuesto WHERE fecha_valor <= '$fecha' AND nombre = 'IVA' ORDER BY fecha_valor desc";
			$bus =& $conn->Execute($sql_porcen);
			}
			
		//$porcentajexx = $bus->fields("porcentaje_impuesto");
	
}
		$porcentajexx=number_format( $bus->fields("porcentaje_impuesto"),2,',','.');
	   die($porcentajexx);		
	?>
