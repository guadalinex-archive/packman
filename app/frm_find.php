<form action="result.php">
	<table style="margin: 5px 0 0 5px" cellspacing="5">
		<tr>
			<td class="field_name">Paquete:&nbsp;&nbsp;</td>
			<td class="field_input"><input type="text" name="package" class="long" /></td>
		</tr>
		<tr>
			<td class="field_name">Versi&oacute;n:&nbsp;&nbsp;</td>
			<td class="field_input"><input type="text" name="version" class="short" /></td>
		</tr>
		<tr>
			<td class="field_name">Secci&oacute;n:&nbsp;&nbsp;</td>
			<td class="field_input">
				<select name="section">
					<option value=""></option>
					<? printOptionSections() ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="field_name">Responsable:&nbsp;&nbsp;</td>
			<td class="field_input"><input type="text" name="maintainer" class="long" /></td>
		</tr>
		<tr>
			<td class="field_name">Dependencias:&nbsp;&nbsp;</td>
			<td class="field_input"><input type="text" name="dependes" class="long" /></td>
		</tr>
		<tr>
			<td class="field_name" valign="top">Descripci&oacute;n:&nbsp;&nbsp;</td>
			<td class="field_input"><textarea name="description" class="long" style="height: 70px"></textarea></td>
		</tr>
		<tr>
			<td><input type="hidden" name="edition" value="<?= $edition ?>" /></td>
			<td>
				<input type="submit" value="Buscar" class="button" />
				<input type="reset" value="Borrar" class="button" />
			</td>
		</tr>
	</table>
</form>