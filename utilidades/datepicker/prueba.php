<script language="javascript" type="text/javascript" src="mootools-1.2-core.js"></script>
<script language="javascript" type="text/javascript" src="_class.datePicker.js"></script>
<link href="_web.css" type="text/css" rel="stylesheet"></link>
<link href="style.css" type="text/css" rel="stylesheet"></link>
<script language="javascript">
window.addEvent('domready',function(){
var fecha03 = $('calendario').getPrevious();
		var dp3 = $('calendario').datePicker({
			format: '%DD %d, %MM %Y | %D %d %M %y | %Y-%m-%d',
			
			initial: [2008,10,18],
			setInitial: true,
			updateElement: false,
			onShow: function(container){
				container.fade('hide').fade('in');
			},
			onHide: function(container){
				container.fade('out');
			},
			onUpdate: function(){
				fecha03.set('html',this.format());
			}
		},'click');

		var inputs03 = $('fecha').getElements('input,button').associate(['fecha','formato','set']);
		inputs03.set.addEvent('click',function(){
			var date = inputs03.fecha.value.split('/');
			dp3.setFullDate(date[2].toInt(),date[1].toInt()-1,date[0].toInt());
			dp3.options.format = inputs03.formato.value;
			dp3.update();
		});
});
</script>
<input name="fecha" type="text" id="fecha" value="dd/mm/yyyy" size="10" maxlength="10">
<img id="calendario" src="calendar.png" width="16" height="16">

