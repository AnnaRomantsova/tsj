<?php

/**
 * "�������" ��������� <br>
 * ������ �� ����������� ������� � ������� ����������������� <br>
 * <br>
 * class B extends Module
 *
 * @package BACK
 * @version 1.03 - 15.12.2006 10:00
 *
 * .02 ���������� ������ ��� �������� ������� <br>
 * .03 ������������ � redactvalue <br>
 *
 */


include_once('class.module.php');

/**
 * ����������
 * @uses Select
 * @uses outTree
 */
class B_ {

 /**
  * ��������� ������ �������������� ������<br>
  * ��������� ����������
  * @param B $_this
  * @param outTree $main ������ ��������������
  * @return string ���� ������� ����������
  */
 function addIfcAddRecord(&$_this,&$main) {
     $main->addField('add','');

         $_this->initPath($main,false);
         $ot_last = new outTree();
         $ot_last->addField('name', '����������');
         $main->path->addField('last',&$ot_last);

         return 'redact.html';
 }

 /**
  * ��������� ������ �������������� ������<br>
  * ��������� ���������
  * @param B $_this
  * @param outTree $main ������ ��������������
  * @param int $id id ���������� ������
  * @return string ���� ������� ���������
  */
 function addIfcEditRecord(&$_this,&$main,$id) {
          $main->addField('edit','');

     $r = new Select($_this->db,'select * from '.$_this->table.' where id="'.$id.'"');
     if ($r->next_row()) {
                 $_this->initPath($main,false);
                 $ot_last = new outTree();
                 $ot_last->addField('name', '��������������' );
                 $main->path->addField('last',&$ot_last);

         $r->addAll($main);
         $GLOBALS['r'] = &$r;
              return 'redact.html';
     }

     return null;
 }


 /**
  * ��������: ��������� ����� ������
  * @param B $_this
  * @param array $values �����.������ �������� �����
  */
 function saveNewRecord(&$_this,&$values) {
          $_this->redactValues($values);
     $values['id'] = $id = $_this->db->next_id($_this->table);
     $_this->db->insert($_this->table, $values);
     return $id;
 }

 /**
  * ��������: ��������� ������������ ������
  * @param B $_this
  * @param array $values �����.������ �������� ���������� �����
  * @param int $id id ���������� ������
  */
 function saveRecord(&$_this,&$values,$id) {
          $_this->redactValues($values);
        //  var_dump($values);
       // echo $id;
         $_this->db->update($_this->table, $values, 'id="'.$id.'"');
 }

 /**
  * ��������������� ��������� <br>
  * �����.������� �������� �����<br>
  * ��� ���������� �������
  * @param B $_this
  * @param array $values �����.������ �������� �����
  */
 function redactValues(&$_this,&$values) {
          foreach ( $values as $key => $value) {
                  //echo $key.' = '.$value.'<br />';
            $values[$key] = $value;
          }
         $values['pabl'] = (double)$values['pabl'];
 }


 /**
  * ��������: ������� �������
  * @param B $_this
  * @return int ���������� ��������� �������
  */
 function clearTable(&$_this) {

    // �������� �������
    $ri = new Select($_this->db,'select * from '.$_this->table);
    while ($ri->next_row())
       $_this->deleteRecord($ri->result('id'),&$ri,false);
    $ri->unset_();
        $_this->db->query('delete from '.$_this->table);
        return $_this->db->affected_rows();

 }


 /**
  * ��������: ������� ������
  * @param B $_this
  * @param int $id id ��������� ������
  * @param Select $r ������ � ��������� �������
  * @param bool $qd ������� ���� ������ ��� ������������ "����������������� ����������"
  * @return int ���������� ��������� �������
  */
 function deleteRecord(&$_this,$id,$r = null,$qd = true) {
        if ($qd) {
                $_this->db->query('delete from '.$_this->table.' where id="'.$id.'"');
                 return $_this->db->affected_rows();
        }
        else
                return 0;

 }


