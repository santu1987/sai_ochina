<script language="javascript" type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<link href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css"></link>
<form name="form_fecha" method="post" action="">
<input type="text" value="<?php echo date("d-m-Y");?>" readonly name="theDate"><input type="button" value="Cal" onclick="displayCalendar(document.form_fecha.theDate,'dd-mm-yyyy',this)">
</form>
