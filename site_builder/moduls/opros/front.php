<?php

 include('config.php');

 unset($main);
 $FILENAME = $front_html_path.'front.html';

 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];

 include($inc_path.'/classes/class.BF.php');
 include($inc_path.'/admin_functions.php');

//если авторизован и председатель этого тсж
 if ( $_SESSION ['user']>0) {
    $r1 = new Select($db,'select * from users where id="'.$user.'"');
    if ($r1->next_row())
    $user_company = $r1->result('id_company');
 };

// var_dump($_POST);
// выбран дом
 if ($cookie>0 || $user_company>0) {

     if ($cookie>0) {
          $r = new Select($db,'select * from house where id="'.$cookie.'"');
          if ($r->next_row()) $id_company = $r->result('id_company');
     } else
              $id_company = $user_company;

     $main = &addInCurrentSection($FILENAME);
     unset($main->content);

     if ( $_SESSION ['user']>0) {

           //если юзер - председатель этого ТСЖ
           if ($user_company == $id_company)
           {

                  //нажали кнопку удалить
                  if ($_POST['rep_del']>0){

                        if ($_POST['id']>0) {
                           $back = new BF($db,$modulName,$modulCaption,$table_name,$arFiles);
                           $back->deleteRecord($_POST['id']);
                           $r1 = new Select($db,'delete from opros where id='.$_POST['id']);
                        };
                        $main->addField('mode','mode_edit');
                  }
                  //нажали кнопку редактировать один отчет
                  else if ($_POST['rep_edit']>0){
                        if ($_POST['id']>0)
                            $main->addField('mode','mode_edit_one');
                  }
                  //нажали кнопку редактировать один отчет
                  else if ($_POST['new_rep']>0){
                           $r1 = new Select($db,'select h.id,h.number,h.fract,s.name from house h,street s where s.id=h.id_street and h.id_company='.$id_company);
                           while ($r1->next_row()) {
                                unset($sub);
                                $sub = new outTree();
                                $r1->addFields($sub,$ar=array('id','name','number'));
                                if ($r1->result('fract') !=='') $sub->addField('fract',$r1->result('fract'));
                                $main->addField('house',&$sub);
                           };
                           $main->addField('mode','mode_new');
                  }
                  //нажали кнопку сохранить
                  else if ($_POST['save']>0){
                     foreach($_POST as $key=>$val)
                     if (strpos($key,'otvet') >0 ) {
                         $id = substr($key,6);
                         $ri = new Select($db,"update opros set name='$val' where id=$id");
                        // echo "update opros set name='$val' where id=$id";
                     };
                     foreach($_POST['new'] as $key=>$val)
                       if ($val !== '')
                            $ri = new Select($db,"insert into opros(name,id_house,parent,cnt) values ('".$val."',".$_POST['id_house'].",".$_POST['id'].",0)");
                     $ri = new Select($db,"update opros set name='".$_POST['zagol']."' where id=".$_POST['id']);
                     $main->addField('mode','mode_edit');
                  }
                  // сохранить новый отчет
                  else if ($_POST['new_rep_submit']>0){
                            $ri = new Select($db,"insert into opros(name,id_house,parent,cnt) values ( '".$_POST['zagol']."',".$_POST['id_house'].",0,0)");
                            $new_id = $db->insert_id();
                            if ($new_id>0)
                              foreach($_POST['_otvet'] as $key=>$val)
                                    if ($val !== '')
                                        $ri = new Select($db,"insert into opros(name,id_house,parent,cnt) values ('".$val."',".$_POST['id_house'].",$new_id,0)");
                           $main->addField('mode','mode_edit');
                  }
                  //если выбран дом
                  else if ($cookie>0) $main->addField('mode','mode_show');
                  //ничего не нажимали
                  else $main->addField('mode','mode_edit');
           } //юзер - не председатель этого ТСЖ
           else $main->addField('mode','mode_show');
     } else $main->addField('mode','mode_show');

     if (($_POST['id']>0)&&($main->mode=='mode_edit_one')) {
          $ri = new Select($db,"select * from opros where parent=0 and id=".$_POST['id']);

          if ($ri->next_row()) {
                  $main->addField('id_house',$ri->result('id_house'));
                  unset($sub);
                  $sub = new outTree();
                  $ri->addFields($sub,$ar=array('id','cnt','name'));

                  $r = new Select($db,"select * from opros where parent=".$ri->result('id'));
                  while ($r->next_row()) {
                       unset($sub1);
                       $sub1 = new outTree();
                       $r->addFields($sub1,$ar=array('id','cnt','name'));
                       $sub->addField('sub1',&$sub1);
                  };
                  $main->addField('sub',&$sub);
           };
     } else if ($main->mode=='mode_show'){
          $ri = new Select($db,"select * from opros where parent=0 and id_house=".$cookie);
          if ($ri->num_rows() ==0) $main->addField('no_sub','');
          while ($ri->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $ri->addFields($sub,$ar=array('id','watch','name'));

                  $r = new Select($db,"select * from opros where parent=".$ri->result('id'));
                  while ($r->next_row()) {
                       unset($sub1);
                       $sub1 = new outTree();
                       $r->addFields($sub1,$ar=array('id','watch','name'));
                       $sub->addField('sub1',&$sub1);
                  };
                  $main->addField('sub',&$sub);
          };


     }
      else if ($main->mode=='mode_edit'){

         $r1 = new Select($db,'select * from opros o,house h where h.id=o.id_house and parent=0 and h.id_company='.$id_company);
         while ($r1->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $r2 = new Select($db,'select * from street where id='.$r1->result('id_street'));
                  if ($r2->next_row()) $sub->addField('house',$r2->result('name')." ".$r1->result('number')." ".$r1->result('fract'));
                  $r1->addFields($sub,$ar=array('id','name'));
                  $main->addField('sub',&$sub);
          };
     };
  }

  else header('Location: /');
 //echotree($main);

 ?>