<?php
/**
 *  Классы и функции по обработке записей и выводу сущностей в шаблоны.
 */

include ($inc_path.'/func.back.php');
include ($inc_path.'/classes/class.BF_P.php');
include ($inc_path.'/delete.php');

class B_news_ {

 function deleteRecord(&$_this,$id) {
       //чистим дома
       $rs = new Select($_this->db,'select * from house where id_street='.$id);
       while ($rs->next_row()) {
           house_delete($rs->result('id'));
       };
       $rs = new Select($_this->db,'delete from house where id_street='.$id);
       $rs->unset_();

       B_::deleteRecord($_this,$id);
 }


 function redactValues(&$_this,&$values) {

    if (empty($values['title']))
            $values['title'] = $values['name'];
        B_::redactValues($_this,$values);
 }

 function addIfcAddRecord(&$_this,&$main) {
    $_FILENAME = B_::addIfcAddRecord($_this,$main);
    $rs = new Select($_this->db,'select * from city order by name');
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));
           $main->addField('str_sub',&$str_sub);
    };
    $rs->unset_();
    return $_FILENAME;
 }

 function addIfcEditRecord(&$_this,&$main,$id) {
    $_FILENAME = BF_::addIfcEditRecord($_this,$main,$id);
    $rs = new Select($_this->db,'select * from city order by name');
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));
           if ($rs->result('id') ==  $main->id_city) $str_sub->addField('selected','selected');
           $main->addField('str_sub',&$str_sub);
    };
    $rs->unset_();
    return $_FILENAME;
 }

 function addSub(&$_this,&$sub,&$r,$param) {
         B_::addSub($_this,$sub,$r,$param);

         //

         $r1 = new Select($_this->db,'select name from city  where id='. $r->result('id_city'));
         if ($r1->next_row()) $sub->addField('city',$r1->result('name'));
         $r1->unset_();


 }

 function &getParamMngr(&$_this) {
         $param = &BP_::getParamMngr($_this);

         if (isset($_GET['id_city']))
            if ($_GET['id_city'] > 0)
              $param['where'] = 'id_city = '.$_GET['id_city'];

         //echo $param['where'];
        // echo $_GET['id_city'];
         $param['order'] = 'id_city,name';

         return $param;
 }


 function addManager(&$_this,&$main) {
          //echotree($_this);

         $r1 = new Select($_this->db,'select * from city');
         while ($r1->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $r1->addFields($str_sub,$ar=array('id','name'));
           if ($_GET['id_city'] == $r1->result('id'))
              $str_sub->addField('selected','selected');
          //echotree($_this);
           $main->addField('str_sub',&$str_sub);
         };
         $r1->unset_();

        return B_::addManager($_this,$main);
 }

}

class B_news extends BF_P {

 function redactValues(&$values) {
         B_news_::redactValues($this,$values);
 }

 function addIfcAddRecord(&$main) {
         return B_news_::addIfcAddRecord($this,$main);
 }

 function deleteRecord($id) {
          return B_news_::deleteRecord($this,$id);
 }

 function addIfcEditRecord(&$main,$id) {
         return B_news_::addIfcEditRecord($this,$main,$id);
 }

 function addSub(&$sub,&$r,$param) {
           B_news_::addSub($this,$sub,$r,$param);
 }

 function &getParamMngr() {
           return B_news_::getParamMngr($this,$query);
 }

///*
function addManager(&$main) {
           return B_news_::addManager($this,$main);
 }
 //*/
}

?>
