<form>
	<input type="submit" name="first" value="&lt;&lt;" class="button" />
	&nbsp;
	<input type="submit" name="previous" value="&lt;" class="button" />
	&nbsp;
	Pag.&nbsp;<input type="text" size="5" name="pag" class="vshort" value="<?= $pag ?>" />&nbsp;de&nbsp;<input type="text" size="5" name="numpags" class="vshort" readonly="true" value="<?= $numpags ?>" />
	&nbsp;
	<input type="submit" name="next" value="&gt;" class="button" />
	&nbsp;
	<input type="submit" name="last" value="&gt;&gt;" class="button" />&nbsp;
	&nbsp;|&nbsp;
	Mostrar:&nbsp;<input type="text" size="5" name="regspag" class="vshort" value="<?= $regspag ?>" />&nbsp;regs. por p&aacute;g.
	&nbsp;|&nbsp;
	<input type="submit" name="reload" value="Actualizar" class="button" />
	&nbsp;|&nbsp;
	Total de registros: <?= $allregs ?>
	<input type="hidden" name="package" value="<?= isset($_GET['package']) ? $_GET['package'] : '' ?>" />
	<input type="hidden" name="version" value="<?= isset($_GET['version']) ? $_GET['version'] : '' ?>" />
	<input type="hidden" name="section" value="<?= isset($_GET['section']) ? $_GET['section'] : '' ?>" />
	<input type="hidden" name="maintainer" value="<?= isset($_GET['maintainer']) ? $_GET['maintainer'] : '' ?>" />
	<input type="hidden" name="dependes" value="<?= isset($_GET['dependes']) ? $_GET['dependes'] : '' ?>" />
	<input type="hidden" name="description" value="<?= isset($_GET['description']) ? $_GET['description'] : '' ?>" />
	<input type="hidden" name="edition" value="<?= $edition ?>" />
	<? if(isset($_GET['others']) and $_GET['others']): ?>
		<input type="hidden" name="others" value="1" />
	<? endif; ?>
</form>