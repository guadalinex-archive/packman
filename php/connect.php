<?
/**
 * Módulo connect.php
 * Fichero de conexión con la base de datos
 * 
 * @author Junta de Andalucía <devmaster@juntadeandalucia.es>
 * @coder Francisco javier Ramos Álvarez <fran.programador@gmail.com>
 * @license GPL
 * @version 1.0
 */
 
	mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_select_db($dbname);
?>