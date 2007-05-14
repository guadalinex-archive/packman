<?
/**
 * Módulo functions.php
 * Funcionalidad de toda la aplicación.
 * 
 * @author Junta de Andalucía <devmaster@juntadeandalucia.es>
 * @coder Francisco javier Ramos Álvarez <fran.programador@gmail.com>
 * @license GPL
 * @version 1.0
 */
 
/**
 * Lista las ediciones en un control select.
 * 
 * @access public
 * @param integer $edition Edición preseleccionada
 */
function printOptionEdition($edition = 0){
	$sql = 'select * from edition';
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res)){
		echo $edition;
		$selected = ($edition == $row['id_edition'] ? 'selected="true"' : '');
		echo '<option value="' . $row['id_edition'] . '" ' . $selected . '>' . $row['name'] . '</option>' . "\n";
	}
}

/**
 * Lista las categorías de paquetes. Éstas fueron previamente almacenadas
 * en la base de datos donde se indica un filtro para cada categoría,
 * por ejemplo: Administración -> admin (section).
 * 
 * @access public
 * @param integer $edition Categorías según la edición
 */
function printListTypes($edition = 0){
	if(!$edition){
		$sql = 'select s.id_section, s.name, count(s.name) as total ';
		$sql .= "from section s inner join package p on (s.id_section = substring_index(p.section, '/', -1)) ";
	}
	else{
		$sql = 'select s.id_section, s.name, count(s.name) as total ';
		$sql .= "from section s inner join package p on (s.id_section = substring_index(p.section, '/', -1)) ";
		$sql .= 'inner join relation r on (p.id_relation = r.id_relation) ';
		$sql .= 'inner join distribution d on (r.id_distribution = d.id_distribution) ';
		$sql .= 'inner join repository re on (d.id_repository = re.id_repository) ';
		$sql .= "where re.id_edition = $edition ";
	}
	$sql .= 'group by s.name order by s.name';
	
	$res = mysql_query($sql);
	echo "<ul>\n";
	
	$param = $edition ? '&edition=' . $edition : '';
	while($row = mysql_fetch_array($res))
		echo '<li><a href="result.php?section=' . $row['id_section'] . $param . '">' . utf8_decode($row['name']) . '</a>&nbsp;&nbsp;[<font color="#dd8d00">' . $row['total'] . "</font>]</li>\n";
	
	$total = 0;
	$res = getNotClassified($edition);
	while($row = mysql_fetch_array($res))
		$total += $row['total'];
	echo '<li><a href="result.php?others=1' . $param . '">Sin clasificar</a>&nbsp;&nbsp;[<font color="#ffaf12">' . $total . '</font>]</li>' . "\n";
	echo "</ul>\n";
}

/**
 * Obtiene las ediciones que no fueron clasificadas (no se introdujo filtro
 * alguno en la base de datos).
 * 
 * @access public
 * @param integer $edition Categorías según la edición
 * @return #query Devuelve el nombre de la sección y el todal de paquetes que tiene
 */
function getNotClassified($edition = 0){
	# $condition = "not (substring_index(p.section, '/', -1) in (select id_section from section)) ";
	$condition = 's.id_section is null';
	
	$sql = 'select p.section, count(p.section) as total from package p ';
	$sql .= "left join section s on (substring_index(p.section, '/', -1) = s.id_section) ";
	
	if(!$edition)
		$sql .= "where $condition ";
	else{
		$sql .= 'inner join relation r on (p.id_relation = r.id_relation) ';
		$sql .= 'inner join distribution d on (r.id_distribution = d.id_distribution) ';
		$sql .= 'inner join repository re on (d.id_repository = re.id_repository) ';
		$sql .= "where re.id_edition = $edition and $condition ";
	}
	
	$sql .= 'group by p.section order by p.section';
		
	return mysql_query($sql);
}

/**
 * Igual que la anterior, pero optimizada con subconsultas.
 * 
 * @access public
 * @param integer $edition Categorías según la edición
 * @return #query Devuelve el nombre de la sección y el todal de paquetes que tiene
 */
