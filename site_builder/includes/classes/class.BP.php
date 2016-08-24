<?php

/**
 * ������ c ������������<br>
 *  P - ������������� ���������<br>
 * <br>
 * class BP extends B<br>
 * �������� ������ BP,B
 *
 * @package BACK
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.B.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Pager
 */
class BP_ {

 /**
  * �������� ��������� ��������� ������� � �������
  * @param BP $_this
  * @return array
  */
 function &getParamMngr(&$_this) {
         $param = &B_::getParamMngr($_this);
          $param['jumpValue'] = $_GET['id_back'];
          $param['asGET'] = true;
         return $param;
 }

 /**
  * ��������� ������ � ������ ��������� � ������������ ����������
  * @param BP $_this
  * @param outTree $main ������ ���������
  * @param array $param ��������� ���������
  */
 function addRecords(&$_this,&$main,&$param) {
           include($GLOBALS['inc_path'].'/service/class.pager.php');
    $main->addField('cp',!empty($_GET['cp']) ? $_GET['cp'] : 0);
           $param = &$_this->getParamMngr();
         //  var_dump($param);
        if ($pg = &Pager::newPager($_this->db,$_this->table,$GLOBALS[$_this->name.'_acount'],$_GET['cp'],$param)) {
                 $pg->addPAGER($main);
             $_this->addSubs($main,$pg->r,$param);
             $main->addField('actClear','');
    }
 }

 /**
  * �������� "�������" �� ��������� ���������� $_GET
  * @param BP $_this
  * @param string $location ��� ������������� ��������� �������� �� ?event=1&'.$location
  */
 function getEvent(&$_this,$location = '') {
         $location.='cp='.$_GET['cp'];
         if (isset($_GET['id_back']))
           $location.='&id_back='.$_GET['id_back'];
         B_::getEvent($_this,$location);
 }



}

/**
 * ������ �� ����������� ������� c ������������ <br>
 *  P - ������������� ���������
 */
class BP extends B {

 function initBP(&$db,$_name = null,$_caption =null,$_table = null) {
        $this->table = $_table;
        $this->initModule($db,$_name,$_caption);
 }

 function addRecords(&$main,&$param) {
         BP_::addRecords($this,$main,$param);
 }

 function &getParamMngr() {
           return BP_::getParamMngr($this);
 }

 function getEvent($location = '') {
         BP_::getEvent($this,$location);
 }

}



?>
