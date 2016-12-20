// Declaro un array en el cual los indices son los ID's de los DIVS que funcionan como pestaña y los valores son los identificadores de las secciones a cargar
var tabsId=new Array();
tabsId['tab1']='seccion1';
tabsId['tab2']='seccion2';
tabsId['tab3']='seccion3';
// Declaro el ID del DIV que actuará como contenedor de los datos recibidos
var contenedor='tabContenido';

function nuevoAjax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por
	lo que se puede copiar tal como esta aqui */
	var xmlhttp=false; 
	try 
	{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch(e)
	{ 
		try
		{ 
			// Creacion del objeto AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		} 
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp=new XMLHttpRequest(); } 

	return xmlhttp; 
}

function cargaContenido()
{
	/* Recorro las pestañas para dejar en estado "apagado" a todas menos la que se ha clickeado. Teniendo en cuenta que solo puede haber una pestaña "encendida"
	a la vez resultaría mas óptimo hacer un while hasta encontrar a esa pestaña, cambiarle el estilo y luego salir, pero, creanme, se complicaría un poco el
	ejemplo y no es mi intención complicarlos */
	for(key in tabsId)
	{
		// Obtengo el elemento
		elemento=document.getElementById(key);
		// Si es la pestaña activa
		if(elemento.className=='tabOn')
		{
			// Cambio el estado de la pestaña a inactivo 
			elemento.className='tabOff';
		}
	}
	// Cambio el estado de la pestaña que se ha clickeado a activo
	this.className='tabOn';
	
	/* De aqui hacia abajo se tratatan la petición y recepción de datos */
	
	// Obtengo el identificador vinculado con el ID del elemento HTML que referencia a la sección a cargar
	seccion=tabsId[this.id];
	
	// Coloco un mensaje mientras se reciben los datos
	tabContenedor.innerHTML='<img src="loading2.gif"> Cargando, por favor espere...';
	
	// Creo el objeto AJAX y envio la petición por POST (para evitar cacheos de datos)
	var ajax=nuevoAjax();
	ajax.open("POST", "tabs_o_pestanas_con_javascript_no_intrusivo_proceso.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send('seccion='+seccion);
	
	ajax.onreadystatechange=function()
	{
		if(ajax.readyState==4)
		{
			// Al recibir la respuesta coloco directamente el HTML en la capa contenedora
			tabContenedor.innerHTML=ajax.responseText;
		}
	}
}

function mouseSobre()
{
	// Si el evento no se produjo en la pestaña seleccionada...
	if(this.className!='tabOn')
	{
		// Cambio el color de fondo de la pestaña
		this.className='tabHover';
	}
}

function mouseFuera()
{
	// Si el evento no se produjo en la pestaña seleccionada...
	if(this.className!='tabOn')
	{
		// Cambio el color de fondo de la pestaña
		this.className='tabOff';
	}
}

onload=function()
{
	for(key in tabsId)
	{
		// Voy obteniendo los ID's de los elementos declarados en el array que representan a las pestañas
		elemento=document.getElementById(key);
		// Asigno que al hacer click en una pestaña se llame a la funcion cargaContenido
		elemento.onclick=cargaContenido;
		/* El cambio de estilo es en 2 funciones diferentes debido a la incompatibilidad del string de backgroundColor devuelto por Mozilla e IE.
		Se podría pasar de rgb(xxx, xxx, xxx) a formato #xxxxxx pero complicaría innecesariamente el ejemplo */
		elemento.onmouseover=mouseSobre;
		elemento.onmouseout=mouseFuera;
	}
	// Obtengo la capa contenedora de datos
	tabContenedor=document.getElementById(contenedor);
}