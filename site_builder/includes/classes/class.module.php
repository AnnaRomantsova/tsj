<?php

/**
 * class Module
 * @package BACK
 * @todo �������������� � ����� �� ������� �������� ������ ��� ������ � �������,<br> ����������� �� �� ��������� Module
 * @uses Db
 * 
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 */
class Module {
	
 /**
  * @var Db
  */
 var $db;
 var $name;
 var $caption;
	
 function Module(&$_db,$_name=null,$_caption=null) {
	$this->initModule();
 }
 
 function initModule(&$_db,$_name=null,$_caption=null) {
	$this->db = &$_db;
	$this->name = $_name;
	$this->caption = $_caption;
 }
 
}

?>