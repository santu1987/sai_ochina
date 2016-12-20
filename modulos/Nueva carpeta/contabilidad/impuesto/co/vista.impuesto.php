<table id="fleximpuesto" style="display:none"></table>

<script type="text/javascript">

			$("#fleximpuesto").flexigrid
			(
			{
			url: 'modulos/contabilidad/impuesto/co/sql.impuesto.php',
			dataType:'json',
			colModel : [
				{display: 'Id', name : 'id_impuesto', width : 20, sortable : true, align: 'left'},
				{display: 'Nombre', name : 'nombre', width : 200, sortable : true, align: 'left'},
				{display: 'Comentarios', name : 'comentarios', width : 200, sortable : true, align: 'left'},
				{display: 'Fecha', name : 'fecha', width : 200, sortable : true, align: 'left'}
				],
				searchitems : [
				{display: 'Id', name : 'id_impuesto'},
				{display: 'Nombre', name : 'nombre', isdefault: true}
				],
			sortname: "nombre",
			sortorder: "asc",
			usepager: true,
			title: 'Consulta Banco',
			useRp: true,
			rp: 15,
			width: 700,
			height: 500
			}
			);
	
</script>
