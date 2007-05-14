<?
/**
 * Módulo apt.deb.php
 * Lanza la descarga del fichero paquete.apt.deb que recoge el xapi
 * para la instalación de éste vía apt-get
 * 
 * @author Junta de Andalucía <devmaster@juntadeandalucia.es>
 * @coder Francisco javier Ramos Álvarez <fran.programador@gmail.com>
 * @license GPL
 * @version 1.0
 */
	include '../php/config.php';
	include '../php/connect.php';
	include '../php/function.php';

	$pack = getPackage();
	header('Content-type: application/x-dpkg');
	header('Content-disposition: inline; filename=' . $pack['pack'] . '.apt.deb');
?>