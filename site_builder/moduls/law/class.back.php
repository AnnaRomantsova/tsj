<?php
/**
 *  Классы и функции по обработке записей и выводу сущностей в шаблоны.
 */

include ($inc_path.'/func.back.php');
include ($inc_path.'/classes/class.BO_P.php');

class B_news_ {

 function addIfcAddRecord(&$_this,&$main) {
    $_FILENAME = B_::addIfcAddRecord($_this,$main);
     $main->addField('about',"loadFCKeditor('about','');");
    return $_FILENAME;
 }

 function addIfcEditRecord(&$_this,&$main,$id) {
    $_FILENAME = B_::addIfcEditRecord($_this,$main,$id);
   removeFields($main,$ar = array('about'));
    $main->addField('about','addFCKeditor($GLOBALS["r"],"about");');
    return $_FILENAME;
 }

 function addSub(&$_this,&$sub,&$r,$param) {
         B_::addSub($_this,$sub,$r,$param);


 }
   function &getParamMngr(&$_this) {
         $param = &BP_::getParamMngr($_this);
          $param['order'] = 'datetime desc,id desc';
          $param['where'] = ' ntype=2';
         return $param;
 }


}

class B_news extends BO_P {

 function addIfcAddRecord(&$main) {
         return B_news_::addIfcAddRecord($this,$main);
 }

 function addIfcEditRecord(&$main,$id) {
         return B_news_::addIfcEditRecord($this,$main,$id);
 }

 function addSub(&$sub,&$r,$param) {
           B_news_::addSub($this,$sub,$r,$param);
 }


 function &getParamMngr() {
           return B_news_::getParamMngr($this);
 }


}

?>
