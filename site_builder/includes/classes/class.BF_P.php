<?php

/**
 * ������ c ������������ <br>
 *  F - ������� �������������� ����� <br>
 *  P - ������������� ��������� <br>
 * <br>
 * class BF_P extends BF <br>
 * �������� ������ BF_P,BP,BF,B
 *
 * @package BACK
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.BF.php');
include_once('class.BP.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Pager
 */
class BF_P_ {

 /**
  * @param BF_P $_this
  */

}

/**
 * ������ �� ����������� ������� c ������������ <br>
 *  F - ������� �������������� ����� <br>
 *  P - ������������� ���������
 */
class BF_P extends BF {

 function initBF_P(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
        $this->arFiles = &$arFiles;
        $this->initBF($db,$_name,$_caption,$_table);
 }

 ///--------------- BP
  function addRecords(&$main,&$param) {
         BP_::addRecords($this,$main,$param);
 }

 function &getParamMngr() {
           return BP_::getParamMngr($this);
 }

 function getEvent($location = '') {
         BP_::getEvent($this,$location);
 }
  ///--------------- /BP

}

?>
