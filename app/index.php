<? include 'header.php' ?>
<div id="section">
	<div style="margin: 5px 0 5px 5px">Inicio</div>
</div>
<div id="body">
	<div style="margin: 15px 0 0 15px" id="mirror">
		<img src="http://www.guadalinex.org/uploads/img44d2fa155a9e0.png" align="right" alt="Andatuz presenta..." />
		<ul>
			<li><strong style="font-size: 16px"><a href="find.php" class="deb">Packman</a></strong> [Buscador de paquetes en los repositorios oficiales de Guadalinex]</li>
			<li><strong style="font-size: 16px"><a href="http://www.guadalinex.org/guadapedia/index.php/Normas_para_los_Mirrors_de_Guadalinex" class="deb" target="_blank">Informaci&oacute;n Mirrors</a></strong> [Normas a seguir para los Mirrors de Guadalinex]</li>
		</ul><br />
		<strong>Repositorios:</strong>
		<ul>
			<?
				$sourcelist = getSourceList();
				foreach($sourcelist as $edition => $debs){
					echo "<li><strong>$edition</strong></li>";
					echo '<ul>';
					foreach($debs as $info)
						echo '<li>deb <a href="' . $info[0] . '" class="deb">' . $info[0] . '</a> ' . $info[1] . ' ' . $info[2] . '</li>';
					
					echo '</ul>';
				}
			?>
		</ul>
	</div>
</div>
<? include 'footer.php' ?>