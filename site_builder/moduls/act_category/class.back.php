<?php
/**
 *  Классы и функции по обработке записей и выводу сущностей в шаблоны.
 */

include ($inc_path.'/func.back.php');
include ($inc_path.'/classes/class.BF_P.php');


class B_news_ {


 function &getParamMngr(&$_this) {
         $param = &BP_::getParamMngr($_this);
         $param['order'] = 'name';
         return $param;
 }


}

class B_news extends BF_P {



 function &getParamMngr() {
           return B_news_::getParamMngr($this);
 }


}

?>
