<? 
	include 'header.php';
	cleanQueryString($_SERVER['QUERY_STRING']);
	$edition = isset($_GET['edition']) ? $_GET['edition'] : 4;
?>

<div id="section">
	<div style="margin: 5px 0 5px 5px;">
		<strong><a href="index.php">Inicio</a></strong>
		&nbsp;-&nbsp;
		<strong><a href="find.php?edition=<?= $edition ?>">B&uacute;squeda de paquetes</a></strong>
		&nbsp;-&nbsp;
		Resultado de la b&uacute;squeda
	</div>
</div>
<br />
<div id="body">
	<div style="margin: 15px 15px 0 15px">
		<div id="selection">
			<? include 'frm_selection.php' ?>
		</div><br />
		<?
			$regspag = isset($_GET['regspag']) ? intval($_GET['regspag']) : $regspag;
			$regspag = empty($regspag) ? 50 : $regspag;
			
			$res = getResultFind();
			$allregs = mysql_num_rows($res);
			$numpags = ceil($allregs / $regspag);
			
			$pag = isset($_GET['pag']) ? intval($_GET['pag']) : 1;
			$pag = empty($pag) ? 1 : $pag;
			
			if(isset($_GET['first'])) $pag = 1;
			if(isset($_GET['previous'])) $pag--;
			if(isset($_GET['next'])) $pag++;
			if(isset($_GET['last'])) $pag = $numpags;
			
			$pag = $pag < 1 ? 1 : $pag;
			$pag = $pag > $numpags ? $numpags : $pag;
		?>
		<div class="pager">
			<? include 'frm_pager.php' ?>
		</div>
		<br />
		<table width="100%" id="result" cellspacing="2" cellpadding="0">
			<tr class="htable">
				<td><strong>Nombre&nbsp;&nbsp;[<font color="#ffaf12">versi&oacute;n</font>]</strong></td>
				<td><strong>Descripci&oacute;n</strong></td>
				<td align="center"><strong>Tama&ntilde;o (Kb)&nbsp;&nbsp;[<font color="#ffaf12">instalado</font>]</strong></td>
				<td align="center"><strong>Secci&oacute;n</strong></td>
			</tr>
			<?
				$limit = 'limit ' . ($pag-1)*$regspag . ", $regspag";
				if($res = getResultFind($limit)){
					$color = '';
					while($row = mysql_fetch_array($res)):
						$color = ($color == '#ffffff') ? '#eeeeee' : '#ffffff';
					?>
						<tr style="background-color: <?= $color ?>">
							<td><a href="data.php?<?= $_SERVER['QUERY_STRING'] ?>&pag=<?= $pag ?>&idpack=<?= $row['id_package'] ?>"><?= $row['name'] ?></a>&nbsp;&nbsp;[<font color="#dd8d00"><?= $row['version'] ?></font>]</td>
							<td><?= nl2br(str_replace("\n.\n", "<br />\n", htmlentities($row['description']))) ?></td>
							<td align="center"><?= round($row['size']/1024, 2) ?> Kb<?= !empty($row['installed_size']) ? '&nbsp;&nbsp;[<font color="#dd8d00">' . $row['installed_size'] . '</font>]' : '' ?></td>
							<td align="center"><?= $row['sec'] ?></td>
						</tr>
					<?
					endwhile;
				}
			?>
		</table>
		<br />
		<div class="pager">
			<? include 'frm_pager.php' ?>
		</div>
		<br />
	</div>
</div>

<? include 'footer.php' ?>