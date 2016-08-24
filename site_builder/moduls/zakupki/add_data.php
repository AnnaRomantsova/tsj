<?php
  function addfilters(&$zmain){
      global $db;
      $zmain->addField('filters','' );
      $r = new Select($db,'select * from city order by name ');
      while ( $r->next_row() ) {
          unset($sub);
          $sub = new outTree();
          $r->addFields($sub, $ar=array('id','name') );
          if ( $_GET['id_city'] == $r->result('id') || $_POST['id_city'] == $r->result('id')) $sub->addField('selected','selected' );
          $zmain->addField('city_sub',$sub );
     };
     if (!( $_POST['id_city'] >0 ) && !( $_GET['id_city'] >0 )) $zmain->addField('no_city','' );

     $r = new Select($db,'select * from act_category order by name ');
      while ( $r->next_row() ) {
          unset($sub);
          $sub = new outTree();
          $r->addFields($sub, $ar=array('id','name') );
          if ( $_GET['id_act_category'] == $r->result('id') || $_POST['id_act_category'] == $r->result('id') ) $sub->addField('selected','selected' );
          $zmain->addField('act_category_sub',$sub );
     };
     if (!( $_POST['id_act_category'] >0 ) && !( $_GET['id_act_category'] >0 )) $zmain->addField('no_act_category','' );
     $zmain->addField('filter_link',$_SERVER['REQUEST_URI'] );

 };

 //инфа о закупке
 function addinfo(&$zmain,&$ri){
      global $db;

      unset($sub);
      $sub = new outTree();
      $ri->addFields($sub,$ar=array('id','watch','preview','name'));
      $ri->addFieldHTML($sub,'about');
      $sub->addField('date_begin',date('d.m.Y',$ri->result('date_begin')));
      $sub->addField('date_end',date('d.m.Y',$ri->result('date_end')));
      $days = ceil( ($ri->result('date_end')- time()) /86400);
      if ($days>0)  $sub->addField('days',$days);
      //$sub->addField('ntype','zakupki');

     if ($_SESSION['user'] >0 || $_SESSION['vendor'] >0) {
          $r1 = new Select($db,"select h.number,h.fract,s.name as street,c.name as city,comp.name as company from house h, street s, city c, company comp where h.id_company=comp.id and h.id_street=s.id and s.id_city=c.id and h.id=".$ri->result('id_house'));
          if ($r1->next_row()) {
              $sub->addField('adress',$r1->result('city').", ".$r1->result('street').", ".$r1->result('number')." ".$r1->result('fract'));
              $sub->addField('company',$r1->result('company'));
          };
     } else {
         $sub->addField('adress','Информация для зарегистрированных поставщиков');
         $sub->addField('company','Информация для зарегистрированных поставщиков');
     };
     $r1 = new Select($db,"select count(*) as cnt from offer where id_zakupki=".$ri->result('id') );
     if ($r1->next_row())
         $sub->addField('cnt',$r1->result('cnt'));

     $r1 = new Select($db,"select name from act_category where id=".$ri->result('act_category') );
     if ($r1->next_row())
         $sub->addField('act_category',$r1->result('name'));
     //echotree($sub);
     if ($_SESSION['vendor'] >0) {
         $r1 = new Select($db,"select count(*) as cnt from offer where id_zakupki=".$ri->result('id')." and id_vendor=".$_SESSION['vendor'] );
         $r1->next_row();
         if ( $ri->result('status')>0 && $r1->result('cnt') ==0 ) $sub->addField('vendor','1');
     };
     $zmain->addField('sub',&$sub);

 };

 function addinfo_my(&$zmain,&$ri,$message ){
      global $db;

      unset($sub);
      $sub = new outTree();
      $ri->addFields($sub,$ar=array('id','watch','preview','name'));
      $ri->addFieldHTML($sub,'about');
      $sub->addField('date_begin',date('d.m.Y',$ri->result('date_begin')));
      $sub->addField('date_end',date('d.m.Y',$ri->result('date_end')));

      $sub->addField('ntype','zakupki');

     $r1 = new Select($db,"select h.number,h.fract,s.name as street,c.name as city,comp.name as company from house h, street s, city c, company comp where h.id_company=comp.id and h.id_street=s.id and s.id_city=c.id and h.id=".$ri->result('id_house'));
     if ($r1->next_row()) {
         $sub->addField('adress',$r1->result('city').", ".$r1->result('street').", ".$r1->result('number')." ".$r1->result('fract'));
         $sub->addField('company',$r1->result('company'));
     };

     $r1 = new Select($db,"select count(*) as cnt from offer where id_zakupki=".$ri->result('id') );
     if ($r1->next_row())
         $sub->addField('cnt',$r1->result('cnt'));


     $r = new Select($db,"select * from offer where id_zakupki=".$ri->result('id')." and id_vendor=$_SESSION[vendor] order by date desc");
     if ($r->next_row()) {
         $r->addFields($sub,$ar=array('offer'));
         $sub->addField('date',date('d.m.Y',$r->result('date')));
     };

     if ($message!=='') $sub->addField('message',$message);


     $zmain->addField('sub',&$sub);

 };

  function add_offer_info(&$zmain,&$ri){
      global $db;

      unset($sub);
      $sub = new outTree();
      $ri->addFields($sub,$ar=array('id','watch','name','offer','id_zakupki'));
      $sub->addField('date',date('d.m.Y',$ri->result('date')));
      $ri->addFieldsIMG($sub,$ar=array('image1'));
      //echotree($sub);
      if ($_SESSION['vendor'] == $ri->result('id')) $sub->addField('redact_buttons','');
      //echo "select * from act_category where id=".$ri->result('id');
      $r_off = new Select($db,"select * from act_category where id=".$ri->result('act_category') );
      if ($r_off->next_row())
            $sub->addField('act_category',$r_off->result('name'));

      $zmain->addField('offer',&$sub);
 };

 function add_vendors_info(&$zmain,&$ri){
      global $db;

      unset($sub);
      $sub = new outTree();
      $ri->addFields($sub,$ar=array('id','name','watch'));
      $ri->addFieldsIMG($sub,$ar=array('image1'));

      $r_off = new Select($db,"select * from act_category where id=".$ri->result('act_category') );
      if ($r_off->next_row())
            $sub->addField('act_category',$r_off->result('name'));
      $r_off = new Select($db,"select * from city where id=".$ri->result('id_city') );
      if ($r_off->next_row())
            $sub->addField('city',$r_off->result('name'));
      $zmain->addField('vendors',&$sub);
 };

  function add_vendor_info(&$zmain,&$ri){
      global $db;

      unset($sub);
      $sub = new outTree();
      $ri->addFields($sub,$ar=array('id','email','name','watch'));
      if ($ri->result('inn') !=='' )   $sub->addField('inn',$ri->result('inn'));
      if ($ri->result('fio') !=='' )   $sub->addField('fio',$ri->result('fio'));
      if ($ri->result('adress') !=='' )   $sub->addField('adress',$ri->result('adress'));
      if ($ri->result('link') !=='' )   $sub->addField('link',$ri->result('link'));
      if ($ri->result('about') !=='' )   $sub->addField('about',$ri->result('about'));
      if ($ri->result('tel') !=='' )   $sub->addField('tel',$ri->result('tel'));

      $sub->addField('date',date('d.m.Y',$ri->result('date')));
      $ri->addFieldsIMG($sub,$ar=array('image1'));
      $r_off = new Select($db,"select * from act_category where id=".$ri->result('act_category') );
      if ($r_off->next_row())
            $sub->addField('act_category',$r_off->result('name'));
      $r_off = new Select($db,"select * from city where id=".$ri->result('id_city') );
      if ($r_off->next_row())
            $sub->addField('city',$r_off->result('name'));
      $zmain->addField('vendor',&$sub);
 };

    function add_vendor_menu(&$zmain){
           global $db;
           global $site;
           $sub = new outTree();

           $status=3;
           if ($_GET['i'] >0 ) {
                 //echo 'select * from '.$GLOBALS['table_name'].'  where  id='.$_GET['i'];
                 $r = new Select($db,'select * from '.$GLOBALS['table_name'].'  where  id='.$_GET['i']);
                 if ($r->next_row()) $status = $r->result('status');

                 $r = new Select($db,"select count(*) as cnt from offer  where  id_vendor=$_SESSION[vendor] and id_zakupki=$_GET[i]");
                 if ($r->next_row()) $cnt_offer = $r->result('cnt');
           };

           /*
           $sub->addfield('href','zakupki/status/1');
           $sub->addfield('name','Открытые');
           if ($_SERVER['REQUEST_URI'] == '/zakupki/status/1' || $_SERVER['REQUEST_URI'] == '/zakupki' ) $Ti='S';
             else if ($_GET['i'] >0 && $status==1 &&  $cnt_offer==0) $Ti='SA';
                  else  $Ti='A';
           $sub->addField('T',$Ti);
           $zmain->addField('menu',$sub);

           $sub = new outTree();
           $sub->addfield('href','zakupki/status/0');
           $sub->addfield('name','Закрытые');
           if ($_SERVER['REQUEST_URI'] == '/zakupki/status/0') $Ti='S';
             else if ($_GET['i'] >0 && $status==0) $Ti='SA';
                  else  $Ti='A';
           $sub->addField('T',$Ti);
           $zmain->addField('menu',$sub);
           */
           $sub = new outTree();
           $sub->addfield('href','zakupki_u');
           $sub->addfield('name','Избранные');
           if ($_SERVER['REQUEST_URI'] == '/'.$site->pageid.'/my/1') $Ti='S';
             else if ($_GET['id'] >0 || $cnt_offer==1) $Ti='SA';
               else  $Ti='A';
           $sub->addField('T',$Ti);
           $zmain->addField('menu',$sub);
  };

   function add_zakupki_menu(&$zmain){
           global $db;
           global $site;
           $sub = new outTree();

           $status=3;
           if ($_GET['i'] >0 ) {
                 //echo 'select * from '.$GLOBALS['table_name'].'  where  id='.$_GET['i'];
                 $r = new Select($db,'select * from '.$GLOBALS['table_name'].'  where  id='.$_GET['i']);
                 if ($r->next_row()) $status = $r->result('status');
           };
           //echo $site->pageid;
           //echo $_SERVER['REQUEST_URI'];
           $sub->addfield('href', $site->pageid.'/status/1');
           $sub->addfield('name','Открытые');
           if ($_GET['status'] == 1 || $_SERVER['REQUEST_URI'] == '/'.$site->pageid) $Ti='S';
             else if ($_GET['i'] >0 && $status==1) $Ti='SA';
                  else  $Ti='A';
           //echo $Ti;
           $sub->addField('T',$Ti);
           $zmain->addField('menu',$sub);

           $sub = new outTree();
           $sub->addfield('href',$site->pageid.'/status/0');
           $sub->addfield('name','Закрытые');
           if (isset($_GET['status'] ) && $_GET['status'] == 0 ) $Ti='S';
             else if ($_GET['i'] >0 && $status==0) $Ti='SA';
                  else  $Ti='A';
           $sub->addField('T',$Ti);
           $zmain->addField('menu',$sub);

           $sub = new outTree();
           $sub->addfield('href',$site->pageid.'/vendors/1');
           $sub->addfield('name','Поставщики');
           if ($_GET['vendors'] == 1) $Ti='S';
             else if ($_GET['id'] >0 ) $Ti='SA';
               else  $Ti='A';
           $sub->addField('T',$Ti);
           $zmain->addField('menu',$sub);
  };
 ?>