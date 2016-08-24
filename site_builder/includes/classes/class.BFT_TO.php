<?php

/**
 * ������ c ������������ <br> 
 *  F - ������� �������������� ����� <br> 
 *  T - ����������� ������ �� �������� <br>
 *  O - ����������� �� ���� sort <br>
 *  <br>
 * class BFT_TO extends BF_T <br>
 * �������� ������ BFT_TO,BF_T,BF,BO,BT_O,BT,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 * 
 */

include_once('class.BF_T.php');
include_once('class.BT_O.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BFT_TO_ {
	
 /**
  * ��������: ��������� ����� ������
  * @param BFT_TO $_this
  * @param array $values �����.������ �������� �����
  * @param int $parent id ������������ ������
  * @param bool $last ��������� ������ ��������� ��� ���
  */
 function saveNewRecord(&$_this,&$values,$parent,$last = null) {
	 $id = BT_O_::saveNewRecord($_this,$values,$parent,$last);
	 $_this->uploadFiles($values,$id);
     return $id;
 }
 
}

/**
 * ������ �� ����������� ������� c ������������ <br> 
 *  F - ������� �������������� ����� <br>
 *  O - ����������� �� ���� sort <br>
 *  T - ����������� ������ �� ��������
 */
class BFT_TO extends BF_T {
 
 function initBFT_TO(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
	$this->initBF_T($db,$_name,$_caption,$_table);
 }

 function saveNewRecord(&$values,$parent,$last = null) {
 	return BFT_TO_::saveNewRecord($this,$values,$parent,$last);
 }

///--------------- BT_O 

 // saveNewRecord - �������������
 
 function upRecord($id) {
 	BT_O_::upRecord($this,$id);
 }

 function downRecord($id) {
 	BT_O_::downRecord($this,$id);
 }
 
 function &getParamMngr() {
   	return BT_O_::getParamMngr($this);
 }
 
 function createEvent($location = '') {
 	return BT_O_::createEvent($this,$location);
 }
 
 ///--------------- BO 
	 
	 // upRecord - �������������
	
	 // downRecord - �������������
	
	 // saveNewRecord - �������������
	 
	 function setRecordSort(&$values,$last = null) {
	 	BO_::setRecordSort($this,$values,$last);
	 } 	 
	 
	 function addButtonsSort(&$main,&$param) {
	 	BO_::addButtonsSort($this,$main,$param);
	 }
	 
	 function addButtons(&$main,&$param) {
	 	BO_::addButtons($this,$main,$param);
	 }
	 
	 function setValidSort(&$r,$reSelect = true) {
	 	BO_::setValidSort($this,$r,$reSelect);
	 }
	 
	 // createEvent - �������������
	 
	 // getParamMngr - �������������
	 
	 function addSubs(&$main,&$r,&$param,$field = 'records') {
	   	BO_::addSubs($this,$main,&$r,&$param,$field);
	 }

 ///--------------- BO 
 
///--------------- /BT_O 

 
}
?>