 /**
  * ��������: ���������/�� ��������� ������
  * @param B $_this
  * @param int $id id ���������� ������
  */
 function pablRecord(&$_this,$id) {
    $r = new Select($_this->db,'select pabl from '.$_this->table.' where id="'.$id.'"');
    if ($r->next_row())
                $_this->db->query('update '.$_this->table.' set pabl="'.((1+intval($r->result(0)))%2).'" where id="'.$id.'"');
    $r->unset_();
 }


 /**
  * ��������� ���� � ������ ��������� ��� ������ ��������������
  * @param B $_this
  * @param outTree $main ������
  * @param Select $r ������ � �������-"������ ����"
  * @param bool $with_last �������� ����� ���� ��� ���
  */
 function initPath(&$_this,&$main,$with_last = true) {

         $nameFirst = $_this->caption;
         $path = new outTree();
        if (!$with_last) {
                 $ot_first = new outTree();
                $ot_first->addField('name', $nameFirst);
                $ot_first->addField('href', '?' );
                $path->addField('first',&$ot_first);
        }
        else {
                $ot_last = new outTree();
                $ot_last->addField('name', $nameFirst );
                $path->addField('last',&$ot_last);
        }

        $main->addField('path',&$path);
 }

 /**
  * ��������� ������ �������� � ������ ���������<br>
  * ������ ����� ���� ��������� ��� � ���� ������ ��������� ��� ������� ������<br>
  * ��� � � ������ ����� ������
  * @param B $_this
  * @param outTree $main ������ ��������� ��� �����
  * @param array $param ��������� ��������� � ������
  */
 function addButtons(&$_this,&$main,&$param) {
         $main->addField('butRedact','');
         $main->addField('butDelete','');
         $main->addField('butPabl','');
 }


 /**
  * ��������� ������ �������� � ������ ���������
  * @param B $_this
  * @param outTree $main ������ ���������
  * @param array $param ��������� ���������
  */
 function addActions(&$_this,&$main,&$param) {
        $main->addField('actAdd','');
        $main->addField('actions','');
 }

 /**
  * ��������� ���� ����� ������� � ������ ���������
  * @param B $_this
  * @param outTree $sub ������ �����
  * @param Select $r ������ � ��������
  * @param array $param ��������� ��������� � ������
  */
 function addSub(&$_this,&$sub,&$r,$param) {
        $_this->addButtons($sub,$param);
           $r->addFields($sub,$ar = array('name','id','pabl'));
 }

 /**
  * ��������� ����� ������� � ������ ���������
  * @param B $_this
  * @param outTree $main ������ ���������
  * @param Select $r ������ � ��������
  * @param array $param ��������� ���������
  * @param string $field ��� ����, � ������� ��������� �����
  */
 function addSubs(&$_this,&$main,&$r,&$param,$field = 'records') {
            $ot = new outTree();
     while ($r->next_row()) {
             $sub =  new outTree();
             $param = &array_merge($param,$ar=&$r->fetch_assoc());
            // var_dump($param);
             $_this->addSub($sub,$r,$param);
             $ot->addField('sub',&$sub);
             unset($sub);
     }
            $main->addField($field,&$ot);
 }

 /**
  * ��������� ������ � ������ ���������
  * @param B $_this
  * @param outTree $main ������ ���������
  * @param array $param ��������� ���������
  */
 function addRecords(&$_this,&$main,&$param) {


        $r = new Select($_this->db,'select * from '.$_this->table.(isset($param['where']) ? ' where '.$param['where'] : '').(isset($param['order']) ? ' order by '.$param['order'] : ''));


    if ($r->num_rows) {
           $_this->addSubs($main,$r,$param);
           $main->addField('actClear','');
    }
    $r->unset_();
 }

 /**
  * �������� ��������� ��������� ������� � �������
  * @param B $_this
  * @return array
  */
 function &getParamMngr(&$_this) {
         return array();
 }

 /**
  * ��������� ������ ��������� �������
  * @param B $_this
  * @param outTree $main ������ ���������
  * @return string ���� ������� ���������
  */
 function addManager(&$_this,&$main) {
         $param = &$_this->getParamMngr();
         $_this->initPath($main);
         $_this->addRecords($main,$param);
         $_this->addActions($main,$param);
        return 'manager.html';
 }

