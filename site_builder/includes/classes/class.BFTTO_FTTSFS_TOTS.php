<?php

/**
 * ������ c ������������ <br> 
 *  F - ������� �������������� ����� <br> 
 *  T - ����������� ������ �� �������� <br>
 *  O - ����������� �� ���� sort <br>
 *  S - ��������� id � ������ <br>
 *  <br>
 * class BFTTO_FTTSFS_TOTS extends BFT_TO <br>
 * �������� ������ BFTTO_FTTSFS_TOTS,BS,BFT_TO,BF_T,BF,BO,BT_O,BT,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 * 
 */

include_once('class.BFT_TO.php');
include_once('class.BFT_TS_FS.php');
include_once('class.BTO_TS.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BFTTO_FTTSFS_TOTS_ {
	
 /**
  * ��������� ������ �������� � ������ ���������<br>
  * ������ ����� ���� ��������� ��� � ���� ������ ��������� ��� ������� ������<br>
  * ��� � � ������ ����� ������
  * @param BFTTO_FTTSFS_TOTS $_this
  * @param outTree $main ������ ��������� ��� �����
  * @param array $param ��������� ��������� � ������
  */ 
 function addButtons(&$_this,&$main,&$param) {
 	BO_::addButtons($_this,$main,$param);
 	$_this->addButtonsCut($main,$param['id']);
 }
 	
}

/**
 * ������ �� ����������� ������� c ������������ <br> 
 *  F - ������� �������������� ����� <br>
 *  S - ��������� id � ������ <br>
 *  O - ����������� �� ���� sort <br>
 *  T - ����������� ������ �� ��������
 */
class BFTTO_FTTSFS_TOTS extends BFT_TO {
 
 function initBFTTO_FTTSFS_TOTS(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
	$this->initBFT_TO($db,$_name,$_caption,$_table);
 }
 
 function addButtons(&$main,&$param) {
 	BFTTO_FTTSFS_TOTS_::addButtons($this,$main,$param);
 }
 
///--------------- BTO_TS 
 // addButtons - �������������
 
 function pastAction(&$param) {
 	BTO_TS_::pastAction($this,$param);
 }
 
 function createEvent($location = '') {
	return BTO_TS_::createEvent($this,$location);
 }
///--------------- /BTO_TS  

///--------------- BFT_TS_FS
	///--------------- BF_S 
	 function deleteRecord($id,$r = null,$qd = true) {
	 	BF_S_::deleteRecord($this,$id,$r,$qd);
	 }
	///--------------- /BF_S
	
	///--------------- BT_S 
		// pastAction - �������������
	///--------------- /BT_S 
///--------------- /BFT_TS_FS


///--------------- BTO_TS,BFT_TS_FS
	 
	///--------------- BF_S,BT_S
		///--------------- BS
		 
		 function addButtonsCut(&$main,$id) {
		 	BS_::addButtonsCut($this,&$main,$id);
		 }
		 
		 // addButtons - �������������
		 
		 // deleteRecord - �������������
		 
		 function cutRecord($id,$justDel = false) {
		 	BS_::cutRecord($this,$id,$justDel);
		 }
		 
		 // pastAction - �������������
		 
		 function pastRecords(&$param) {
		 	BS_::pastRecords($this,$param);
		 }
		 
		 // createEvent - �������������
		 
		///--------------- /BS 
	///--------------- /BF_S,BT_S
///--------------- /BTO_TS,BFT_TS_FS
 
 
}
?>