<table id="flexorganismo" style="display:none"></table>

<script type="text/javascript">

			$("#flexorganismo").flexigrid
			(
			{
			url: 'modulos/administracion_sistema/organismo/co/sql.organismo2.php',
			dataType:'json',
			colModel : [
				{display: 'Id', name : 'id_organismo', width : 20, sortable : true, align: 'left'},
				{display: 'Nombre', name : 'organismo', width : 200, sortable : true, align: 'left'},
				{display: 'Tel&eacute;fono', name : 'telefono', width : 200, sortable : true, align: 'left'},
				{display: 'Email', name : 'email', width : 200, sortable : true, align: 'left'},
				{display: 'Representante', name : 'representante', width : 200, sortable : true, align: 'left'}
				],
				searchitems : [
				{display: 'Id', name : 'id_organismo'},
				{display: 'Nombre', name : 'organismo', isdefault: true}
				],
			sortname: "organismo",
			sortorder: "asc",
			usepager: true,
			title: 'Consulta Organismos',
			useRp: true,
			rp: 15,
			width: 700,
			height: 500
			}
			);
	
</script>
