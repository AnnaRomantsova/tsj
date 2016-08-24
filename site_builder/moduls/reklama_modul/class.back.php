<?php
/**
 *  Классы и функции по обработке записей и выводу сущностей в шаблоны.
 */

include ($inc_path.'/func.back.php');
include ($inc_path.'/img.php');
include ($inc_path.'/classes/class.BF_P.php');


class B_news_ {
 function deleteRecord(&$_this,$id) {
       //чистим фотки, новости ТСЖ, опросы
       //echo $id;die;
       $rs = new Select($_this->db,"delete from reklama_page where id_reklama=$id");
       $rs1 = new Select($_this->db,"delete from reklama_city where id_reklama=$id");
      
       B_::deleteRecord($_this,$id);
 }


 function saveRecord(&$_this,&$values,$id) {

        //$_FILES['image1'] =  $_FILES['image2'];

        $rs = new Select($_this->db,"delete from reklama_page where id_reklama=$id");
        $rs = new Select($_this->db,"delete from reklama_city where id_reklama=$id");
        foreach ($_POST as $key => $value) {
           //страницы
           if (substr($key,0,5)=='pages') {
               $page_id = substr($key,5);
               if ($value=='on') $rs = new Select($_this->db,"insert into reklama_page(id_reklama,id_page) values($id,$page_id)");
           };
           //города
           if (substr($key,0,4)=='city') {
               $page_id = substr($key,4);
               if ($value=='on') $rs = new Select($_this->db,"insert into reklama_city(id_reklama,id_city) values($id,$page_id)");
           };
        };

        BF_::saveRecord($_this,$values,$id);

        $r1 = new Select($_this->db,'select * from '.$this->table.' where id='.$id);
        if ($r1->next_row()) {
            image_resize_admin( $r1->result('image1'),230,400);
        };
 }

  function saveNewRecord(&$_this,&$values) {

        //$_FILES['image1'] =  $_FILES['image2'];
        //echo_tree($values);
        $id = BF_::saveNewRecord($_this,$values,$id);

        foreach ($_POST as $key => $value) {
           //страницы
           if (substr($key,0,5)=='pages') {
               $page_id = substr($key,5);
               if ($value=='on') $rs = new Select($_this->db,"insert into reklama_page(id_reklama,id_page) values($id,$page_id)");
           };
           //города
           if (substr($key,0,4)=='city') {
               $page_id = substr($key,4);
               if ($value=='on') $rs = new Select($_this->db,"insert into reklama_city(id_reklama,id_city) values($id,$page_id)");
           };
        };



        $r1 = new Select($_this->db,'select * from '.$this->table.' where id='.$id);
        if ($r1->next_row()) {
            image_resize_admin( $r1->result('image1'),230,400);
        };
 }

 function addIfcAddRecord(&$_this,&$main) {
        $_FILENAME = B_::addIfcAddRecord($_this,$main);
        $rs = new Select($_this->db,"select c.* from city c order by name");
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));

           $main->addField('city_sub',&$str_sub);
    };

    $rs = new Select($_this->db,"select c.* from site_tree c where parent = 135 and id not in (136,139,143,144)");
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));

           $main->addField('pages_sub',&$str_sub);
    };
    return $_FILENAME;
 }

 function addIfcEditRecord(&$_this,&$main,$id) {
    $_FILENAME = BF_::addIfcEditRecord($_this,$main,$id);
    $rs = new Select($_this->db,"select c.*,(select count(*) from reklama_city where id_reklama=$id and id_city=c.id) as cnt from city c order by name");
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));
           if ($rs->result('cnt') > 0) $str_sub->addField('checked','checked');
           $main->addField('city_sub',&$str_sub);
    };

    $rs = new Select($_this->db,"select c.*,(select count(*) from reklama_page p where id_reklama=$id and p.id_page=c.id) as cnt from site_tree c where parent = 135 and id not in (136,139,143,144)");
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));
           if ($rs->result('cnt') > 0) $str_sub->addField('checked','checked');
           $main->addField('pages_sub',&$str_sub);
    };
    return $_FILENAME;
 }

 function addSub(&$_this,&$sub,&$r,$param) {
         B_::addSub($_this,$sub,$r,$param);
         //$sub->addField('date',date('d.m.y, H i', $r->result('datetime')));
         //$r->addFieldIMG($sub,$ar=array('image1'));
        // echotree($sub);
         if ($r->result('image1')!=='') $r->addField($sub,'image1');
         //$rs = new Select($_this->db,'select c.name as company,h.*,s.name as street from house h,company c, street s where c.id=h.id_company and s.id=h.id_street and h.id='.$r->result('id_house'));
         //if ($rs->next_row())
         //  $rs->addFields($sub,$ar=array('company','street','number','fract'));
  }

 function &getParamMngr(&$_this) {
         $param = &BP_::getParamMngr($_this);
         //$param['order'] = 'id_house desc';
        // $param['where'] = ' ntype=1';
         return $param;
 }


}

class B_news extends BF_P {

 //function redactValues(&$values) {
 //        B_news_::redactValues($this,$values);
// }
 function deleteRecord($id) {
          return B_news_::deleteRecord($this,$id);
 }
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

 function saveRecord(&$values,$id) {
         B_news_::saveRecord($this,$values,$id);
 }

 function saveNewRecord(&$values) {
         B_news_::saveNewRecord($this,$values);
 }
}

?>