function getNotClassified2($edition = 0){
	$condition = "not (substring_index(p.section, '/', -1) in (select id_section from section)) ";
	
	$sql = 'select sec, sum(total) as suma ';
	$sql .= "from (select substring_index(p.section, '/', -1) as sec, count(p.section) as total ";
	$sql .= 'from package p ';
	
	if(!$edition)
		$sql .= "where $condition ";
	else{
		$sql .= 'inner join relation r on (p.id_relation = r.id_relation) ';
		$sql .= 'inner join distribution d on (r.id_distribution = d.id_distribution) ';
		$sql .= 'inner join repository re on (d.id_repository = re.id_repository) ';
		$sql .= "where re.id_edition = $edition and $condition ";
	}
	
	$sql .= 'group by p.section order by p.section) p group by sec order by sec';
		
	return mysql_query($sql);
}

/**
 * Igual que la primera, optimizada sin subconsultas.
 * 
 * @access public
 * @param integer $edition Categorías según la edición
 * @return #query Devuelve el nombre de la sección
 */
function getNotClassified3($edition = 0){
	$condition = 's.id_section is null';
	
	$sql = "select distinct substring_index(p.section, '/', -1) as sec ";
	$sql .= 'from package p left join section s on ';
	$sql .= "(substring_index(p.section, '/', -1) = s.id_section) ";
	
	if(!$edition)
		$sql .= "where $condition ";
	else{
		$sql .= 'inner join relation r on (p.id_relation = r.id_relation) ';
		$sql .= 'inner join distribution d on (r.id_distribution = d.id_distribution) ';
		$sql .= 'inner join repository re on (d.id_repository = re.id_repository) ';
		$sql .= "where re.id_edition = $edition and $condition ";
	}
	
	$sql .= 'order by sec';
		
	return mysql_query($sql);
}

/**
 * Lista las secciones (campo section) en un control select.
 * 
 * @access public
 */
function printOptionSections(){
	$sql = "select distinct substring_index(section, '/', -1) sec from package order by sec";
	
	$section = isset($_GET['section']) ? $_GET['section'] : '';
	
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res)){
		$selected = '';
		if($section == $row['sec'])
			$selected = 'selected="true"';
		echo '<option value="' . $row['sec'] . '" ' . $selected . '>' . $row['sec'] . '</option>' . "\n";
	}
}

/**
 * Lista las secciones no clasificadas en un control select.
 * 
 * @access public
 * @param integer $edition Categorías según la edición
 */
function printOptionNoClassified($edition = 0){
	# $res = getNotClassified2($edition);
	$res = getNotClassified3($edition);
	
	$section = isset($_GET['section']) ? $_GET['section'] : '';
	
	while($row = mysql_fetch_array($res)){
		$selected = '';
		if($section == $row['sec'])
			$selected = 'selected="true"';
		echo '<option value="' . $row['sec'] . '" ' . $selected . '>' . $row['sec'] . '</option>' . "\n";
	}
}

/**
 * Devuelve el resultado de una búsqueda.
 * 
 * @access public
 * @param integer $edition Categorías según la edición
 * @return #query Devuelve los datos más importantes de un paquete
 */
function getResultFind($limit = ''){
	$sql = "select p.id_package, p.name, p.version, p.description, p.size, p.installed_size, substring_index(p.section, '/', -1) sec ";
	$sql .= 'from package p ';
	
	if(isset($_GET['others']) and $_GET['others'])
		$sql .= "left join section s on (substring_index(p.section, '/', -1) = s.id_section) ";
	
	if(!empty($_GET['edition'])){
		$sql .= 'inner join relation r on (p.id_relation = r.id_relation) ';
		$sql .= 'inner join distribution d on (r.id_distribution = d.id_distribution) ';
		$sql .= 'inner join repository re on (d.id_repository = re.id_repository) ';
		$sql .= 'where re.id_edition = ' . $_GET['edition'] . ' ';
	}
	else
		$sql .= 'where 1 ';
	
	# montamos la condición según los criterios introducidos
	
	if(isset($_GET['others']) and $_GET['others'])
		$condition = 'and s.id_section is null';
	
	if(!empty($_GET['package']))
		$sql .= "and p.name like '%" . $_GET['package'] . "%' ";
	if(!empty($_GET['version']))
		$sql .= "and p.version like '%" . $_GET['version'] . "%' ";
	if(!empty($_GET['section']))
		$sql .= "and p.section like '%" . $_GET['section'] . "%' ";
	if(!empty($_GET['maintainer']))
		$sql .= "and p.maintainer like '%" . $_GET['maintainer'] . "%' ";
	if(!empty($_GET['dependes']))
		$sql .= "and p.dependes like '%" . $_GET['dependes'] . "%' ";
	if(!empty($_GET['description']))
		$sql .= "and p.description like '%" . $_GET['description'] . "%' ";
		
	$sql .= 'order by p.name ' . $limit;

	return mysql_query($sql);
}

