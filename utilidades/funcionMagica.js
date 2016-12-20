//----------------------------------------------------------------------------------------
function Ocultar(id) {
	document.getElementById(id).style.display ='none';
}

//----------------------------------------------------------------------------------------
function Mostrar(id) {
	document.getElementById(id).style.display ='block';
	/*
	if (document.getElementById(id).style.display == 'block') {
		document.getElementById(id).style.display ='none';
	} else {
		document.getElementById(id).style.display ='block';
	}
	*/
}

//----------------------------------------------------------------------------------------
function MostrarOcultar(x) {
	if (document.all[x].style.display=='block') {
		document.all[x].style.display='none';
	} else {
		document.all[x].style.display='block';
	}
}

//----------------------------------------------------------------------------------------
