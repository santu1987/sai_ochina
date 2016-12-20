<link rel="stylesheet" type="text/css" media="all" href="../../../../utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="../../../../utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="../../../../utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="../../../../utilidades/jscalendar-1.0/calendar-setup.js"></script>
<div class="div_busqueda">
<form id="form1" method="post">
<label><input type="text" name="fecha" id="fecha" size="7" />
		<button type="reset" id="fecha_desde_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "fecha",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_desde_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script></label>
                <?php echo "Hola";?>
</form>                