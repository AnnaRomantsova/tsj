<?php

/**
 * ������ c ������������ <br> 
 *  F - ������� �������������� ����� <br> 
 *  T - ����������� ������ �� �������� <br>
 *  <br>
 * class BF_T extends BF <br>
 * �������� ������ BF_T,BT,BF,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 * 
 */

include_once('class.BF.php');
include_once('class.BT.php');
 
/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BF_T_ {
	
 /**
  * ��������: ��������� ����� ������
  * @param BF_T $_this
  * @param array $values �����.������ �������� �����
  * @param int $parent id ������������ ������
  */	
 function saveNewRecord(&$_this,&$values,$parent) {
     $values['parent'] = $parent;
     return BF_::saveNewRecord(&$_this,&$values);
 }
 
 /**
  * ��������� ������ �������������� ������<br>
  * ��������� ���������
  * @param BF_T $_this 
  * @param outTree $main ������ ��������������
  * @param int $id id ���������� ������
  * @param string $table ��� �������, ��� �������� ������������ ������
  * @return string ���� ������� ����������
  */ 
 function addIfcEditRecord(&$_this,&$main,$id,$table = null) {
     if ($_FILENAME = BT_::addIfcEditRecord($_this,$main,$id,$table)) {
 	 	$_this->addFiles($main);
 	 }
     return $_FILENAME;
 }
 
}

/**
 * ������ �� ����������� ������� c ������������ <br> 
 *  F - ������� �������������� ����� <br>
 *  T - ����������� ������ �� ��������
 */
class BF_T extends BF {

 function initBF_T(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
	$this->arFiles = &$arFiles;
	$this->initBF($db,$_name,$_caption,$_table);
 }
 
 function saveNewRecord(&$values,$parent) {
 	return BF_T_::saveNewRecord($this,$values,$parent);
 }
 
 function addIfcEditRecord(&$main,$id,$table = null) {
 	return BF_T_::addIfcEditRecord($this,$main,$id,$table);
 }

 
///--------------- BT
  
 // saveNewRecord - �������������

 function initPath(&$br,&$main,&$r,$with_end = false,$with_last = true ) { 
 	BT_::initPath($this,$br,$main,$r,$with_end,$with_last);
 }

 function addIfcAddRecord(&$main,$parent,$table = null) {
 	return BT_::addIfcAddRecord($this,$main,$parent,$table);
 }
 
 // addIfcEditRecord - �������������
 
 function &getParamMngr() {
   	return BT_::getParamMngr($this);
 }
 
 function addManager(&$main) {
 	return BT_::addManager($this,$main);
 }

///--------------- /BT  
 
}

?>