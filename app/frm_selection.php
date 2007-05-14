<? $others = isset($_GET['others']) ? $_GET['others'] : '' ?>

<form>
	Seleccione la edición de Guadalinex:&nbsp;
	<select name="edition">
		<!-- <option value="">[Todas]</option> -->
		<? printOptionEdition($edition) ?>
	</select>
	<? if(basename($_SERVER['SCRIPT_NAME']) == 'result.php'): ?>
		&nbsp;|&nbsp;
		Elija la secci&oacute;n:&nbsp;
		<select name="section">
			<? if($others): ?>
			<option value="">[Sin clasificar]</option>
			<? printOptionNoClassified($edition);
			   else: ?>
			<option value="">[Secci&oacute;n]</option>
			<? printOptionSections(); 
			   endif; ?>
		</select>
		<input type="hidden" name="package" value="<?= isset($_GET['package']) ? $_GET['package'] : '' ?>" />
		<input type="hidden" name="version" value="<?= isset($_GET['version']) ? $_GET['version'] : '' ?>" />
		<input type="hidden" name="maintainer" value="<?= isset($_GET['maintainer']) ? $_GET['maintainer'] : '' ?>" />
		<input type="hidden" name="dependes" value="<?= isset($_GET['dependes']) ? $_GET['dependes'] : '' ?>" />
		<input type="hidden" name="description" value="<?= isset($_GET['description']) ? $_GET['description'] : '' ?>" />
		<? if($others): ?>
		<input type="hidden" name="others" value="1" />
		<? 
		   endif;
	   endif; 
	?>
	&nbsp;
	<input type="submit" value="Cambiar" class="button" />
</form>