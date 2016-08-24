<?php


 include('config.php');
 include('add_data.php');
 //include ($inc_path.'/calend.php');

 unset($main);
 $add_FILENAME = $front_html_path.'add_offer.html';
 $all_FILENAME = $front_html_path.'all.html';
 $one_FILENAME = $front_html_path.'one.html';

 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];


 include($inc_path.'/admin_functions.php');


//если зашел поставщик
 if ( $_SESSION ['vendor']>0) {

     //$main = &addInCurrentSection($FILENAME);
     //unset($main->content);

     //поставщики
     if ($_GET['vendors'] >0 && !$_GET['id']>0) $mode='mode_show_vendors';
     else if ($_GET['vendor'] >0 && $_GET['id']>0) $mode='mode_show_one_vendor';
     //Нажали Добавить предложение
     else if (($_GET['id'] >0) || ($_POST['save_offer'] >0)){
         $mode='mode_add_offer';
     } else
          if ($_GET['i'] >0)
               $mode= 'mode_show_one';
          else $mode='mode_show';

      //удаляем рекламу
      unset($site->section4);
    // var_dump($_POST);

     if (($_POST['save_offer'] >0) && ($_GET['id']>0)) {
          $main = &addInCurrentSection($add_FILENAME);
         unset($main->content);

          //проверяем нет ли уже предложения от поставщика
          $r = new Select($db,"select count(*) as cnt from offer where id_zakupki = $_GET[id] and id_vendor=$_SESSION[vendor]");
          $r->next_row();
          if ($r->result('cnt') ==0 ) {
               $message="Ваше предложение добавлено.";
               $r = new Select($db,"insert into offer(id_zakupki,id_vendor,date,offer)
                  values ($_GET[id],$_SESSION[vendor],".time().",'$_POST[offer]')");
               $main->addField('message',$message);
          } else {
               $message="Ваше предложение обновлено.";
               $r = new Select($db,"update offer set offer='$_POST[offer]', date= ".time()."
                  where id_vendor=$_SESSION[vendor] and id_zakupki=$_GET[id]");
               $main->addField('message',$message);
          };
     };
     //echotree($main);
     //режим просмотра всех закупок
     if (($mode=='mode_show')&&(!($_GET['i']>0)))
     {
           $main = &addInCurrentSection($all_FILENAME);
           unset($main->content);
           $main->addField('link','vend_zakupki');

           //$main->addField('mode','mode_show');
           include($inc_path.'/service/class.pager.php');
           if (!isset($_GET['my']))
              if (isset($_GET['status']))
                 $status = $_GET['status'];
              else
                 $status = '1';
         //меню
         add_vendor_menu($main);

         if (isset($_GET['my'])) {
              $table = 'zakupki z,offer o';
              $where = " o.id_zakupki=z.id and o.id_vendor=$_SESSION[vendor]";
         } else  {
              $table = 'zakupki';
              $where = "status=$status";
         };
         if ($pg = Pagers::PrSoZakup($db,$table,$where, $GLOBALS[$modulName.'_fcount'],$_GET['cp'],"/vend_zakupki$link",'date_end','desc')) {
              $pg->addPAGER($main);
              $ri = &$pg->r;
              while ($ri->next_row())
                   addinfo($main,$ri);
              $ri->unset_();
           }
           //echotree($main);
    }
    //режим просмотра одной закупки
     else if (($mode=='mode_show_one')&&($_GET['i']>0)){
          $ri = new Select($db,'select * from zakupki where id='.$_GET['i']);
          if ($ri->next_row()) {
               $main = &addInCurrentSection($one_FILENAME);
               unset($main->content);

              //меню
               add_vendor_menu($main);

               $main->addField('link','vend_zakupki');
               addinfo($main,$ri);
               $r = new Select($db,"select u.*,o.offer,o.date from offer o,users u where u.id=o.id_vendor and id_zakupki=$_GET[i] order by o.date desc");
               while ($r->next_row()) add_offer_info($main,$r);
               if ($r->num_rows==0) $main->addField('no_offers','');
               $r = new Select($db,'update zakupki set watch=watch+1 where id = '.$_GET['i']);
          } else
              header('Location: /error404');
          $ri->unset_();

     }
     //режим добавления предлодения
     else if (($mode=='mode_add_offer')&&($_GET['id']>0)){

         $main = &addInCurrentSection($add_FILENAME);
         unset($main->content);

         $ri = new Select($db,'select * from zakupki where id='.$_GET['id']);
         $ri->next_row();
         $r = new Select($db,'select * from offer where id_vendor='.$_SESSION ['vendor'].' and id_zakupki='.$_GET['id'] );
           $r->next_row();

         addinfo($main,$ri);
         $main->sub->addField('offer',$r->result('offer'));
       // echotree($main);
     }
     //показ поставщиков
     else if ($mode=='mode_show_vendors'  ) {
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
      //один поставщик
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
 } else header('Location: /');
// echotree($main);
  //echo $main->mode;
 ?>