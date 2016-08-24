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
  unset($image_p);
  unset($image);
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

// var_dump($_POST);
// выбран дом или личный кабинет
 if ($cookie>0 || $user_company>0) {

     if ($cookie>0) {
          $r = new Select($db,'select * from house where id="'.$cookie.'"');
          if ($r->next_row()) $id_company = $r->result('id_company');
     } else
          $id_company = $user_company;

     $main = &addInCurrentSection($FILENAME);
     unset($main->content);
     //echo $id_company;
     if ( $_SESSION ['user']>0) {

           //если юзер - председатель этого “—∆
           if ($user_company == $id_company)
           {
               if ($_POST['id']>0) {
                  //нажали кнопку удалить одну запись
                  if ($_POST['rep_del']>0){

                        if ($_POST['id']>0) {
                           $back = new BF($db,$modulName,$modulCaption,$table_name,$arFiles);
                           $back->deleteRecord($_POST['id']);
                           $r1 = new Select($db,'delete from tsjnews where id='.$_POST['id']);
                        };
                        $main->addField('mode','mode_edit');
                  }
                  //нажали кнопку редактировать одну запись
                  else if ($_POST['rep_edit']>0){
                        if ($_POST['id']>0)
                            $main->addField('mode','mode_edit_one');
                  }
                  //нажали кнопку сохранить одну запись
                  else if ($_POST['save_submit']>0){

                         $values['name'] = $_POST['name'];
                         $values['preview'] = $_POST['preview'];
                         $values['about'] = $_POST['about'];

                         $values['kn_image1'] = $_POST['kn_image1'];
                         $values['id_house'] = $_POST['id_house'];
                         $values['alt1'] = $_POST['alt1'];
                         $values['d_image1'] = $_POST['d_image1'];

                         $back = new BF($db,$modulName,$modulCaption,$table_name,$arFiles);
                         //var_dump($back);
                         $back->saveRecord($values,$_POST['id']);

                         $r1 = new Select($db,'select * from tsjnews where id='.$_POST['id']);
                            if ($r1->next_row())
                              if ($r1->result('image1')!=='')
                                image_resize($r1->result('image1'),80,80);

                         $main->addField('mode','mode_edit');
                  }
                }
                //создать новую запись
                else if ($_POST['new_rep']>0){
                               $main->addField('mode','mode_new');
                }
                //сохранить новую запись
                else if ($_POST['new_submit']>0){
                            $values['name'] = $_POST['name'];
                            $values['preview'] = $_POST['preview'];
                            $values['about'] = $_POST['about'];
                            $values['id_house'] = $_POST['id_house'];
                            $values['datetime'] = time();
                            $values['watch'] = 0;


                           $values['kn_image1'] = $_POST['kn_image1'];
                           $values['alt1'] = $_POST['alt1'];


                           $back = new BF($db,$modulName,$modulCaption,$table_name,$arFiles);
                           $back->saveNewRecord($values);

                           $r1 = new Select($db,'select * from tsjnews where id='.$values['id']);
                           if ($r1->next_row())
                              if ($r1->result('image1')!=='')
                                image_resize($r1->result('image1'),80,80);

                           $main->addField('mode','mode_edit');
                }
                //если выбран дом
                else if ($cookie>0)
                    if ($_GET['i'] >0) $main->addField('mode','mode_show_one');
                    else $main->addField('mode','mode_show');
                //ничего не нажимали
                else  $main->addField('mode','mode_edit');
           }
           //юзер - не председатель этого “—∆
           else if ($_GET['i'] >0)
              $main->addField('mode','mode_show_one');
           else $main->addField('mode','mode_show');
     }
     else if ($_GET['i'] >0)
         $main->addField('mode','mode_show_one');
     else $main->addField('mode','mode_show');

     switch ($main->mode){
             case 'mode_show_one'     :  unset($site->section4);
             case 'mode_edit'     :  unset($site->section4);
             case 'mode_new'      :  unset($site->section4);
             case 'mode_edit_one' :  unset($site->section4);
     };

     //режим редактирорвани€ одной записи
     if (($_POST['id']>0)&&($main->mode=='mode_edit_one')) {
         $r = new Select($db,'select * from '.$GLOBALS['table_name'].'  where  id='.$_POST['id']);
         if ($r->next_row()) {
                    $r->addFields($main,$ar=array('id','name'));
                    $r->addFieldHTML($main,'about');
                    $r->addFieldHTML($main,'preview');
                    $r->addFieldsIMG($main,$ar=array('image1'));
                    $main->addField('date',date('d.m.Y',$r->result('datetime')));

         };
     //режим редактиоровани€ всех записей
     } else if ($main->mode=='mode_edit'){
          $r1 = new Select($db,'select n.*,h.number,h.fract,h.id_street from tsjnews n,house h where h.id_company='.$id_company.' and h.id=n.id_house order by n.datetime desc,n.id_house');
          while ($r1->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $r1->addFields($sub,$ar=array('id','name'));
                  $r2 = new Select($db,'select * from street where id='.$r1->result('id_street'));
                  if ($r2->next_row()) $sub->addField('house',$r2->result('name')." ".$r1->result('number')." ".$r1->result('fract'));
                 // $r1->addFieldHTML($main,'preview');
                  $sub->addField('date',date('d.m.Y',$r1->result('datetime')));
                  $main->addField('sub',&$sub);
          };
     }
     //реждим добавлени€
     else if ($main->mode=='mode_new') {
          $r1 = new Select($db,'select h.id,h.number,h.fract,s.name from house h,street s where s.id=h.id_street and h.id_company='.$id_company);
          while ($r1->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $r1->addFields($sub,$ar=array('id','name','number'));
                  if ($r1->result('fract') !=='') $sub->addField('fract',$r1->result('fract'));
                  $main->addField('house',&$sub);
          };
     }
     //режим просмотра всех новостей
     else if (($main->mode=='mode_show')&&(!($_GET['i']>0))&&($cookie>0))
     {
           $ri = new Select($db,"select id as cnt from tsjnews n where pabl=1 and n.id_house=".$cookie);
          // echo "select count(*) as cnt from tsjnews n where n.id_house=".$cookie;
           if( $ri->num_rows() > $GLOBALS[$modulName.'_fcount'])
             $main->addField('escho','');


           $sql="select * from tsjnews  where pabl=1 and id_house=".$cookie."  order by datetime desc limit 0,".$GLOBALS[$modulName.'_fcount'];

         //  echo $sql;
           $ri = new Select($db,$sql);
           if ($ri->num_rows() == 0) $main->addField('no_sub','');
           $d = '';
           $d1=1;

           $month=array("€нвар€","феврел€","марта","апрел€","ма€","июн€","июл€","августа","сент€бр€","окт€бр€","но€бр€","декабр€");

           if ($ri->next_row()) {
                          $sub = new outTree();

                          $d1 = date('d.m.Y', $ri->result('datetime'));
                          $d=$d1;
                          $sub1 = new outTree();
                          $ri->addFields($sub1,$ar=array('id','watch'));


                          $ri->addFieldIMG($sub1,'image1');
                          $str=trim($ri->result('name'));
                          $str1=substr_replace($str, strtoupper($str[0]),0,1);
                          $sub1->addField('name',$str);
                          $ri->addFieldHTML($sub1,'preview');
                          $sub1->addField('ntype','tsjnews');

                          $strdate = date("d",$ri->result('datetime'))." ".$month[((int) date("m",$ri->result('datetime'))-1)];
                          if (date('Y',$ri->result('datetime')) !==  date('Y',time())) $strdate.=" ".date('Y',$ri->result('datetime'));
                          $sub->addField('datetime',$strdate);
                          $sub->addField('sub1',&$sub1);
                          unset($sub1);
          };
          while ($ri->next_row()) {
                          unset($sub1);
                          $sub1 = new outTree();
                          $ri->addFields($sub1,$ar=array('id','watch'));

                          $str=trim($ri->result('name'));
                          $str1=substr_replace($str, strtoupper($str[0]),0,1);
                          $sub1->addField('name',$str1);
                          $ri->addFieldIMG($sub1,'image1');
                          $ri->addFieldHTML($sub1,'preview');
                          $sub1->addField('ntype','tsjnews');

                          $d1 = date('d.m.Y', $ri->result('datetime'));
                          if($d!==$d1) {
                                  $d=$d1;
                                  $main->addField('sub',&$sub);
                                  unset($sub);
                                  $sub = new outTree();
                          };
                          $strdate = date("d",$ri->result('datetime'))." ".$month[((int) date("m",$ri->result('datetime'))-1)];
                          if (date('Y',$ri->result('datetime')) !==  date('Y',time())) $strdate.=" ".date('Y',$ri->result('datetime'));
                         // $sub->addField('datetime',$strdate);
                          if(!isset($sub->datetime)) $sub->addField('datetime',$strdate);
                          $sub->addField('sub1',&$sub1);
          };
           if(isset($sub))  {
            $main->addField('sub',&$sub);
            unset($sub);
          }


    }
    //режим просмотра одной новости
     else if (($main->mode=='mode_show_one')&&($_GET['i']>0)){
          $r = new Select($db,'select * from tsjnews where pabl=1 and id='.$_GET['i']);
            if ($r->next_row()) {

                    $r->addFields($site,$ar=array('title','description','keywords'));
                    $r->addFields($main,$ar=array('id','name','alt3'));
                    $r->addFieldHTML($main,'about');
                    $main->addField('date',date('d.m.Y',$r->result('datetime')));
                    //$r->addFieldIMG($main,'image3');

                    $r = new Select($db,'update tsjnews set watch=watch+1 where id = '.$_GET['i']);
                    unset($site->section4);
                    addLast($GLOBALS['site']->path,$main->name);
            }
            else
                        header('Location: /error404');
            $r->unset_();

     }

 } else header('Location: /');
// echotree($main);
  //echo $main->mode;
 ?>