<?
	if(!empty($_GET['package'])){
		
		include 'php/config.php';
		include 'php/connect.php';
		include 'php/function.php';
		
		$res = getPackage2($_GET['package']);
		if($res){
			$pack = mysql_fetch_array($res);
			header('location: app/data.php?package=' . $_GET['package'] . '&edition=' . $pack['id_edition'] . '&idpack=' . $pack['id_package']);
		}
		else
			header('location: app/');
	}
	else
		header('location: app/');
?>