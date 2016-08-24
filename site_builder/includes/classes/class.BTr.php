<?php

/**
 * ��������� ���� ������ <br> 
 *  <br>
 * class BTr extends Module <br>
 *  <br>
 * class BSc extends BFTTO_FTTSFS_TOTS <br>
 * 
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 * 
 */

include_once('class.BFTTO_FTTSFS_TOTS.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BSc_ {
	
 /**
  * ��������� ������ �������� � ������ ���������<br>
  * ������ ����� ���� ��������� ��� � ���� ������ ��������� ��� ������� ������<br>
  * ��� � � ������ ����� ������
  * @param BSc $_this
  * @param outTree $main ������ ��������� ��� �����
  * @param array $param ��������� ��������� � ������
  */
 function addButtons(&$_this,&$main,&$param) {
	 if ( (1 != $param['id']) )	{ // ���� �� ������
	    $main->addField('butRedact','');    		
	    $_this->addButtonsCut($main,$param['id']);
	 }
	 
	 if (empty($param['root']))  { // ���� �� ������� �������
	 	$main->addField('butDelete','');
	    $main->addField('butPabl','');
	    $_this->addButtonsSort(&$main,&$param);
	 }
 }
 
 /**
  * ��������� ������ ��������� �������
  * @param BSc $_this 
  * @param outTree $main ������ ���������
  * @return string ���� ������� ���������
  */ 
 function addManager(&$_this,&$main) {
 	$param = &$_this->getParamMngr();
 	$_this->addRecords($main,$param);
	return 'manager.html';
 }
 
}

/**
 * ������ �� ����������� ������� "������" c ������������ <br> 
 *  F - ������� �������������� ����� <br>
 *  S - ��������� id � ������ <br>
 *  O - ����������� �� ���� sort <br>
 *  T - ����������� ������ �� ��������
 */
class BSc extends BFTTO_FTTSFS_TOTS {
	
  function addButtons(&$main,&$param) {
 	BSc_::addButtons($this,$main,$param);
  }
  
  function addManager(&$main) {
 	return BSc_::addManager($this,$main);
  }

}

/**
 * @uses Select
 * @uses outTree
 */
class BTr_ {
	
 /**
  * ��������: ������� "������"
  * @param BTr $_this 
  * @param int   $id     ����� "������" �������
  * @param int   $type   ��� �������:<br> 
  *                      0 - ��;<br> 
  *                      2 - ������ "����������"<br>
  *                      ����� ���������� ����� �������, ���������� �� ������������:<br>
  *                      �������� ������� � ������ = 3*2 = 6
  */
 function clearSection(&$_this,$id,$type = 0) {
    // �������� �����������
    if (0 == ($type%2)) 
    	$_this->deleteSections($id);
           	 	
 	
 }
 
 /**
  * ��������: ������� "������"
  * @param BTr $_this 
  * @param int $id id ���������� "�������"
  * @param Select $r ������ � ��������� �������
  * @param bool $qd ������� ���� ������ ��� ������������ "����������������� ����������"
  */
 function deleteSection(&$_this,$id,$r = null,$qd = true) {
 	$_this->Section->deleteRecord($id,$r,$qd);
	$_this->clearSection($id);
 }
 
 /**
  * ������� ��� "����������" � "�������"
  * @param BTr $_this 
  * @param int $id id ���������� "�������"
  * @param Select $r ������ � ��������� �������
  * @param bool $qd ������� ���� ������ ��� ������������ "����������������� ����������"
  */ 
 function deleteSections(&$_this,$id) {
    $r = new Select($_this->db,'select * from '.$_this->Section->table.' where parent="'.$id.'"');
    while ($r->next_row()) {
       $_this->deleteSection($r->result('id'),$r,false);
    }
    $r->unset_();
	$_this->db->query('delete from '.$_this->Section->table.' where parent="'.$id.'"');
 } 
 

 /**
  * ��������: ��������� ���������� ������<br>
  * ���������� ��������� ��������, ����� ������� ������
  * @param BTr $_this
  * @param array $param ��������� ���������
  */
 function pastRecords(&$_this,&$param) {
 	$_this->Section->pastRecords($param);
 }
 
 
 /**
  * ��������� ������ �������� � ������ ���������
  * @param BTr $_this
  * @param outTree $main ������ ���������
  * @param array $param ��������� ���������
  */ 
 function addActions(&$_this,&$main,&$param) {
  //���� ������ ���� � ����� - ������� �� �������� �� ���������.
	if ( 0 > $GLOBALS['br']->level) 
       header('Location: ?sct=1');
	 	
	$main->addField('actAddSection','');

	if (   isset($_SESSION['idCuts']) && 
	      ( $co = count($_SESSION['idCuts'][$_this->Section->table]))
	     )
		 $main->addField('actPast',$co);    		
		 
  // ���� ������������������� ���� �� ���� �� ��������
	if (     isset($main->actAddSection) 
	      ||  isset($main->actClear) 
	      ||  isset($main->actPast) 
	    )
	 	$main->addField('actions',''); 
 }
 
