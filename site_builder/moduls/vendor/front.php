<?php

  include('config.php');
  include($inc_path.'/classes/class.BF.php');
  include($inc_path.'/admin_functions.php');
  include($inc_path.'/img.php');

  //echo $front_html_path;
  $FILENAME = $front_html_path.'panel.html';
  $reg_FILENAME = 'front/auth/reg.html';
  $forgot_FILENAME = $front_html_path.'forgot.html';
  $auth_FILENAME = $front_html_path.'auth.html';

  //echo "!!!!!!!!";
  //var_dump($_SESSION);

  $patch=$HTTP_SERVER_VARS[HTTP_REFERER];

  $message = '';
  unset($main);
  if (!(isset($_SESSION['vendor']))) {
      $main = new outTree($auth_FILENAME);
  };
 // echotree($main);
  if ($_GET['forgot']>0) {
       $main = new outTree($auth_FILENAME);
  }
  else if ($_GET['reg']>0) {
       $main = new outTree($reg_FILENAME);
       $r1 = new Select ( $db, 'select * from act_category order by name' );
       while ($r1->next_row() > 0) {
                 unset($sub);
                 $sub = new outTree();
                 $r1->addFields($sub,$ar=array('id','name'));
                 if ($r1->result('id')==$r->result('act_category')) $sub->addfield('selected','selected');
                 $main->addField('act_category',$sub);
       };
       $r1 = new Select ( $db, 'select * from city order by name' );
       while ($r1->next_row() > 0) {
                 unset($sub);
                 $sub = new outTree();
                 $r1->addFields($sub,$ar=array('id','name'));
                 if ($r1->result('id')==$r->result('id_city')) $sub->addfield('selected','selected');
                 $main->addField('city',$sub);
       };
  }


  //если поставщик нажал Сохранить в личном кабинете
 else if (isset($_POST['save']))  {
         //echo $_SESSION['vendor'];
         foreach ( $_POST as $key => $value)
               $$key= htmlspecialchars ( addslashes ($value));

         $r = new Select($db,"update users set name = '$name', email='$email',fio='$fio',
                               act_category='$act_category',inn='$inn',link='$link',adress='$adress',tel='$tel',id_city=$city,about='$about'
                               where id=$_SESSION[vendor]");

         $values['kn_image1'] = $_POST['kn_image1'];
         $values['d_image1'] = $_POST['d_image1'];
         $back = new BF($db,$modulName,$modulCaption,'users',$arFiles);
         $back->saveRecord($values,$_SESSION['vendor']);

         $r1 = new Select($db,'select * from users where id='.$_SESSION['vendor']);
         if ($r1->next_row()) image_resize($r1->result('image1'),80,80);
         //var_dump($_FILES);
         if ($_POST['pass1'] !== '' )
           if ($_POST['pass1'] == $_POST['pass2']) {
              $r = new Select($db,"update users set pass = '".md5($pass1)."', pass_text= '$pass1'
                                   where id=$_SESSION[vendor]");
           } else $message = 'Пароли не совпадают.';
         if ($message=='') $message = 'Данные сохранены.';



       //если поставщик то показать личный кабинет
       if (isset($_SESSION['vendor']))  {
          $main = new outTree($FILENAME);
          $r = new Select($db,'select * from users where id="'.$_SESSION['vendor'].'"');
          $r->next_row();
          $r->addFields($main,$ar=array('id','name','email','fio','inn','link','adress','tel','about'));
          $r->addFieldsIMG($main,$ar=array('image1'));

          $r1 = new Select ( $db, 'select * from act_category order by name' );
          while ($r1->next_row() > 0) {
                    unset($sub);
                    $sub = new outTree();
                    $r1->addFields($sub,$ar=array('id','name'));
                    if ($r1->result('id')==$r->result('act_category')) $sub->addfield('selected','selected');
                    $main->addField('act_category',$sub);
          };
          $r1 = new Select ( $db, 'select * from city order by name' );
          while ($r1->next_row() > 0) {
                    unset($sub);
                    $sub = new outTree();
                    $r1->addFields($sub,$ar=array('id','name'));
                    if ($r1->result('id')==$r->result('id_city')) $sub->addfield('selected','selected');
                    $main->addField('city',$sub);
          };
          if ($message!=='') $main->addField('message',$message);
        }
  } else {
        //если поставщик то показать личный кабинет
       if (isset($_SESSION['vendor']))  {
            //если поставщик Удалить картинку
           if (isset($_POST['del_photo']))  {
                     $values['d_image1'] = "1";
                 $back = new BF($db,$modulName,$modulCaption,'users',$arFiles);
                 $back->saveRecord($values,$_SESSION['vendor']);
          }


          $main = new outTree($FILENAME);
          $r = new Select($db,'select * from users where id="'.$_SESSION['vendor'].'"');
          $r->next_row();
          $r->addFields($main,$ar=array('id','name','email','fio','inn','link','adress','tel','about'));
          $r->addFieldsIMG($main,$ar=array('image1'));

          $r1 = new Select ( $db, 'select * from act_category order by name' );
          while ($r1->next_row() > 0) {
                    unset($sub);
                    $sub = new outTree();
                    $r1->addFields($sub,$ar=array('id','name'));
                    if ($r1->result('id')==$r->result('act_category')) $sub->addfield('selected','selected');
                    $main->addField('act_category',$sub);
          };
          $r1 = new Select ( $db, 'select * from city order by name' );
          while ($r1->next_row() > 0) {
                    unset($sub);
                    $sub = new outTree();
                    $r1->addFields($sub,$ar=array('id','name'));
                    if ($r1->result('id')==$r->result('id_city')) $sub->addfield('selected','selected');
                    $main->addField('city',$sub);
          };
          if ($message!=='') $main->addField('message',$message);
        }
  };

  $site->addField($GLOBALS['currentSection'],&$main);
  unset($main);

 ?>