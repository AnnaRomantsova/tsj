<?php

/**
 * ������ c ������������ <br> 
 *  S - ��������� id � ������ <br>
 * <br>
 * class BS extends B <br>
 * �������� ������ BS,B
 * 
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 * 
 */

include_once('class.B.php');

/**
 * @uses Select
 * @uses outTree
 */
class BS_ {
	
 /**
  * ���������� "�������" �� ��������� ���������� $_GET
  * @param BS $_this 
  * @param string $location ��� ������������� ��������� �������� �� ?event=1&'.$location 
  * @return bool ��������� ������� ��� ���
  */	
 function createEvent(&$_this,$location = '') {	
 	
 // ��������� ������ � ������
     if ( isset($_GET['cut']) ) {
        $_this->cutRecord($_GET['cut']);
        header('Location: ?event=1&'.$location);
     }
     else 
     	return B_::createEvent($_this,$location);
     return true;	
 }		
	
 /**
  * ��������: "��������" ������ - ����������/������� �� ������
  * @param BS $_this
  * @param int $id id "����������" ������
  * @param bool $justDel ������ ������� �� ������
  */
 function cutRecord(&$_this,$id,$justDel = false) {
	if ( isset($_SESSION['idCuts']) && isset($_SESSION['idCuts'][$_this->table]) && isset($_SESSION['idCuts'][$_this->table][$id]) ) 
	     	unset($_SESSION['idCuts'][$_this->table][$id]);
	     	
	else {
		if ($justDel) return;
		if (!isset($_SESSION['idCuts'])) {
			$_SESSION['idCuts'] = array();
			if (!isset($_SESSION['idCuts'][$_this->table]))
				$_SESSION['idCuts'][$_this->table] = array();
		}
		$_SESSION['idCuts'][$_this->table][$id]=true;
	}
 }
	
 /**
  * ��������� ������ "���������" � ������ ���������<br>
  * ������ ����� ���� ��������� ��� � ���� ������ ��������� ��� ������� ������<br>
  * ��� � � ������ ����� ������
  * @param BS $_this
  * @param outTree $main ������ ��������� ��� �����
  * @param array $param ��������� ��������� � ������
  */	
 function addButtonsCut(&$_this,&$main,$id) {
	$ot = new outTree();
	if ( isset($_SESSION['idCuts']) && isset($_SESSION['idCuts'][$_this->table]) && isset($_SESSION['idCuts'][$_this->table][$id]) )
		$ot->addField('D','D');
	$main->addField('butCut',&$ot);
 }
 
 /**
  * ��������� ������ �������� � ������ ���������<br>
  * ������ ����� ���� ��������� ��� � ���� ������ ��������� ��� ������� ������<br>
  * ��� � � ������ ����� ������
  * @param BS $_this
  * @param outTree $main ������ ��������� ��� �����
  * @param array $param ��������� ��������� � ������
  */	
 function addButtons(&$_this,&$main,&$param) {
 	B_::addButtons($_this,$main,$param);
 	$_this->addButtonsCut($main,$param['id']);
 }
 

 /**
  * ��������: ������� ������
  * @param BS $_this
  * @param int $id id ��������� ������
  * @param Select $r ������ � ��������� �������
  * @param bool $qd ������� ���� ������ ��� ������������ "����������������� ����������"
  * @return int ���������� ��������� �������
  */	
 function deleteRecord(&$_this,$id,$r = null,$qd = true) {
	B_::deleteRecord($_this,$id,$r,$qd);
	$_this->cutRecord($id,true);
 }
 
 /**
  * "���������" "����������" ������<br>
  * ���������� ����� ���������� � ��������, ������������ � ������, ����� ��������
  * @param BS $_this
  * @param array $param ��������� ���������
  */	
 function pastAction(&$_this,&$param) {
 }
 
 /**
  * ��������: "���������" "����������" ������<br>
  * ���������� ��������� ��������, ����� ������� ������
  * @param BS $_this
  * @param array $param ��������� ���������
  */	
 function pastRecords(&$_this,&$param) {
	if (isset($_SESSION['idCuts']) && isset($_SESSION['idCuts'][$_this->table])) {
		$_this->pastAction($param);
		unset($_SESSION['idCuts'][$_this->table]);
	}	
 }
	
}

/**
 * ������ �� ����������� ������� c ������������ <br> 
 *  S - ��������� id � ������
 */
class BS extends B {

 function initBS(&$db,$_name = null,$_caption =null,$_table = null) {
	$this->initB(&$db,$_name,$_caption,$_table);
 }
 
 function addButtonsCut(&$main,$id) {
 	BS_::addButtonsCut($this,&$main,$id);
 }
 
 function addButtons(&$main,&$param) {
 	BS_::addButtons($this,$main,$param);
 }
 
 function deleteRecord($id,$r = null,$qd = true) {
 	BS_::deleteRecord($this,$id,$r,$qd);
 }
 
 function cutRecord($id,$justDel = false) {
 	BS_::cutRecord($this,$id,$justDel);
 }
 
 function pastAction(&$param) {
 	BS_::pastAction($this,$param);
 }
 
 function pastRecords(&$param) {
 	BS_::pastRecords($this,$param);
 }
 
 function createEvent($location = '') {
 	return BS_::createEvent($this,$location);
 }
 	
} 

?>
