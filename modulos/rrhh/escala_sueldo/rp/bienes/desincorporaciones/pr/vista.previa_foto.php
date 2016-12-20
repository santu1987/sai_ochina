<?php
	session_start();
	require_once("../../../../controladores/db.inc.php");
?>
<form id="form1" name="form1" method="post" action="">
  <input type="hidden" name="pos" id="pos" value="<?php echo $_POST['posicion'];?>"/>
  <input type="hidden" name="nombre" id="nombre" value="<?php echo $_FILES['foto_desincorporacion'.$_POST['posicion']]['name'];?>"/>
</form>
<?php
	$opt  =$_POST['opt'];
	$fecha = date("d-m-Y");
	if($opt==1){
	$pos = $_POST['posicion'];
	$tmp = $_FILES['foto_desincorporacion'.$pos]['tmp_name'];

	$type = $_FILES['foto_desincorporacion'.$pos]['type'];

	//$size = $_FILES['foto_desincorporacion'.$pos]['size'];
	echo "Type: ".$type."<br>";
	echo "Size: ".$size."<br>";
	
	//
	if ($type!="image/jpeg" /*&& $type!="image/png" && $type!="image/bmp"*/){
		$option = 0;
		die ("<script>parent.document.form_desin_foto.err_formato.onclick(); pos = document.form1.pos.value; cad = eval('parent.document.form_desin_foto.foto'+pos); cad.src='imagenes/iconos/sombra.bmp'; cad = eval('parent.document.form_desin_foto.foto_desincorporacion'+pos); cad.value='';																												</script>");
	}
	else if ($size>=1000000){
		$option = 0;
		die ("<script>parent.document.form_desin_foto.err_tamano.onclick(); pos = document.form1.pos.value; cad = eval('parent.document.form_desin_foto.foto'+pos); cad.src='imagenes/iconos/sombra.bmp'; cad = eval('parent.document.form_desin_foto.foto_desincorporacion'+pos); cad.value='';</script>");
	}
	if ($type=="image/jpeg" /*|| $type=="image/png" || $type=="image/bmp"*/ && $size<1000000){
	$option = 1;
	move_uploaded_file($tmp, '../../../../imagenes/save_cache/'.$_FILES['foto_desincorporacion'.$pos]['name']);
	echo "<script>pos = document.form1.pos.value; cad = eval('parent.document.form_desin_foto.foto'+pos); cad.src='imagenes/save_cache/'+document.form1.nombre.value; parent.document.form_desin_foto.action='modulos/bienes/desincorporaciones/pr/limpiar.save_cache.php'; parent.document.form_desin_foto.target='form_limpiar_cache'; parent.document.form_desin_foto.submit();</script>";
	}
	//
	}
	else{
		$db = dbconn("pgsql");
		$cn = pg_connect("host=".$db["host"]." port=".$db["port"]." dbname=".$db["dbname"]." user=".$db["user"]." password=".$db["password"]);
		//$cn = pg_connect("host=localhost port=5432 user=postgres password=batusay dbname=sai_ochina");
		for($i=1; $i<=4; $i++){
			if($_FILES['foto_desincorporacion'.$i]['name']!=''){
				$nombre = 'foto_'.$_POST['id_bienes']."_".$_FILES['foto_desincorporacion'.$i]['name'];
				$tmp = $_FILES['foto_desincorporacion'.$i]['tmp_name'];
				move_uploaded_file($tmp, '../../../../imagenes/desincorporaciones/'.$nombre);
				$sql = "INSERT INTO fotos_desincorporacion (id_organismo, id_bienes, nombre, ultimo_usuario, fecha_actualizacion) VALUES ('$_SESSION[id_organismo]','$_POST[id_bienes]','$nombre','$_SESSION[id_usuario]','$fecha')";
				pg_query($cn, $sql);
			}
		}
		echo "<script>parent.document.form_desin_foto.borrar.onclick();</script>";
	}
?>