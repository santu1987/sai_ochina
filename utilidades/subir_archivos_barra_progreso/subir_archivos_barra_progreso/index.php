<?php
//CREADO POR www.codigo-fuente.com
?>
<html>
<head>
	<script>
	//Declaramos nuestras variables globales
		var obcxm;
		var timer;
		var url;
		var nombre_archivo;
		var paramentros;
		var tipo_contenido;
		var metodo;
		var action2;
    	var barra;		
		function crearXMLHTTP(){
			
			var cxm = null;
			try{
				cxm = new XMLHttpRequest();
			}catch(e){
				cxm = new ActiveXObject("Microsoft.XMLHTTP");
			}
			return cxm;
		}
		function subirArchivo(){
			
			var nombre_path = document.getElementById("archivo").value.split("\\");
      		barra = document.getElementById("por");
			nombre_archivo = nombre_path[nombre_path.length-1];
      		//Filtramos la ruta y comprobamos que cumpla el archivocon la extensión permitida
			var extension_archivo = nombre_archivo.split(".");
			var extension = extension_archivo[extension_archivo.length-1];
		 	if( !extension.match(/(jpg)|(jpeg)|(gif)|(png)|(zip)|(pdf)/) )
			{
				alert ( "Sólo se permite subir archivo: jpg, gif, png, pdf y zip" );
				return false;
			}
			
			url = "proceso.php"; //Archivo donde se irá comprobando el estado
			paramentros = "id_archivo="+nombre_archivo; //el parámetro que le pasamos al arhivo anterior
			tipo_contenido = "application/x-www-form-urlencoded";

			//Se podría hacer también en get
      		metodo = "post";			
			
			//Montamos el action para el form
			document.forms[0].action = "http://codigo-fuente.com/cgi-bin/subir_archivos_barra_progreso/upload.cgi?id="+nombre_archivo;
		    
			//Enviamos el archivo a la ruta anteriror
		    document.forms[0].submit();
      
      		//Comprobamos el estado del archivo
			setTimeout("enviarPeticion()",100);
		}
		function enviarPeticion(){
			
			//Obtenemos nuestro objeto XMLHttp
			obcxm = crearXMLHTTP();
			//Abrimos la conexión
			obcxm.open(metodo, url, true);
			obcxm.setRequestHeader('Content-Type', tipo_contenido);
			//Mientras hayan cambios de estado ejecutamos la función estadoPeticion()
			obcxm.onreadystatechange = estadoPeticion;
			//Le pasamos los parametros anteriormente declarados
			obcxm.send(paramentros);			
		}
		function estadoPeticion()
		{
			if (obcxm.readyState == 4){
				if(obcxm.status == 200){	
					if(obcxm.responseText!="100"){
						barra.innerHTML = obcxm.responseText + "%";
						//Mientras no haya llegado al 100 ejecutamos la función enviarPeticion() para saber su estado
						setTimeout("enviarPeticion()",100);
						//Enviamos el tanto por ciento para pintar la barra
						barraProgreso(obcxm.responseText);
					}		
				}
			}
		}
		function barraProgreso(porcentaje)
		{
		    //Pintamos la barra incrementando el width
      		barra.style["width"]=porcentaje+"%";
    	}
	</script>
	<style type="text/css">
		#caja{
			width:350px;
			height:90px;
		}
		#barra
		{
			width:290px;
			height:20px;
			border:1px solid #000000;
			margin-top:10px;

		}
		#por
		{
	       width:0%;
	       height:20px;
	       float:left;
	       background-image:url(img/bar.png);
	       text-align:right;
    	}
	</style> 
</head>
<body>
<form name="forumlario" id="forumlario" enctype="multipart/form-data" method="post" target="_self">
		<div id="caja">
			<input type="file" name="archivo" id="archivo" />
			<input type="button" name="boton" value="Enviar" onClick="subirArchivo()"/>
			<div id="barra">
        <div id="por"></div>
      </div>
	</div>
</form>
</body>
</html>
