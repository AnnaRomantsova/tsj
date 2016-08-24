<?php

/**
 * ������ c ������������ <br>
 *  T - ����������� ������ �� �������� <br>
 *  O - ����������� �� ���� sort <br>
 *  <br>
 * class BT_O extends BT <br>
 * �������� ������ BT_O,BO,BT,B
 * 
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 * 
 */

include_once('class.BT.php');
include_once('class.BO.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BT_O_ {
	
 /**
  * ��������: ��������� ����� ������
  * @param BT_O $_this
  * @param array $values �����.������ �������� �����
  * @param int $parent id ������������ ������
  * @param bool $last ��������� ������ ��������� ��� ���
  */
 function saveNewRecord(&$_this,&$values,$parent,$last = null) {
 	 $_this->setRecordSort($values,$last);
     return BT_::saveNewRecord(&$_this,&$values,$parent);
 }

 /**
  * ��������: ��������� ������ ����
  * @param BT_O $_this
  * @param int $id id ����������� ������
  */
 function upRecord(&$_this,$id) {
    $r = new Select($_this->db,'select sort,parent from '.$_this->table.' where id="'.$id.'"');
    if ($r->next_row()) 
        record_up($_this->table,$r->result('sort'),' ( parent="'.$r->result('parent').'") ');
    $r->unset_();
 }
 
 /**
  * ��������: �������� ������ ����  :)
  * @param BT_O $_this
  * @param int $id id ���������� ������  :)
  */
 function downRecord(&$_this,$id) {
    $r = new Select($_this->db,'select sort,parent from '.$_this->table.' where id="'.$id.'"');
    if ($r->next_row()) 
        record_down($_this->table,$r->result('sort'),' ( parent="'.$r->result('parent').'") ');
    $r->unset_();
 }
 
 /**
  * �������� ��������� ��������� ������� � �������
  * @param BT_O $_this 
  * @return array
  */
 function &getParamMngr(&$_this) {
 	$param = &BT_::getParamMngr($_this);
 	$param2 = &BO_::getParamMngr($_this);
 	$param = &array_merge($param,$param2);
 	return $param;
 }
 
 /**
  * ���������� "�������" �� ��������� ���������� $_GET
  * @param BT_O $_this 
  * @param string $location ��� ������������� ��������� �������� �� ?event=1&'.$location 
  * @return bool ��������� ������� ��� ���
  */
 function createEvent(&$_this,$location = '') {
  /// !!! ������� �����	!!!
   // ���������� ������
   if  (isset($_GET['save_new'])) {
     $id = $_this->saveNewRecord($_POST,$_GET['save_new'],$_GET['save_last']);
     go('?event=1&'.$location);
   }
   elseif(BT_::createEvent($_this,$location)); 
   elseif(BO_::createEvent($_this,$location)); 
   else 
		return false;
   return true;
 }

 
	
}


/**
 * ������ �� ����������� ������� c ������������ <br> 
 *  T - ����������� ������ �� �������� <br>
 *  O - ����������� �� ���� sort
 */
class BT_O extends BT {

 function initBT_O(&$db,$_name = null,$_caption =null,$_table = null) {
	$this->initBT(&$db,$_name,$_caption,$_table);
 }
  
 function saveNewRecord(&$values,$parent,$last = null) {
 	return BT_O_::saveNewRecord($this,$values,$parent,$last);
 }
 
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
 
 function setRecordSort(&$values,$last = null) {
 	BO_::setRecordSort($this,$values,$last);
 } 
 
 // upRecord - �������������

 // downRecord - �������������

 // saveNewRecord - �������������
 
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

 
///--------------- /BO
	
}

?>