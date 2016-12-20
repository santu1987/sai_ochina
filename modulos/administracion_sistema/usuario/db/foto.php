<form id="form1" name="form1" method="post" action="">
  <label>
    <input type="text" name="foto" id="foto" value="<?php echo $_FILES['foto']['name'];?>"/>
  </label>
</form>
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$opt = $_POST['form_foto_opt'];
$tmp = $_FILES['foto']['tmp_name'];
$type = $_FILES['foto']['type'];
$size  =$_FILES['foto']['size'];
if($opt==0){
	$dir = "../../../../imagenes/save_cache/".$_FILES['foto']['name'];
	if ($size>=1000000)
	{
	echo "<script>parent.document.form_usuario.nomfoto.value=''; 			  parent.document.form_foto.foto_usuario.src='imagenes/user.png';
	 parent.document.form_foto_us.form_foto_err_t.onclick();</script>";
	
	}
	if ($type!="image/jpeg" && $type!="image/png" && $type!="image/bmp")
	{
	echo "<script>parent.document.form_usuario.nomfoto.value=''; parent.document.form_foto.foto_usuario.src='imagenes/foto/sombra.png'; parent.document.form_foto.form_foto_err_f.onclick();</script>";
	
	}
	if ($type=="image/jpeg" || $type=="image/png" || $type=="image/bmp" && $size<1000000)
	{
		move_uploaded_file($tmp,$dir);
		echo "<script>parent.document.form_foto.foto_usuario.src='imagenes/save_cache/'+document.form1.foto.value; parent.document.form_foto.action='modulos/administracion_sistema/usuario/db/limpiar_cache.php'; parent.document.form_foto.target='limpiar_cache';  parent.document.form_foto.submit();</script>";
	}
	}
	
if($opt==1){	
	$dir = "../../../../imagenes/foto/";
	$dir.= $_POST['nomfoto'].".png";
	$cedula = $_POST['nomfoto'];
	if (substr($type,0,5)!='image'){
	$load=0;
	}	
	if ($type=="image/jpeg" || $type=="image/png" || $type=="image/bmp" && $size<1000000)
	{
	move_uploaded_file ($tmp, $dir);
	$load = 1;
	}
	
}
?>