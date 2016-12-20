<?php
	if (!$_SESSION) session_start();
?>
<ul id='qm0' class='qmmc'>
<?
if ($_GET[id_usuario] && $_GET[id_perfil] && $_GET[id_organismo])
{
	$_SESSION[id_organismo]=$_GET[id_organismo];
	$id_usuario = $_SESSION['id_usuario'];
	require_once('controladores/db.inc.php');
	require_once('utilidades/adodb/adodb.inc.php');
	$conn = &ADONewConnection('postgres');
	
	$db=dbconn("pgsql");
	$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	$sql="
				SELECT 
					modulo.id,
					modulo.nombre,
					modulo.icono  
				FROM 
					modulo
				INNER JOIN 
					perfil_modulo 
				ON 
					 modulo.id = perfil_modulo.id_modulo 
				INNER JOIN 
					perfil_usuario 
				ON 
					perfil_usuario.id_perfil = perfil_modulo.id_perfil 
				WHERE 
					perfil_usuario.id_usuario =$_GET[id_usuario]  AND 
					perfil_usuario.id_perfil =$_GET[id_perfil] 
				ORDER BY 
					nombre ASC
	";
	$rs_modulo =& $conn->Execute($sql);
	while (!$rs_modulo->EOF) {
		echo "
		<li><a class='qm-startopen qmparent' href='javascript:void(0)'><img src='imagenes/iconos/".$rs_modulo->fields("icono")."'/>".$rs_modulo->fields("nombre")."</a>
		<ul>
		";
		$sql="SELECT * FROM proceso ORDER BY id ASC";
		$rs_proceso =& $conn->Execute($sql);
		while (!$rs_proceso->EOF) {	
			echo "
			<li><a class='qmparent' href='javascript:void(0)'><img src='imagenes/iconos/".$rs_proceso->fields("icono")."' />".$rs_proceso->fields("nombre")."</a>
			<ul>
			";
			
			$sql="
					SELECT 
						programa.*, 
						perfil_programa.id_programa 
					FROM 
						programa, 
						perfil_programa, perfil_usuario 
					WHERE 
						(programa.id = perfil_programa.id_programa) AND
						(perfil_programa.id_perfil = perfil_usuario.id_perfil) AND
						(perfil_usuario.id_usuario = $_GET[id_usuario])AND 
						(programa.id_modulo=".$rs_modulo->fields("id").") AND 
						(programa.id_proceso=".$rs_proceso->fields("id").")  AND 
						(perfil_usuario.id_perfil =$_GET[id_perfil])  
					ORDER BY 
						programa.nombre ASC
			";
	
			$rs_programa =& $conn->Execute($sql);
			while (!$rs_programa->EOF) {
				echo "<li><a class='lnk' title='".$rs_programa->fields("nombre")."' id='".$rs_programa->fields("pagina")."' href='javascript:void(0)'>".$rs_programa->fields("nombre")."</a></li>";
				$rs_programa->MoveNext();			
			}
			echo "
			</ul>
			</li>	
			";
			$rs_proceso->MoveNext();
		}
		echo "
		</ul>
		</li>
		";
	   $rs_modulo->MoveNext();
	}
}
?>


<li class="qmclear">&nbsp;</li>
</ul>