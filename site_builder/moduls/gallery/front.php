<?php

 include('config.php');

 unset($main);
 $FILENAME = $front_html_path.'front.html';

 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];

 include($inc_path.'/classes/class.BF.php');
 include($inc_path.'/admin_functions.php');

  //сжтие картинки
 function image_resize($filename,$new_width,$new_height){

  $filename=substr($filename,1);
  list($width, $height) = getimagesize($filename);

  $ratio_orig = $width/$height;

  if ($new_width/$new_height > $ratio_orig) {
     $new_width = $new_height*$ratio_orig;
  } else {
     $new_height = $new_width/$ratio_orig;
  }

  $image_p = imagecreatetruecolor($new_width, $new_height);
  $image = imagecreatefromjpeg($filename);
  imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
  imagejpeg($image_p, $filename, 100); //50% это качество 0-100%
  //echo $width;
  //exif_thumbnail($filename, $new_width, $new_height, 'jpeg');
  //die;
 };


//если авторизован и председатель этого тсж
 if ( $_SESSION ['user']>0) {
    $r1 = new Select($db,'select * from users where id="'.$user.'"');
    if ($r1->next_row())
    $user_company = $r1->result('id_company');
 };

 //var_dump($_POST);
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
                           $r1 = new Select($db,'delete from galery where id='.$_POST['id']);
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
                         $_FILES['image2'] =  $_FILES['image1'];

                         $values['kn_image1'] = $_POST['kn_image1'];
                         $values['d_image1'] = $_POST['d_image1'];
                         $values['alt1'] = $_POST['alt1'];

//                         var_dump($values);
                         $back = new BF($db,$modulName,$modulCaption,$table_name,$arFiles);
                         $back->saveRecord($values,$_POST['id']);

                         $r1 = new Select($db,'select * from galery where id='.$_POST['id']);
                         if ($r1->next_row()) {
                            image_resize($r1->result('image1'),80,80);
                            image_resize($r1->result('image2'),600,600);
                         };
                         $main->addField('mode','mode_edit');
                  }
                  // сохранить новый отчет
                  else if ($_POST['new_rep_submit']>0){
                         $values['kn_image1'] = $_POST['kn_image1'];
                         $values['id_house'] = $_POST['id_house'];
                         $values['alt1'] = $_POST['alt1'];

                          $_FILES['image2'] =  $_FILES['image1'];

                         $back = new BF($db,$modulName,$modulCaption,$table_name,$arFiles);
                         $back->saveNewRecord($values);


                         $r1 = new Select($db,'select * from galery where id='.$values['id']);
                         if ($r1->next_row()) {
                            image_resize($r1->result('image1'),80,80);
                            image_resize($r1->result('image2'),600,600);
                         };
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
          $ri = new Select($db,"select * from galery where   id=".$_POST['id']);
          if ($ri->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $ri->addFields($sub,$ar=array('id','alt1'));
                  $ri->addFieldsIMG($sub,$ar=array('image1','image2'));
                  $main->addField('sub',&$sub);
                  //echotree($sub);
           };
     } else if ($main->mode=='mode_show'){
          $ri = new Select($db,"select * from galery where pabl=1 and  id_house=".$cookie);
          if ($ri->num_rows() ==0) $main->addField('no_sub','');
          while ($ri->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $ri->addFields($sub,$ar=array('id','alt1','alt2'));
                  $ri->addFieldsIMG($sub,$ar=array('image1','image2'));

                  $main->addField('sub',&$sub);
          };


     }
      else if ($main->mode=='mode_edit'){

         $r1 = new Select($db,'select * from galery o,house h where h.id=o.id_house and h.id_company='.$id_company);
         while ($r1->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $r2 = new Select($db,'select * from street where id='.$r1->result('id_street'));
                  if ($r2->next_row()) $sub->addField('house',$r2->result('name')." ".$r1->result('number')." ".$r1->result('fract'));
                  $r1->addFields($sub,$ar=array('id','name'));
                  $r1->addFieldsIMG($sub,$ar=array('image1','image2'));
                  $main->addField('sub',&$sub);
          };
     };
  }

  else header('Location: /');
 //echotree($main);

 ?>
