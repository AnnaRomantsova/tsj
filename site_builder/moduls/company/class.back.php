<?php
/**
 *  Классы и функции по обработке записей и выводу сущностей в шаблоны.
 */

include ($inc_path.'/func.back.php');
include ($inc_path.'/classes/class.BF_P.php');
include ($inc_path.'/delete.php');

class B_news_ {

  function deleteRecord(&$_this,$id) {

       //чистим отчеты
       report_delete($id);
       //чистим председателй
       $r1 = new Select($_this->db,'delete from users where id_company='.$id);
       B_::deleteRecord($_this,$id);
 }

 function redactValues(&$_this,&$values) {
     if ($values['pabl'] == 1) {
         $values['about'] = &$values['pre_about'];
         $values['manage'] = &$values['pre_manage'];
         $values['service'] = &$values['pre_service'];
         $values['tarif'] = &$values['pre_tarif'];
     } else $values['pabl'] = 0;
     B_::redactValues($_this,$values);
 }

 function addIfcAddRecord(&$_this,&$main) {
         $_FILENAME = B_::addIfcAddRecord($_this,$main);
         $main->addField('pre_about',"loadFCKeditor('pre_about','');");
         $main->addField('pre_manage',"loadFCKeditor('pre_manage','');");
         $main->addField('pre_service',"loadFCKeditor('pre_service','');");
         $main->addField('pre_tarif',"loadFCKeditor('pre_tarif','');");
    return $_FILENAME;
 }

 function addIfcEditRecord(&$_this,&$main,$id) {
    $_FILENAME = BF_::addIfcEditRecord($_this,$main,$id);
    removeFields($main,$ar = array('pre_about','pre_manage','pre_service','pre_tarif'));
   // $main->addField('about','addFCKeditor($GLOBALS["r"],"about");');
    $main->addField('pre_about','addFCKeditor($GLOBALS["r"],"pre_about");');
    $main->addField('pre_manage','addFCKeditor($GLOBALS["r"],"pre_manage");');
    $main->addField('pre_service','addFCKeditor($GLOBALS["r"],"pre_service");');
    $main->addField('pre_tarif','addFCKeditor($GLOBALS["r"],"pre_tarif");');
    return $_FILENAME;
 }



 function &getParamMngr(&$_this) {
         $param = &BP_::getParamMngr($_this);
         $param['order'] = 'name';
         return $param;
 }


}

class B_news extends BF_P {

 function redactValues(&$values) {
         B_news_::redactValues($this,$values);
 }

 function addIfcAddRecord(&$main) {
         return B_news_::addIfcAddRecord($this,$main);
 }

 function addIfcEditRecord(&$main,$id) {
         return B_news_::addIfcEditRecord($this,$main,$id);
 }



 function &getParamMngr() {
           return B_news_::getParamMngr($this);
 }
  function deleteRecord($id) {
          return B_news_::deleteRecord($this,$id);
 }


}

?>
