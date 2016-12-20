<?php
session_start();
function consulta_codigo(){
$conn= pg_connect("host=localhost port=5432 user=postgres password=batusay dbname=sai_ochina");
$sql = "	SELECT 
				id_bienes
			FROM
				bienes
			
			ORDER BY 
				id_bienes
			";
$query= pg_query($conn,$sql);
while($row= pg_fetch_row($query))
 {
	$i=$row["0"];
 }
 $i=substr($i,7,5);
 if($i==" "){ $i=1;}
 else{
	 $i++;
	 $tam=strlen($i);
	 $tam=5-$tam;
	 $cad="0";
	 for($t=1;$t<$tam;$t++){
		 $cad.="0";
	 }
	 $cad.=$i;
 }
 $i="1624462"."".$cad;
  //echo $i;
return $i;
}
?>