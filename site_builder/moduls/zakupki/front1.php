<?php


 include('config.php');
 include('add_data.php');
 //include ($inc_path.'/calend.php');

 unset($main);
 $vendors_FILENAME = $front_html_path.'vendors.html';
 $vendor_FILENAME = $front_html_path.'vendor.html';
 $edit_FILENAME = $front_html_path.'edit.html';
 $all_FILENAME = $front_html_path.'all.html';
 $one_FILENAME = $front_html_path.'one.html';
 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];

 include($inc_path.'/classes/class.B.php');
 include($inc_path.'/admin_functions.php');

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


     //echo $id_company;
     if ( $_SESSION ['user']>0) {

           //если юзер - председатель этого ТСЖ
           if ($user_company == $id_company)
           {
               if ($_POST['id']>0) {
                  //нажали кнопку удалить одну запись
                  if ($_POST['rep_del']>0){

                        if ($_POST['id']>0) {
                           $back = new B($db,$modulName,$modulCaption,$table_name);
                           $back->deleteRecord($_POST['id']);
                           $r1 = new Select($db,'delete from zakupki where id='.$_POST['id']);
                        };
                        $mode='mode_edit';
                  }
                  //нажали кнопку редактировать одну запись
                  else if ($_POST['rep_edit']>0){
                        if ($_POST['id']>0)
                            $mode='mode_edit_one';
                  }
                  //нажали кнопку сохранить одну запись
                  else if ($_POST['save_submit']>0){
                        // var_dump($_POST);
                         $values['name'] = $_POST['name'];
                         $values['preview'] = $_POST['preview'];
                         $values['about'] = $_POST['about'];
                          $values['status'] = $_POST['status'];
                         //$values['date_begin'] = $_POST['date_begin'];
                         //$values['date_end'] = $_POST['date_end'];
                         $values['id_house'] = $_POST['id_house'];
                         $values['date_begin'] = @mktime(0,0,0,substr($_POST['date_begin'],3,2),substr($_POST['date_begin'],0,2),substr($_POST['date_begin'],6));
                         $values['date_end'] = @mktime(0,0,0,substr($_POST['date_end'],3,2),substr($_POST['date_end'],0,2),substr($_POST['date_end'],6));

                         $back = new B($db,$modulName,$modulCaption,$table_name);
                         $back->saveRecord($values,$_POST['id']);
                         $mode='mode_edit';
                  }
                }
                //создать новую запись
                else if ($_POST['new_rep']>0) {
                   $mode='mode_new';
                }
                //сохранить новую запись
                else if ($_POST['new_submit']>0){
                            $values['name'] = $_POST['name'];
                            $values['about'] = $_POST['about'];
                            $values['id_house'] = $_POST['id_house'];
                            $values['status'] = $_POST['status'];
                            $values['watch'] = 0;
                            $values['date_begin'] = @mktime(0,0,0,substr($_POST['date_begin'],3,2),substr($_POST['date_begin'],0,2),substr($_POST['date_begin'],6));
                            $values['date_end'] = @mktime(0,0,0,substr($_POST['date_end'],3,2),substr($_POST['date_end'],0,2),substr($_POST['date_end'],6));

                            //$values['datetime'] = time();
                            //$values['watch'] = 0;

                           $back = new B($db,$modulName,$modulCaption,$table_name);
                           $back->saveNewRecord($values);

                           $mode='mode_edit';
                }
                //если выбран дом
                else if ($cookie>0)
                    if ($_GET['i'] >0) $mode='mode_show_one';
                     else $mode='mode_show';
                //ничего не нажимали
                else  $mode='mode_edit';
           }
           //юзер - не председатель этого ТСЖ
           else if ($_GET['i'] >0)
              $mode='mode_show_one';
           else $mode='mode_show';
     }
     //поставщики
     else if ($_GET['vendors'] >0 && !$_GET['id']>0) $mode='mode_show_vendors';
        else if ($_GET['vendor'] >0 && $_GET['id']>0) $mode='mode_show_one_vendor';
     else if ($_GET['i'] >0)
         $mode='mode_show_one';
     else $mode='mode_show';

     //echo $mode;
     switch ($mode){
             case 'mode_show_one'     :  unset($site->section4);
             case 'mode_edit'     :  unset($site->section4);
             case 'mode_new'      :  unset($site->section4);
             case 'mode_edit_one' :  unset($site->section4);
     };

     //режим редактирорвания одной записи
     if (($_POST['id']>0)&&($mode=='mode_edit_one')) {
         $main = &addInCurrentSection($edit_FILENAME);
          unset($main->content);
          $main->addField('mode',$mode);

         $r = new Select($db,'select * from '.$GLOBALS['table_name'].'  where  id='.$_POST['id']);
         if ($r->next_row()) {
                    addCalend($main,1);
                    addCalend($main,2);
                    $r->addFields($main,$ar=array('id','name','status'));
                    $main->addField('date_end',date('d.m.Y', $r->result('date_end')));
                    $main->addField('date_begin',date('d.m.Y',$r->result('date_begin')));
                    $r->addFieldHTML($main,'about');
                    $r->addFieldHTML($main,'preview');
                    $r->addFieldsIMG($main,$ar=array('image1'));
                  //  echotree($main);
                   // $main->addField('date_begin',date('d.m.Y',$r->result('date_begin')));
                   // $main->addField('date_end',date('d.m.Y',$r->result('date_end')));

         };
     //режим редактиорования всех записей
     } else if ($mode=='mode_edit'){
          $main = &addInCurrentSection($edit_FILENAME);
          unset($main->content);
          $main->addField('mode',$mode);

          $r1 = new Select($db,'select n.*,h.number,h.fract,h.id_street from zakupki n,house h where h.id_company='.$id_company.' and h.id=n.id_house order by n.date_begin desc,n.id_house');
          while ($r1->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $r1->addFields($sub,$ar=array('id','name','status'));
                  $r2 = new Select($db,'select * from street where id='.$r1->result('id_street'));
                  if ($r2->next_row()) $sub->addField('house',$r2->result('name')." ".$r1->result('number')." ".$r1->result('fract'));
                 // $r1->addFieldHTML($main,'preview');
                  $sub->addField('date_begin',date('d.m.Y',$r1->result('date_begin')));
                  $sub->addField('date_end',date('d.m.Y',$r1->result('date_end')));
                  $main->addField('sub',&$sub);
          };
     }
     //реждим добавления
     else if ($mode=='mode_new') {
          $main = &addInCurrentSection($edit_FILENAME);
          unset($main->content);
          $main->addField('mode',$mode);

          addCalend($main,1);
          addCalend($main,2);
          $main->addField('date_begin',date('d.m.Y',time()));
          $main->addField('date_end',date('d.m.Y',time()));
          $r1 = new Select($db,'select h.id,h.number,h.fract,s.name from house h,street s where s.id=h.id_street and h.id_company='.$id_company);
          while ($r1->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $r1->addFields($sub,$ar=array('id','name','number'));
                  if ($r1->result('fract') !=='') $sub->addField('fract',$r1->result('fract'));
                  $main->addField('house',&$sub);
          };
//
     }
     //режим просмотра всех закупок
     else if (($mode=='mode_show')&&(!($_GET['i']>0))&&($cookie>0))
     {
           include($inc_path.'/service/class.pager.php');
           $main = &addInCurrentSection($all_FILENAME);
           unset($main->content);
           $main->addField('link','zakupki');

           if (isset($_GET['status']))
              $status = $_GET['status'];
           else
              $status = '1';

           //меню
           add_zakupki_menu($main);

           //echotree($main);
           $sql="select * from zakupki  where  id_house=$cookie and status=$status order by date_begin desc limit 0,".$GLOBALS[$modulName.'_fcount'];

         //  echo $sql;
           $ri = new Select($db,$sql);
           if ($ri->num_rows() == 0) $main->addField('no_sub','');

           $table = 'zakupki';
           $where = "status=$status and id_house=$cookie";

           if ($pg = Pagers::PrSoZakup($db,$table,$where, $GLOBALS[$modulName.'_fcount'],$_GET['cp'],"/zakupki$link",'date_end','desc')) {
              $pg->addPAGER($main);
              $ri = &$pg->r;
              while ($ri->next_row())
                   addinfo($main,$ri);
              $ri->unset_();
           };

         //echotree($main);

    }
    //режим просмотра одной закупки
     else if (($mode=='mode_show_one')&&($_GET['i']>0)){

         $ri = new Select($db,'select * from zakupki where id='.$_GET['i']);
         if ($ri->next_row()) {
               $main = &addInCurrentSection($one_FILENAME);
               unset($main->content);

               //меню
               add_zakupki_menu($main);

               $main->addField('link','zakupki');
               addinfo($main,$ri);
               $r = new Select($db,"select u.*,o.offer,o.date from offer o,users u where u.id=o.id_vendor and id_zakupki=$_GET[i] order by o.date desc");
               while ($r->next_row())
                  add_offer_info($main,$r);
               if ($r->num_rows==0) $main->addField('no_offers','');
               $r = new Select($db,'update zakupki set watch=watch+1 where id = '.$_GET['i']);

          } else
              header('Location: /error404');
          $ri->unset_();

     }
      //поставщики
     else if ($_GET['vendors'] >0 && !$_GET['id']>0) $mode='mode_show_vendors';
        else if ($_GET['vendor'] >0 && $_GET['id']>0) $mode='mode_show_one_vendor';

    if ($mode=='mode_show_vendors'  ) {
             $ri = new Select($db,'select * from users where is_chairman<>1 order by name');
             $main = &addInCurrentSection($vendors_FILENAME);
             unset($main->content);
             //меню
             add_zakupki_menu($main);
             while ($ri->next_row()) {

                  //$main->addField('link','zakupki');
                  add_vendors_info($main,$ri);
             };
    }
    else if ($mode=='mode_show_one_vendor' && $_GET['id']>0) {
             $ri = new Select($db,"select * from users where id=$_GET[id] order by name");
             if ($ri->next_row()) {
                  $main = &addInCurrentSection($vendor_FILENAME);
                  unset($main->content);

                  //меню
                  add_zakupki_menu($main);
                  //$main->addField('link','zakupki');
                  add_vendor_info($main,$ri);
                  $r = new Select($db,'update users set watch=watch+1 where id = '.$_GET['id']);
             };
           //  echotree($main);
    }


 }

 else header('Location: /');
// echotree($main);
  //echo $main->mode;
 ?>