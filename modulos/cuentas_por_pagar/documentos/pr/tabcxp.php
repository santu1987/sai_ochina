<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
.container {width: 700px; margin: 10px auto;}
ul.tabspersona {
	margin: 0;
	padding: 0;
	float: left;
	list-style: none;
	height: 32px;
	border-bottom: 1px solid #999;
	border-left: 1px solid #999;
	width: 100%;
}
ul.tabspersona li {
	float: left;
	margin: 0;
	padding: 0;
	height: 31px;
	line-height: 31px;
	border: 1px solid #999;
	border-left: none;
	margin-bottom: -1px;
	background: #e0e0e0;
	overflow: hidden;
	position: relative;
}
ul.tabspersona li a {
	text-decoration: none;
	color: #000;
	display: block;
	font-size: 1.2em;
	padding: 0 20px;
	border: 1px solid #fff;
	outline: none;
}
ul.tabspersona li a:hover {
	background: #ccc;
}	
html ul.tabspersona li.active, html ul.tabspersona li.active a:hover  {
	background: #fff;
	border-bottom: 1px solid #fff;
}
.tab_container {
	border: 1px solid #999;
	border-top: none;
	clear: both;
	float: left; 
	width: 100%;
	background: #fff;
	-moz-border-radius-bottomright: 5px;
	-khtml-border-radius-bottomright: 5px;
	-webkit-border-bottom-right-radius: 5px;
	-moz-border-radius-bottomleft: 5px;
	-khtml-border-radius-bottomleft: 5px;
	-webkit-border-bottom-left-radius: 5px;
}
.tab_contentpersona {
	padding: 20px;
	font-size: 1.2em;
}
.tab_contentpersona table th{
	white-space:nowrap;
	text-align:justify;
	height:20px;
}
.tab_contentpersona table td{
	height:20px;
}
.tab_contentpersona h2 {
	font-weight: normal;
	padding-bottom: 10px;
	border-bottom: 1px dashed #ddd;
	font-size: 1.8em;
}
.tab_contentpersona h3 a{
	color: #254588;
}
.tab_contentpersona img {
	float: left;
	margin: 0 20px 20px 0;
	border: 1px solid #ddd;
	padding: 5px;
}
.tab_contentpersona select{
	min-width:200px;
}
</style>

<script type='text/javascript'>

//Default Action
$(".tab_contentpersona").hide(); //Hide all content
$("ul.tabspersona li:first").addClass("active").show(); //Activate first tab
$(".tab_contentpersona:first").show(); //Show first tab content

//On Click Event
$("ul.tabspersona li").click(function() {
	$("ul.tabspersona li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	$(".tab_contentpersona").hide(); //Hide all tab content
	var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active content
	return false;
});

</script>
</head>

<body>
<div class="container">
	<h1>.</h1>
    <ul class="tabspersona">
        <li><a href="#tab1">Datos Personales</a></li>
        <li><a href="#tab2">Informaci&oacute;n Medica</a></li>
        <li><a href="#tab3">Veh&iacute;culo</a></li>
        
    </ul>
 <div class="tab_container">
 	 <div id="tab1" class="tab_contentpersona">
	 	 <h1>a</h1>
	 </div>
	  <div id="tab2" class="tab_contentpersona">
		 <h1>b</h1>
	 </div>
	  <div id="tab3" class="tab_contentpersona">
		 <h1>c</h1>
	 </div>
 </div>
</body>
</html>