 /**
  * �������� "�������" �� ��������� ���������� $_GET
  * @param B $_this
  * @param string $location ��� ������������� ��������� �������� �� ?event=1&'.$location
  */
 function getEvent(&$_this,$location = '') {
         $_this->createEvent($location);

  // ����������� ���������� ���������
    if (!isset($GLOBALS['main'])) {
             $GLOBALS['main'] = new outTree();
             $GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addManager($GLOBALS['main']);
    }

    out::_echo($GLOBALS['main'],$GLOBALS['main_FILENAME']);
 }

 /**
  * ���������� "�������" �� ��������� ���������� $_GET
  * @param B $_this
  * @param string $location ��� ������������� ��������� �������� �� ?event=1&'.$location
  * @return bool ��������� ������� ��� ���
  */
 function createEvent(&$_this,$location = '') {

 // �������� �������
     if ( isset($_GET['clear']) ) {
        $_this->clearTable();
        header('Location: ?event=1&'.$location);
     }

 // ��������
     elseif ( isset($_GET['delete']) ) {
        $_this->deleteRecord($_GET['delete']);
        header('Location: ?event=1&'.$location);
     }

 // �����������/�� �����������
     elseif ( isset($_GET['pabl']) ) {
        $_this->pablRecord($_GET['pabl']);
        header('Location: ?event=1&'.$location);
     }

 // ��������� ���������� ������
        elseif     (isset($_GET['add'])) {
            $GLOBALS['main'] = new outTree();
                $GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addIfcAddRecord($GLOBALS['main']);
        }

// ��������� ��������� ������
        elseif (isset($_GET['edit'])) {
            $GLOBALS['main'] = new outTree();
                $GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addIfcEditRecord($GLOBALS['main'],$_GET['edit']);
        }

 // ���������� ������
         elseif  (isset($_GET['save_new'])) {
             $id = $_this->saveNewRecord($_POST);
         header('Location: ?event=1&'.$location);
         }

 // ��������� ������
     elseif (isset($_GET['save'])) {
             $_this->saveRecord($_POST,$_GET['save']);
         header('Location: ?event=1&'.$location);
         }

         else
                 return false;
         return true;
 }

}

/**
 * ������ �� ����������� �������
 */
class B extends Module {
 var $table;

 function B(&$db,$_name = null,$_caption =null,$_table = null) {
        $this->initB(&$db,$_name,$_caption,$_table);
 }

 function initB(&$db,$_name = null,$_caption =null,$_table = null) {
        $this->table = $_table;
        $this->initModule($db,$_name,$_caption);
 }


 function redactValues(&$values) {
         B_::redactValues($this,$values);
 }

 function saveNewRecord(&$values) {
         return B_::saveNewRecord($this,$values);
 }

 function saveRecord(&$values,$id) {
         B_::saveRecord($this,$values,$id);
 }

 function clearTable() {
         return B_::clearTable($this);
 }

 function deleteRecord($id,$r = null,$qd = true) {
         B_::deleteRecord($this,$id,$r,$qd);
 }

 function pablRecord($id) {
         B_::pablRecord($this,$id);
 }

 function addIfcAddRecord(&$main) {
         return B_::addIfcAddRecord($this,$main);
 }

 function addIfcEditRecord(&$main,$id) {
         return B_::addIfcEditRecord($this,$main,$id);
 }

 function initPath(&$main,$with_last = true ) {
         B_::initPath($this,$main,$with_last);
 }

 function addButtons(&$main,&$param) {
         B_::addButtons($this,$main,$param);
 }

 function addActions(&$main,&$param) {
         B_::addActions($this,$main,$param);
 }

 function addRecords(&$main,&$param) {
         B_::addRecords($this,$main,$param);
 }

 function addManager(&$main) {
         return B_::addManager($this,$main);
 }

 function addSub(&$sub,&$r,&$param) {
           B_::addSub($this,$sub,$r,$param);
 }

 function addSubs(&$main,&$r,&$param,$field = 'records') {
           B_::addSubs($this,$main,&$r,&$param,$field);
 }

 function &getParamMngr() {
           return B_::getParamMngr($this);
 }

 function createEvent($location = '') {
         return B_::createEvent($this,$location);
 }

 function getEvent($location = '') {
         B_::getEvent($this,$location);
 }

}



?>
