<? 
	include 'header.php';
	delParam($_SERVER['QUERY_STRING'], 'idpack');
	delParam($_SERVER['QUERY_STRING'], 'download');
	delParam($_SERVER['QUERY_STRING'], 'install');
	$pack = getPackage();
?>

<div id="section">
	<div style="margin: 5px 0 5px 5px">
		<strong><a href="index.php">Inicio</a></strong>
		&nbsp;-&nbsp;
		<strong><a href="find.php?edition=<?= $_GET['edition'] ?>">B&uacute;squeda de paquetes</a></strong>
		&nbsp;-&nbsp;
		<strong><a href="result.php?<?= $_SERVER['QUERY_STRING'] ?>">Resultado de la b&uacute;squeda</a></strong>
		&nbsp;-&nbsp;
		Ficha del paquete
	</div>
</div><br />
<div id="body">
	<div style="margin: 15px 15px 0 15px">
		<div class="table datas">
			<div class="htable"><strong>Datos principales</strong></div>
			<div class="btable">
				<table cellspacing="5">
					<tr>
						<td></td>
						<td>
							<?
								if($pack['id_edition'] > 3)
									$href = 'apt://' . $pack['pack'];
								else
									$href = '../php/apt.deb.php?idpack=' . $_GET['idpack'];
							?>
							<a href="<?= $href ?>" class="download"><strong>Instalar paquete</strong></a>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="<?= $pack['deb'] ?>" class="download"><strong>Descargar fichero</strong></a>
						</td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Edici&oacute;n&nbsp;&nbsp;[<font color="#ffaf12">distro</font>]:</td>
						<td><?= $pack['edit'] ?>&nbsp;&nbsp;[<font color="#dd8d00"><?= $pack['distro'] ?></font>]</td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Paquete&nbsp;&nbsp;[<font color="#ffaf12">versi&oacute;n</font>]:</td>
						<td><?= $pack['pack'] ?>&nbsp;&nbsp;[<font color="#dd8d00"><?= $pack['version'] ?></font>]</td>
					</tr>
					<tr>
						<td style="font-weight: bold;" valign="top">Descripci&oacute;n:</td>
						<td><?= nl2br(str_replace("\n.\n", "<br />\n", htmlentities($pack['description']))) ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Tama&ntilde;o&nbsp;&nbsp;[<font color="#ffaf12">instalado</font>]:</td>
						<td><?= round($pack['size']/1024, 2) ?> Kb<?= !empty($pack['installed_size']) ? '&nbsp;&nbsp;[<font color="#dd8d00">' . $pack['installed_size'] . '</font>]' : '' ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Categor&iacute;a:</td>
						<td><?= getCategory($pack['sec']) ?></td>
					</tr>
					<tr>
						<td colspan="2"><hr></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Ruta:</td>
						<td><?= $pack['filename'] ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Secci&oacute;n:</td>
						<td><?= $pack['sec'] ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Responsable:</td>
						<td><?= $pack['maintainer'] ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;" valign="top">Dependencias:</td>
						<td><?= $pack['dependes'] ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Conflictos:</td>
						<td><?= $pack['conflicts'] ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Recomendaciones:</td>
						<td><?= $pack['recommends'] ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Sugerencias:</td>
						<td><?= $pack['suggests'] ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">MD5:</td>
						<td><?= $pack['md5'] ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<? include 'footer.php' ?>