 /**
  * ��������� ������ ��������� �������
  * @param BTr $_this 
  * @param outTree $main ������ ���������
  * @return string ���� ������� ���������
  */ 
 function addManager(&$_this,&$main) {
 	$_GET['sct'] =  ( !empty($_GET['sct']) ? $_GET['sct'] : 1 );

 	$r = new Select($_this->db,'select * from '.$_this->Section->table.' where id="'.$_GET['sct'].'"');
    $GLOBALS['r']  = &$r;
    if ($r->next_row()) {
	    // ��������� �������� �������
   	    $r->addFields($main,$ar = array('name','id'));
	
		$br = new Brunch($_GET['sct'], $_this->Section->table, '', $_this->db);
	    $GLOBALS['br']  = &$br;
	    
		$_this->Section->initPath($br,$main,$r);
	 	$_this->Section->addButtons($main,$ar = array('id'=>$_GET['sct'],'root'=>1));
		$_this->Section->addManager($main);
	 		
	 	$_this->addActions($main,$param);
	 	
		return 'manager.html';
    }
 }
 
 /**
  * ���������� "�������" �� ��������� ���������� $_GET
  * @param BTr $_this 
  * @param string $location ��� ������������� ��������� �������� �� ?event=1&'.$location 
  * @return bool ��������� ������� ��� ���
  */
 function createEvent(&$_this) {
 	
 	 $location = '&sct='.$_GET['sct_back'];
 	 if ('s' == $_GET['type'])
 	 	$GLOBALS['b'] = &$_this->Section;
 	 	
  // �������� ������
     if     ( isset($_GET['clear_s']) ) {
         $_this->clearSection($_GET['clear_s'],$_GET['clear_type']);
         header('Location: ?event=1&sct='.$_GET['clear_s']);
     }    

 // �������� ������
     elseif ( isset($_GET['delete'])&& ('s' == $_GET['type'])) {
        $_this->deleteSection($_GET['delete']);         	
        header('Location: ?event=1'.$location);
     }
     
 // �������� ���������� ������
     elseif ( isset($_GET['past']) ) {
		$_this->pastRecords($ar=array('parent'=>$_GET['past']));
        header('Location: ?event=1'.$location);
     }     
     
 // �������� �������
     elseif ( isset($_GET['undoPast']) ) {
		unset($_SESSION['idCuts'][$_this->Section->table]);	
        header('Location: ?event=1'.$location);
     }
     
 // ����������� �������� ��� ������
     elseif ( isset($GLOBALS['b']) ) {
     	return $GLOBALS['b']->createEvent($location);
     }
     else return false;
     return true;
 }
 
 
 /**
  * �������� "�������" �� ��������� ���������� $_GET
  * @param BTr $_this 
  * @param string $location ��� ������������� ��������� �������� �� ?event=1&'.$location 
  */  
 function getEvent(&$_this,$location = '') {
 	$_this->createEvent($location = '');

  // ����������� ���������� ���������
    if (!isset($GLOBALS['main'])) {
	     $GLOBALS['main'] = new outTree();
	     $GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addManager($GLOBALS['main']); 
 	}
    
    out::_echo($GLOBALS['main'],$GLOBALS['main_FILENAME']);
 }
	
}


/**
 * ������ �� ����� �������
 */
class BTr extends Module {
	
 /**
  * @var BSc $Section ������-"������" ��� ������ � "�������" ���������� 
  */
 var $Section; 
 
 function BTr(&$_db,$_name = null,$_caption = null,$table_sections,&$arFilesS) {
 	$this->initBTr(&$_db,$_name,$_caption,$table_sections,&$arFilesS);
 }
 
 function initBTr(&$_db,$_name,$_caption,$table_sections,&$arFilesS) {
	$this->Section = new BSc(&$_db,$_name,$_caption,$table_sections,$arFilesS);
	$this->initModule(&$_db,$_name,$_caption);
 }
 
 function clearSection($id,$type = 0) {
 	BTr_::clearSection($this,$id,$type);
 }
 
 function deleteSection($id,$r = null,$qd = true) {
 	BTr_::deleteSection($this,$id,$r,$qd);
 }
 
 function deleteSections($id) {
 	BTr_::deleteSections($this,$id);
 } 
 
 function pastRecords(&$param) {
 	BTr_::pastRecords($this,$param);
 }
 
 function addManager(&$main) {
 	return BTr_::addManager($this,$main);
 }
 
 function addActions(&$main,&$param) {
 	BTr_::addActions($this,$main,$param);
 }
 
 function createEvent() {
 	return BTr_::createEvent($this);
 }
 
 function getEvent($location = '') {
 	BTr_::getEvent($this,$location);
 }


}


?>