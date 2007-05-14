<? 
	include 'header.php';
	
	$edition = isset($_GET['edition']) ? intval($_GET['edition']) : 4;
?>

<div id="section">
	
	<div style="margin: 5px 0 5px 5px">
		<strong><a href="index.php">Inicio</a></strong>
		&nbsp;-&nbsp;
		B&uacute;squeda de paquetes
	</div>
</div>
<br />
<div id="body">
	<div style="margin: 15px 0 0 15px">
		<div id="selection">
			<? include 'frm_selection.php' ?>
		</div>
		<div id="find">
			<div class="table category">
				<div class="htable"><strong>Categor&iacute;as</strong></div>
				<div class="btable">
					<? printListTypes($edition) ?>
				</div>
			</div>
			<div class="table fields">
				<div class="htable"><strong>Criterios de b&uacute;squeda</strong></div>
				<div class="btable">
					<? include 'frm_find.php' ?>
				</div>
			</div>
		</div>
	</div>
</div>
<? include 'footer.php' ?>