/**
 * Elimina un parámetro de una QUERY_STRING
 * 
 * @access public
 * @param string $request (in/out)
 * @param stirng $param Parámetro a eliminar
 */
function delParam(&$request, $param){
	$keyvalue = explode('&', $request);
	for($i = 0; $i < count($keyvalue); $i++)
		if(ereg("^$param=", $keyvalue[$i])) unset($keyvalue[$i]);
	$request = implode('&', $keyvalue);
}

/**
 * Limpia toda la QUERY_STRING
 * 
 * @access public
 * @param string $request (in/out)
 */
function cleanQueryString(&$request){
	delParam($request, 'pag');
	delParam($request, 'first');
	delParam($request, 'previous');
	delParam($request, 'next');
	delParam($request, 'last');
	delParam($request, 'reload');
	delParam($request, 'numpags');
	delParam($request, 'regspag');
}

/**
 * Devuelve toda la información de un paquete, cuyo identificador
 * se envio por get.
 * 
 * @access public
 * @return array Array asociativo con la información
 */
function getPackage(){
	$sql = 'select p.name pack, p.version, p.size, p.installed_size, concat(re.url, p.filename) deb, ';
	$sql .= 'p.filename, p.description, e.id_edition, e.name edit, d.name distro, ';
	$sql .= "substring_index(p.section, '/', -1) sec, p.maintainer, p.dependes, p.conflicts, ";
	$sql .= 'p.recommends, p.suggests, p.md5 from package p ';
	$sql .= 'inner join relation r on (p.id_relation = r.id_relation) ';
	$sql .= 'inner join distribution d on (r.id_distribution = d.id_distribution) ';
	$sql .= 'inner join repository re on (d.id_repository = re.id_repository) ';
	$sql .= 'inner join edition e on (re.id_edition = e.id_edition) ';
	$sql .= 'where p.id_package = ' . $_GET['idpack'];
	
	$res = mysql_query($sql);
	if(mysql_num_rows($res))
		return mysql_fetch_array($res);
	else
		return null;
}

/**
 * Devuelve el nombre de la Categoría según el campo section
 * 
 * @access public
 * @param string $section
 * @return string
 */
function getCategory($section){
	$section = basename($section);
	$sql = "select name from section where id_section = '$section'";
	$res = mysql_query($sql);
	if(mysql_num_rows($res)){
		return mysql_result($res, 0, 'name');
	}
	else
		return 'Sin clasificar';
}

/**
 * Nos devolverá las urls que componen el source.list para cada distribución.
 * 
 * @access public
 * @return array Listado de urls deb
 */
function getSourceList(){
	$result = array();
	
	$sql = 'select e.name edit, r.url, d.name distro, d.id_distribution from edition e inner join ';
	$sql .= 'repository r on (e.id_edition = r.id_edition) inner join distribution d ';
	$sql .= 'on (r.id_repository = d.id_repository) order by e.id_edition desc, ';
	$sql .= 'd.id_distribution';
	
	$res = mysql_query($sql);
	$edition = '';
	$i = 0;
	while($row = mysql_fetch_array($res)){
		if($edition != $row['edit']){
			$edition = $row['edit'];
			$i = 0;
		}
		
		$result[$edition][$i] = array($row['url'], $row['distro']);
		
		$sql = 'select id_branch from relation where id_distribution = ' . $row['id_distribution'] . ' order by id_relation';
		$res2 = mysql_query($sql);
		$branches = array();
		while($b = mysql_fetch_array($res2))
			$branches[] = $b['id_branch'];
		
		$result[$edition][$i][] = implode(' ', $branches);
		$i++;
	}

	return $result;
}
?>