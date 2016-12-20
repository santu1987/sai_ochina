// Titulo: Validaciones a usar en los sistemas
// Version: 1.0
// Fecha: 06/11/2004 (dd/mm/yyyy)
// Dentro del tag Head se llama asi:
// <script language="JavaScript" src="../recursos/js/validaciones.js"></script>

//var nav4 = window.Event ? true : false;
var nav5 = window.Event ? true : false;
//var nav6 = window.Event ? true : false;

//----------------------------------------------------------------------------------------
function acceptNumInt(evt)
// Se llama con : onKeyPress="return acceptNumInt(event)"	
{	
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
	var key = nav5 ? evt.which : evt.keyCode;	
	return (key <= 13 || (key >= 48 && key <= 57 ));
}

//------------------------------------------------------------------------------------
function acceptLetra(evt)
// Se llama con : onKeyPress="return acceptLetra(event)"	
{	
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
	var key = nav5 ? evt.which : evt.keyCode;	
	return (   key == 46 || key <= 13 || key <= 33 || (key >= 65 && key <= 122 ));
}

//--------------------------------------------------------------------------------
function acceptNum(evt)
// Se llama con : onKeyPress="return acceptNum(event)"	
{	
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
	var key = nav5 ? evt.which : evt.keyCode;	
	return (key <= 13 || (key >= 48 && key <= 57));
}

//--------------------------------------------------------------------------------
function acceptNumLetras(evt)
// Se llama con : onKeyPress="return acceptNumLetras(event)"	
{	
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
	var key = nav5 ? evt.which : evt.keyCode;	
	return (key <= 13 || (key >= 40 && key <= 90) || (key >= 97 && key <= 122) );
}

//--------------------------------------------------------------------------------
function acceptNumDecim(evt)
// Se llama con : onKeyPress="return acceptNumDecim(event)"	
{	
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
	var key = nav5 ? evt.which : evt.keyCode;	
	return (   key == 46 || key <= 13 || (key >= 48 && key <= 57 ));
}

//--------------------------------------------------------------------------------
function validarEmailNecesario(field)
// Se llama con : onBlur="validarEmailNecesario(this);"
// No pasa hasta que no teclea una direccion correcta
{
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(field.value)  || field.value == ""){

  } else {
    alert("Error, por favor ingrese una dirección de correo electronico valida!");
	field.focus();
	field.value="";
  }
}

//--------------------------------------------------------------------------------
function validarEmail(field)
// Se llama con : onChange="validarEmail(this);"
// Solo valida la direccion de correo, permite en blanco
{
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(field.value)){

  } else {
    alert("Error, debe ingresar una dirección de correo electronico valida!");
	field.value="";
  }
}
//--------------------------------------------------------------------------------

function validarHora(field)
// Se llama con : onBlur="validarEmailNecesario(this);"
// No pasa hasta que no teclea una direccion correcta
{
	//if (/^\w+([\.-]?\w+)*:\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(field.value)){
  if (/^\w+([\.-]?\w+)*:\w+([\.-]?\w+)+$/.test(field.value)  || field.value == ""){

  } else {
    alert("Error, por favor ingrese una hora valida!");
	field.focus();
	field.value="";
  }
}

//--------------------------------------------------------------------------------
function acceptLetters(field)
// Se llama con : onChange=" return acceptLetters(this);"
{
var str = field.value;
if (str == "") {
alert("\nError, el campo esta vacio.\n\nPor favor, debe teclear algun valor.")
field.focus();
return false;
}
for (var i = 0; i < str.length; i++) {
var ch = str.substring(i, i + 1);
if (((ch < "a" || "z" < ch) && (ch < "A" || "Z" < ch)) && ch != ' ') 
{
alert("\nEste campo solo acepta letras y espacios.\n\nPor favor, teclee los datos correctos.");
field.select();
field.focus();
return false;
   }
}
return true;
}		

//--------------------------------------------------------------------------------
function acceptLettersNoBlank(field)
// Se llama con : onChange=" return acceptLettersNoBlank(this);"
{
var str = field.value;
for (var i = 0; i < str.length; i++) {
var ch = str.substring(i, i + 1);
if (((ch < "a" || "z" < ch) && (ch < "A" || "Z" < ch))) 
{
alert("\nEste campo solo acepta letras (sin espacios).\n\nPor favor, teclee los datos correctos.");
field.select();
field.focus();
return false;
   }
}
return true;
}

//--------------------------------------------------------------------------------
function limitTextBox(field, cuantos)
// Se llama con onkeypress="return limitTextBox(this, 10);"
{
if (field.value.length==cuantos)
{
field.select();
field.focus();
return false;
}
return true;
}

//--------------------------------------------------------------------------------
function checkDecimals(field, decallowed) {
//Se llama con onClick="checkDecimals(this, 2)"

//decallowed = 2;  // how many decimals are allowed?

if (isNaN(field.value) || field.value == "") {
alert("Error, Por favor, teclee un número.");
field.select();
field.focus();
}
else {
if (field.value.indexOf('.') == -1) field.value += ".";
dectext = field.value.substring(field.value.indexOf('.')+1, field.value.length);

if (dectext.length > decallowed)
		{
alert ("Error, debe teclear un número con " + decallowed + " lugares decimales.");
field.select();
field.focus();
		}
   }
}

//--------------------------------------------------------------------------------

function fdp(n,d){
	var xx = n.indexOf('.')
	var l = n.length
	var zstr = '0000000000000000000000'
	var theInt = ''
	var theFrac = ''
	var theNo = ''
	rfac = ''
	rfacx = 0
	nx = 0
	var xt = parseInt(d) + 1
	var rstr = '' + zstr.substring(1,xt)
	var rfac = '.' + rstr + '5'
	var rfacx = parseFloat(rfac)
	if (xx == -1 ) 	{    // No fraction
		theFrac = zstr
		theInt = "" + n
	}
	else if (xx == 0) {
		theInt = '0'
		nx = 0 + parseFloat(n) + parseFloat(rfacx)
		n = nx + zstr
		theFrac = '' + n.substring(1, n.length)
	}
	else {
		theInt = n.substring(0,xx)
		nx = parseFloat(n) + rfacx
		n = '' + nx + zstr
		theFrac = '' + n.substring(xx+1,xx + 1 + parseInt(d))
		var astr = 'd = ' + d
	}
	theFrac = theFrac.substring(0,parseInt(d))
	var ii = 0
	theNo = theInt + '.' + theFrac
	return theNo
}

//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
