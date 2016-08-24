<?php

/**
 * ������ c ������������<br>
 *  O - ����������� �� ���� sort<br>
 * <br>
 * class BO extends B<br>
 * �������� ������ BO,B
 *
 * class BFTTO_FTTSFS_TOTS extends BFT_TO
 * �������� ������ BFTTO_FTTSFS_TOTS,BS,BFT_TO,BF_T,BF,BO,BT_O,BT,B
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
class BO_ {

 /**
  * ���������� "�������" �� ��������� ���������� $_GET
  * @param BO $_this
  * @param string $location ��� ������������� ��������� �������� �� ?event=1&'.$location
  * @return bool ��������� ������� ��� ���
  */
 function createEvent(&$_this,$location = '') {

 // ���������� ������
         if  (isset($_GET['save_new'])) {
             $id = $_this->saveNewRecord($_POST,$_GET['save_last']);
         header('Location: ?event=1&'.$location);
         }

 // ������� ����
     elseif ( isset($_GET['up']) ) {
        $_this->upRecord($_GET['up']);
        header('Location: ?event=1&'.$location);
     }

 // �������� ����
     elseif ( isset($_GET['down']) ) {
        $_this->downRecord($_GET['down']);
        header('Location: ?event=1&'.$location);
     }

     else
             return B_::createEvent($_this,$location);
         return true;
 }

 /**
  * ������������� �������� ���� ���������� � �����.������� ��������<br>
  * ��� ���������� ����� ������
  * @param BO $_this
  * @param array $values �����.������ �������� �����
  * @param bool $last ��������� ������ ��������� ��� ���
  */
 function setRecordSort(&$_this,&$values,$last = false) {
         if (!empty($last)) {
             $rSort=new Select($_this->db,'select max(sort) from '.$_this->table);
             $values['sort'] = $rSort->num_rows ? 1+$rSort->result(0,0) : 0;
             $rSort->unset_();
         }
         else {
             $_this->db->query('update '.$_this->table.' set sort=sort+1');
             $values['sort'] = 0;
         }
 }

 /**
  * ��������: ��������� ����� ������
  * @param BO $_this
  * @param array $values �����.������ �������� �����
  * @param bool $last ��������� ������ ��������� ��� ���
  */
 function saveNewRecord(&$_this,&$values,$last = false) {
          $_this->setRecordSort($values,$last);
     return B_::saveNewRecord(&$_this,&$values);
 }

 /**
 * ��������� ���������� �, ���� ���� ����������� ��������, �������������
 * @param BO $_this
 * @param Select $r ��� ��������� ���������� - ������� � ������� ������ ��������� � $_this->table
 * @return bool ��������������� ��� ���
 */
 function setValidSort(&$_this,&$r,$reSelect = false) {
        $sorts = array();
        while($r->next_row())
                $sorts[] = intval($r->result('sort'));

        $r->result_row = -1; // ���������� ������ �� �����

        $sorts = array_unique($sorts);

   // �������� � ��������������
        if ( $notValid = ($r->num_rows != count($sorts)) ) {
                while($r->next_row())
                        $_this->db->query('update '.$_this->table.' set sort='.$r->result_row.' where id="'.$r->result('id').'"');
                go($_SERVER['REQUEST_URI']);
        }

        return $notValid;
 }

 /**
  * ��������: ��������� ������ ����
  * @param BO $_this
  * @param int $id id ����������� ������
  */
 function upRecord(&$_this,$id) {
    $r = new Select($_this->db,'select sort from '.$_this->table.' where id="'.$id.'"');
    if ($r->next_row())
        record_up($_this->table,$r->result('sort'));
    $r->unset_();
 }

 /**
  * ��������: �������� ������ ����  :)
  * @param BO $_this
  * @param int $id id ���������� ������  :)
  */
 function downRecord(&$_this,$id) {
    $r = new Select($_this->db,'select sort from '.$_this->table.' where id="'.$id.'"');
    if ($r->next_row())
        record_down($_this->table,$r->result('sort'));
    $r->unset_();
 }

 /**
  * ��������� ������ ���������� � ����� ������ � ������ ���������
  * @param BO $_this
  * @param outTree $main ������ �����
  * @param array $param ��������� ��������� � ������
  */
 function addButtonsSort(&$_this,&$main,&$param) {
         if ($param['sort'] > $param['minSort'])
                 $main->addField('butUp','');

         if ($param['sort'] < $param['maxSort'])
                 $main->addField('butDown','');
 }

 /**
  * ��������� ������ �������� � ������ ���������<br>
  * ������ ����� ���� ��������� ��� � ���� ������ ��������� ��� ������� ������<br>
  * ��� � � ������ ����� ������
  * @param BO $_this
  * @param outTree $main ������ ��������� ��� �����
  * @param array $param ��������� ��������� � ������
  */
 function addButtons(&$_this,&$main,&$param) {
         B_::addButtons($_this,$main,$param);
         $_this->addButtonsSort($main,$param);
 }

 /**
  * �������� ��������� ��������� ������� � �������
  * @param BO $_this
  * @return array
  */
 function &getParamMngr(&$_this) {
         $param = &B_::getParamMngr($_this);
         $param['order']  = 'sort';
         return $param;
 }

 /**
  * ��������� ����� ������� � ������ ���������
  * @param BO $_this
  * @param outTree $main ������ ���������
  * @param Select $r ������ � ��������
  * @param array $param ��������� ���������
  * @param string $field ��� ����, � ������� ��������� �����
  */
 function addSubs(&$_this,&$main,&$r,&$param,$field = 'records') {
  // ���������� ������������ ������� � ������ - ��� �� ����� ����
         $result_row = $r->result_row; $end = $r->end;

  // �������� ����������
    $r->result_row = -1; unset($r->end);
    $_this->setValidSort($r);

  //������ �������
         $r->result_row = $result_row; $r->end = $end;

        $param['minSort'] = $r->result('sort',0);
        $param['maxSort'] = $r->result('sort',($r->num_rows-1));

           B_::addSubs($_this,$main,$r,$param,$field);
 }

}

/**
 * ������ �� ����������� ������� c ������������ <br>
 *  O - ����������� �� ���� sort
 */
class BO extends B {

 function initBO(&$db,$_name = null,$_caption =null,$_table = null) {
        $this->initB(&$db,$_name,$_caption,$_table);
 }


 function saveNewRecord(&$values,$last = null) {
         return BO_::saveNewRecord($this,$values,$last);
 }

 function setRecordSort(&$values,$last = null) {
         BO_::setRecordSort($this,$values,$last);
 }

 function setValidSort(&$r,$reSelect = true) {
         BO_::setValidSort($this,$r,$reSelect);
 }

 function upRecord($id) {
         BO_::upRecord($this,$id);
 }

 function downRecord($id) {
         BO_::downRecord($this,$id);
 }

 function addButtonsSort(&$main,&$param) {
         BO_::addButtonsSort($this,$main,$param);
 }

 function addButtons(&$main,&$param) {
         BO_::addButtons($this,$main,$param);
 }

 function createEvent($location = '') {
         return BO_::createEvent($this,$location);
 }

 function &getParamMngr() {
           return BO_::getParamMngr($this);
 }

 function addSubs(&$main,&$r,&$param,$field = 'records') {
           BO_::addSubs($this,$main,&$r,&$param,$field);
 }


}

?>
