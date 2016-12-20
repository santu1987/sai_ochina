<table id="flexbanco" style="display:none"></table>

<script type="text/javascript">

			$("#flexbanco").flexigrid
			(
			{
			url: 'modulos/contabilidad/banco/co/sql.banco.php',
			dataType:'json',
			colModel : [
				{display: 'Id', name : 'id_banco', width : 20, sortable : true, align: 'left'},
				{display: 'Nombre', name : 'nombre', width : 200, sortable : true, align: 'left'},
				{display: 'Tel&eacute;fono', name : 'telefono', width : 200, sortable : true, align: 'left'},
				{display: 'Email', name : 'email_contacto', width : 200, sortable : true, align: 'left'},
				{display: 'Persona Contacto', name : 'persona_contacto', width : 200, sortable : true, align: 'left'}
				],
				searchitems : [
				{display: 'Id', name : 'id_banco'},
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
