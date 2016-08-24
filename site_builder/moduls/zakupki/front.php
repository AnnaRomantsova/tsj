<?php


 include('config.php');
 include('add_data.php');
 include($inc_path.'/service/class.pager.php');
 //include ($inc_path.'/calend.php');

 unset($main);
 $vendors_FILENAME = $front_html_path.'vendors.html';
 $vendor_FILENAME = $front_html_path.'vendor.html';
 $edit_FILENAME = $front_html_path.'edit.html';
 $all_FILENAME = $front_html_path.'all.html';
 $one_FILENAME = $front_html_path.'one.html';
 $my_FILENAME = $front_html_path.'my.html';
 $add_FILENAME = $front_html_path.'add_offer.html';
 $my_edit_FILENAME = $front_html_path.'my_edit.html';

 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];

 include($inc_path.'/classes/class.B.php');
 include($inc_path.'/admin_functions.php');


 if ($cookie>0) $user_mode = 'house';
   else if ($_SESSION ['user']>0 && $site->pageid=='zakupki_u') $user_mode = 'user';
     else if ($_SESSION ['vendor']>0 && $site->pageid=='zakupki_u')  $user_mode = 'vendor';
       else $user_mode = 'guest';

 if ( $_SESSION ['user']>0) {
    $r1 = new Select($db,'select * from users where id="'.$user.'"');
    if ($r1->next_row())
    $user_company = $r1->result('id_company');
 };
 //echo $user_mode;
 //echo "dd";
 //echotree ($site->pageid);

 function get_city($id_house){
         global $db;
         $r1 = new Select($db,'select s.id_city from house h,street s where s.id=h.id_street and h.id='.$id_house);
         $r1->next_row();
         return $r1->result('id_city');
 };

 switch ($user_mode) {
     case 'house' : {
                if ($_GET['vendors'] >0 && !$_GET['id']>0) $mode='mode_show_vendors';
                  else if ($_GET['add_offer'] >0) $mode='mode_add_offer';
                    else if ($_GET['vendor'] >0 && $_GET['id']>0) $mode='mode_show_one_vendor';
                      else if ($_GET['i'] >0) $mode='mode_show_one';
                        else  $mode='mode_show';
                 break;
               };
    case 'guest' : {
                if ($_GET['vendors'] >0 && !$_GET['id']>0) $mode='mode_show_vendors';
                    else if ($_GET['vendor'] >0 && $_GET['id']>0) $mode='mode_show_one_vendor';
                      else if ($_GET['add_offer'] >0) $mode='mode_add_offer';
                        else if ($_GET['i'] >0) $mode='mode_show_one';
                          else  $mode='mode_show';
                 break;
               };
     case 'user'  : {
                      if ($_POST['id']>0) {
                        //нажали кнопку удалить одну запись
                        if ($_POST['rep_del']>0){

                              if ($_POST['id']>0) {
                                 $back = new B($db,$modulName,$modulCaption,$table_name);
                                 $back->deleteRecord($_POST['id']);
                                 $r1 = new Select($db,'delete from zakupki where id='.$_POST['id']);
                                 $r1 = new Select($db,'delete from offer where id_zakupki='.$_POST['id']);
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
                               $values['name'] = $_POST['name'];
                               $values['preview'] = $_POST['preview'];
                               $values['about'] = $_POST['about'];
                               $values['status'] = $_POST['status'];
                               $values['act_category'] = $_POST['act_category'];
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
                                  $values['act_category'] = $_POST['act_category'];
                                  $values['watch'] = 0;
                                  $values['date_begin'] = @mktime(0,0,0,substr($_POST['date_begin'],3,2),substr($_POST['date_begin'],0,2),substr($_POST['date_begin'],6));
                                  $values['date_end'] = @mktime(0,0,0,substr($_POST['date_end'],3,2),substr($_POST['date_end'],0,2),substr($_POST['date_end'],6));
                                   $values['id_city'] = get_city($_POST['id_house']);
                              // echo $values['id_city'];
                                 $back = new B($db,$modulName,$modulCaption,$table_name);
                                 $back->saveNewRecord($values);

                                 $mode='mode_edit';
                      }
                      else if ($_GET['offers'] >0) $mode='mode_show_offers';
                      //если выбран дом
                         else if ($cookie>0)
                             if ($_GET['i'] >0) $mode='mode_show_one';
                              else $mode='mode_show';
                      //ничего не нажимали
                      else  $mode='mode_edit';

                     break;
                   };
     case 'vendor': {
                  if ($_GET['vendors'] >0 && !$_GET['id']>0) $mode='mode_show_vendors';
                    else if ($_GET['add_offer'] >0) $mode='mode_add_offer';
                      else if ($_GET['vendor'] >0 && $_GET['id']>0) $mode='mode_show_one_vendor';
                        else if ($_GET['i'] >0) $mode='mode_show_one';
                          else if ($site->pageid=='zakupki_u') $mode='mode_show_my';
                              else  $mode='mode_show';


     };
  };
 //echo $mode;
  if ($cookie>0) {
          $r = new Select($db,'select * from house where id="'.$cookie.'"');
          if ($r->next_row()) $id_company = $r->result('id_company');
  } else
          $id_company = $user_company;


 switch ($mode) {
//если авторизован и председатель этого тсж
    case ($mode=='mode_edit') : {
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
                        $sub->addField('date_begin',date('d.m.Y',$r1->result('date_begin')));
                        $sub->addField('date_end',date('d.m.Y',$r1->result('date_end')));
                        $r2 = new Select($db,'select count(*) as cnt from offer where id_zakupki='.$r1->result('id'));
                        $r2->next_row();
                        $sub->addField('cnt_offer',$r2->result('cnt'));
                        if ($r2->result('cnt')>0) $sub->addField('link','');
                        $main->addField('sub',&$sub);
                };
                break;
          };
   case ($mode=='mode_edit_one') : {
              //режим редактирорвания одной записи
              if (($_POST['id']>0)) {
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
                             $r2 = new Select ( $db, 'select * from act_category order by name' );
                             while ($r2->next_row() > 0) {
                                       unset($sub1);
                                       $sub1 = new outTree();
                                       $r2->addFields($sub1,$ar=array('id','name'));
                                       if ($r2->result('id')==$r->result('act_category')) $sub1->addfield('selected','selected');
                                       $main->addField('act_category',$sub1);
                             };
                  };
              //режим редактиорования всех записей
              } else {
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
              };
              break;
     };
     //режим добавления предлодения
     case 'mode_add_offer': {
        if ($_SESSION ['vendor']>0){

         $main = &addInCurrentSection($add_FILENAME);
         unset($main->content);

         if ($_POST['save_offer'] >0)  {

                  //проверяем нет ли уже предложения от поставщика
                  $r = new Select($db,"select count(*) as cnt from offer where id_zakupki = $_GET[add_offer] and id_vendor=$_SESSION[vendor]");
                  $r->next_row();
                  if ($r->result('cnt') ==0 ) {
                       $message="Ваше предложение добавлено.";
                       $r = new Select($db,"insert into offer(id_zakupki,id_vendor,date,offer)
                          values ($_GET[add_offer],$_SESSION[vendor],".time().",'$_POST[offer]')");
                       $main->addField('message',$message);
                  } else {
                       $message="Ваше предложение обновлено.";
                       $r = new Select($db,"update offer set offer='$_POST[offer]', date= ".time()."
                          where id_vendor=$_SESSION[vendor] and id_zakupki=$_GET[id]");
                       $main->addField('message',$message);
                  };
         };


         $ri = new Select($db,'select * from zakupki where id='.$_GET['add_offer']);
         $ri->next_row();
         $r = new Select($db,'select * from offer where id_vendor='.$_SESSION ['vendor'].' and id_zakupki='.$_GET['add_offer'] );
           $r->next_row();

         addinfo($main,$ri);
         $main->sub->addField('offer',$r->result('offer'));
        };
        break;
     };
     //режим показа предложений для закупки
     case 'mode_show_offers': {
       if ($_GET['offers']>0){
            $ri = new Select($db,'select * from zakupki where id='.$_GET['offers']);
            if ($ri->next_row()) {
                  $main = &addInCurrentSection($one_FILENAME);
                  unset($main->content);

                  addinfo($main,$ri);
                  $r = new Select($db,"select u.*,o.offer,o.date from offer o,users u where u.id=o.id_vendor and id_zakupki=$_GET[offers] order by o.date desc");
                  while ($r->next_row())
                     add_offer_info($main,$r);
                  if ($r->num_rows==0) $main->addField('no_offers','');
                  //$r = new Select($db,'update zakupki set watch=watch+1 where id = '.$_GET['i']);

             } else
                 header('Location: /error404');
             $ri->unset_();
         };
         break;
     };
     //реждим добавления
     case 'mode_new' : {
          $main = &addInCurrentSection($edit_FILENAME);
          unset($main->content);
          $main->addField('mode',$mode);

          addCalend($main,1);
          addCalend($main,2);
          $main->addField('date_begin',date('d.m.Y',time()));
          $main->addField('date_end',date('d.m.Y',time()));
          $r2 = new Select ( $db, 'select * from act_category order by name' );
          while ($r2->next_row() > 0) {
                    unset($sub1);
                    $sub1 = new outTree();
                    $r2->addFields($sub1,$ar=array('id','name'));
                    if ($r2->result('id')==$r->result('act_category')) $sub1->addfield('selected','selected');
                    $main->addField('act_category',$sub1);
          };
          $r1 = new Select($db,'select h.id,h.number,h.fract,s.name from house h,street s where s.id=h.id_street and h.id_company='.$id_company);
          while ($r1->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $r1->addFields($sub,$ar=array('id','name','number'));
                  if ($r1->result('fract') !=='') $sub->addField('fract',$r1->result('fract'));
                  $main->addField('house',&$sub);
          };
       break;
     };
     //режим просмотра всех закупок
     case 'mode_show' : {
                 $main = &addInCurrentSection($all_FILENAME);
                 unset($main->content);
                 if (!isset($_GET['my']))
                    if (isset($_GET['status']))
                         $status = $_GET['status'];
                      else
                         $status = '1';
                         //echo $status;
                 //меню
                 add_zakupki_menu($main);
                 $main->addField('ismenu','');

                 //if ($_SESSION['user'] >0 || $_SESSION['vendor'] >0) $main->addField('guest','');
                 //фильтры
                 if (!($cookie>0))  addfilters($main);
                 //var_dump($_SERVER);
                 $table = 'zakupki z'; $where='1=1';
                 $id_city=0;
                 if ($_GET['id_city']>0) $id_city=$_GET['id_city'];
                 if ($_POST['id_city']>0) $id_city=$_POST['id_city'];

                 $id_act_category=0; $link='';
                 if ($_GET['id_act_category']>0) $id_act_category=$_GET['id_act_category'];
                 if ($_POST['id_act_category']>0) $id_act_category=$_POST['id_act_category'];

                 if ($cookie>0)   $where .= " and id_house=$cookie";

                 if ($id_city>0) {
                      $table .= ' ,house h,street s';
                      $where .= " and z.id_house=h.id and h.id_street=s.id and s.id = $id_city";
                      $link = "/id_city/$id_city";
                      //$patch = '';
                 };

                 if ($id_act_category>0) {
                      $where .= " and z.act_category = $id_act_category";
                      $link .= "/id_act_category/$id_act_category";
                 };

                 if (isset($_GET['my'])) {
                      $table .= ',offer o';
                      $where .= " and o.id_zakupki=z.id and o.id_vendor=$_SESSION[vendor]";
                 } else {
                      $where .= " and status=$status";
                      $link .= "/status/$status";
                 };
                // echo $site->pageid;
                // echo $link;
                 if ($pg = Pagers::PrSoZakup($db,$table,$where, $GLOBALS[$modulName.'_fcount'],$_GET['cp'],'/'.$site->pageid.$link,'date_end','desc')) {
                      $pg->addPAGER($main);
                      $ri = &$pg->r;
                      while ($ri->next_row())
                           addinfo($main,$ri);
                      $ri->unset_();
                 }  else $main->addField('no_sub','');

          break;
    };
    //режим просмотра избранных
    case 'mode_show_my': {
                 if ($_GET['edit_offer'] >0 )   $main = &addInCurrentSection($my_edit_FILENAME);
                    else $main = &addInCurrentSection($my_FILENAME);
                 unset($main->content);
                 //add_vendor_menu($main);

                 if ($_GET['del_offer'] >0 ) {
                    if ($_GET['id']>0) $r1 = new Select($db,"delete from offer where id_vendor=$_SESSION[vendor] and id_zakupki=$_GET[id]");
                 };
                 //if ($_GET['edit_offer'] >0 ) {
                 if ($_POST['save_offer']>0 and $_POST['id']>0) {

                        $message="Ваше предложение обновлено.";
                        $r = new Select($db,"update offer set offer='$_POST[offer]', date= ".time()."
                                              where id_vendor=$_SESSION[vendor] and id_zakupki=$_POST[id]");
                     };

                 if ($_GET['edit_offer'] >0 and $_GET['id']>0) {
                        $ri = new Select($db,"select o.*,z.name,z.id from zakupki z,offer o where o.id_zakupki=z.id  and o.id_vendor=$_SESSION[vendor] and o.id_zakupki=$_GET[id]");

                        $ri->next_row();
                        $sub=new outtree();
                        $ri->addFields($sub,$ar=array('offer','name','id'));
                        $main->addField('sub',$sub);
//                        echotree($main);
                 } else  {

                             $table = 'zakupki z,offer o';
                             $where = " o.id_zakupki=z.id and o.id_vendor=$_SESSION[vendor]";

                             if ($pg = Pagers::PrSoZakup($db,$table,$where, $GLOBALS[$modulName.'_fcount'],$_GET['cp'],"/zakupki_u",'date_end','desc')) {
                                  $pg->addPAGER($main);
                                  $ri = &$pg->r;
                                  while ($ri->next_row()) {
                                       if ($_POST['id'] == $ri->result('id')) addinfo_my($main,$ri,$message);
                                         else  addinfo_my($main,$ri,'');
                                  };
                                  $ri->unset_();
                             } else $main->addField('no_sub','');
                };
          //};
          break;
    };
    //режим просмотра одной закупки
    case 'mode_show_one': {
         if ($_GET['i']>0){
            $ri = new Select($db,'select * from zakupki where id='.$_GET['i']);
            if ($ri->next_row()) {
                  $main = &addInCurrentSection($one_FILENAME);
                  unset($main->content);

                  if ($site->pageid !== 'zakupki_u') {add_zakupki_menu($main); $main->addField('ismenu','');};

                  addinfo($main,$ri);
                  $r = new Select($db,"select u.*,o.offer,o.date,o.id_zakupki from offer o,users u where u.id=o.id_vendor and id_zakupki=$_GET[i] order by o.date desc");
                  while ($r->next_row())
                     add_offer_info($main,$r);
                  if ($r->num_rows==0) $main->addField('no_offers','');
                  $r = new Select($db,'update zakupki set watch=watch+1 where id = '.$_GET['i']);

             } else
                 header('Location: /error404');
             $ri->unset_();
         };
         break;
     };

    case 'mode_show_vendors': {
             $ri = new Select($db,'select * from users where is_chairman<>1 order by name');
             $main = &addInCurrentSection($vendors_FILENAME);
             unset($main->content);
             //фильтры
              addfilters($main); $main->addField('ismenu','');
             //меню
             add_zakupki_menu($main);

             $where='is_chairman<>1'; $link='';
             $id_city=0;
             if ($_GET['id_city']>0) $id_city=$_GET['id_city'];
             if ($_POST['id_city']>0) $id_city=$_POST['id_city'];

             $id_act_category=0; $link='';
             if ($_GET['id_act_category']>0) $id_act_category=$_GET['id_act_category'];
             if ($_POST['id_act_category']>0) $id_act_category=$_POST['id_act_category'];

             if ($id_city>0) {
                     $where .= " and id_city = $id_city";
                     $link = "/id_city/$id_city";
             };

                 if ($id_act_category>0) {
                      $where .= " and act_category = $id_act_category";
                      $link .= "/id_act_category/$id_act_category";
                 };

             if ($pg = Pagers::PrSoZakup($db,'users',$where, $GLOBALS[$modulName.'_fcount'],$_GET['cp'],'/'.$site->pageid.'/vendors/1'.$link,'name','asc')) {
                 $pg->addPAGER($main);
                 $ri = &$pg->r;
                 while ($ri->next_row())
                    add_vendors_info($main,$ri);
             } else $main->addField('no_sub','');
             break;
    }
    case 'mode_show_one_vendor': {
             if ($_GET['id']>0) {
                if ($_GET['id'] == $_SESSION ['vendor'])  header('Location: /lk');
                $ri = new Select($db,"select * from users where id=$_GET[id] order by name");
                if ($ri->next_row()) {
                     $main = &addInCurrentSection($vendor_FILENAME);
                     unset($main->content);

                     //меню
                     if ($site->pageid !== 'zakupki_u') add_zakupki_menu($main);
                     //$main->addField('link','zakupki');
                     add_vendor_info($main,$ri);
                     $r = new Select($db,'update users set watch=watch+1 where id = '.$_GET['id']);
                };
             };
             break;
   };

 };
  $main->addField('link',$site->pageid);
 //else header('Location: /');
// echotree($main);
  //echo $main->mode;
 ?>