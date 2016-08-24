<?php

 include('config.php');

 unset($main);
 $s_FILENAME = $front_html_path.'front.html';
 $i_FILENAME = $front_html_path.'item.html';
 $add_FILENAME = $front_html_path.'add.html';

 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];

 //если авторизован и председатель этого тсж
     if ( $_SESSION ['user']>0) {
         $r1 = new Select($db,'select * from users where id="'.$user.'"');
         if ($r1->next_row()) $user_company = $r1->result('id_company');
     };

     $r = new Select($db,'select * from house where id="'.$cookie.'"');
     if ($r->next_row())  $id_company=$r->result('id_company');


//var_dump($_POST);
// выбран дом
 if (!($cookie>0)) header('Location: /');

  if (isset($_POST['edit_id'])) $_POST['id'] = $_POST['edit_id'];

  if (isset($_GET['add'])) {
         $main = new outTree($add_FILENAME);
         if ($_POST['save']>0){

               $ri = new Select($db,"insert into opros(name,id_company,parent,cnt) values ( '".$_POST['zagol']."',$id_company,0,0)");
               $new_id = $db->insert_id();
               if ($new_id>0) {
                  foreach($_POST['_otvet'] as $key=>$val)
                       if ($val !== '')
                            $ri = new Select($db,"insert into opros(name,id_company,parent,cnt) values ('".$val."',$id_company,$new_id,0)");


                }
          header('Location: /opros');
         }

  }
// запись
  else if (isset($_POST['id'])) {

           $main = new outTree($i_FILENAME);

           //нажали кнопку сохранить
           if ($_POST['save']>0){
               foreach($_POST as $key=>$val)
                    if (strpos($key,'otvet') >0 ) {
                         $id = substr($key,6);
                         $ri = new Select($db,"update opros set name='$val' where id=$id and id_company=".$id_company);

                    };
               foreach($_POST['new'] as $key=>$val)
                       if ($val !== '')
                            $ri = new Select($db,"insert into opros(name,id_company,parent,cnt) values ('".$val."',$id_company,".$_POST['id'].",0)");



               $ri = new Select($db,"update opros set name='".$_POST['zagol']."' where id=".$_POST['id']." and id_company=".$id_company);
           }

           $ri = new Select($db,"select * from opros where id=".$_POST['id']." and id_company=".$id_company);

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


                  //если юзер - председатель этого ТСЖ
                  if ($user_company == $id_company)  $sub->addField('edit','');
                  $main->addField('sub',&$sub);


          };
          //если юзер - председатель этого ТСЖ
          if ($user_company == $id_company)  $main->addField('edit','');//echotree($main);

          $ri->unset_();



    }

// все записи
    else {
           $main = new outTree($s_FILENAME);

           //нажали кнопку удалить
           if ($_POST['del_id']>0){
                  $r1 = new Select($db,'delete from '.$GLOBALS['table_name'].' where id='.$_POST['del_id']);
                  $r1 = new Select($db,'delete from '.$GLOBALS['table_name'].' where parent='.$_POST['del_id']);

           }

           $ri = new Select($db,"select * from opros where parent=0 and id_company=".$id_company);

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


                  //если юзер - председатель этого ТСЖ
                  if ($user_company == $id_company)  $sub->addField('edit','');
                  $main->addField('sub',&$sub);


          };
          //echotree($main);
          if ($user_company == $id_company)  $main->addField('edit','');
          $ri->unset_();




    };

    if (isset($main)) {
                  //  echoTree($main);
                    $site->addField($GLOBALS['currentSection'],&$main);
                    unset($main);
    }

    unset($main);

 ?>

