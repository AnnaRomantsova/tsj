<?php

/**
 * ������ c ������������ 
 *  O - ����������� �� ���� sort
 *  P - ������������� ��������� 
 * 
 * class BO_P extends BO
 * �������� ������ BP,BO,B 
 * 
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 * 
 */

include_once('class.BO.php');
include_once('class.BP.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Pager
 */
class BO_P_ {
	
 /**
  * @param BO_P $_this
  */	
 function &getParamMngr(&$_this) {
 	$param = &BP_::getParamMngr($_this);
 	$param2 = &BO_::getParamMngr($_this);
  	$param = &array_merge($param,$param2);
 	return $param;
 }		
	
}

/**
 * ������ �� ����������� ������� c ������������ <br> 
 *  O - ����������� �� ���� sort <br>
 *  P - ������������� ���������
 */
class BO_P extends BO {

 function initBO_P(&$db,$_name = null,$_caption =null,$_table = null) {
	$this->initBO(&$db,$_name,$_caption,$_table);
 }

 function &getParamMngr() {
   	return BO_P_::getParamMngr($this);
 }
 
///--------------- BP 
 
 function addRecords(&$main,&$param) {
 	BP_::addRecords($this,$main,$param);
 }
 
 // getParamMngr - �������������
 
 function getEvent($location = '') {
 	BP_::getEvent($this,$location);
 }
 
///--------------- /BP 
 	
} 

?>
