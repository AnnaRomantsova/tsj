<?php

 include('config.php');
 unset($main);
 $main_FILENAME = $front_html_path.'panel.html';

 //var_dump($_POST );
 $main = &addInCurrentSection($main_FILENAME,false);

 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];

  // ���� ��������� ����� ������ �� ������ �� ������ ����
 if ((( $_POST['id_house'] >0 ) || ( $_POST['id_street'] >0 ) || ( $_POST['id_city'] >0 )) && ( $_POST['change_house'] !== '1')) {
          setcookie("id_house", '');
          $_COOKIE['id_house']='';
          $cookie=0;
          //echo "�����";
 };

 // ���� ��������� ����� ������ �� ������ �� ������ ����
 if (( $_POST['id_house'] >0 ) && ( $_POST['change_house'] == '1') ) {
          setcookie("id_house", $_POST['id_house']);
          $cookie= $_POST['id_house'];
          //echo "������ ����";
          //echo $_COOKIE['id_house'];
          header("location: about");
 };

 $parent=0;
 $r2 = new Select($db,'select parent from site_tree where page="'.$site->pageid.'"');
 $r2->next_row();
 $parent = $r2->result('parent');

 if ((($parent!=='135')))echo $_COOKIE['id_house'];
 //echo $parent;


  //echo $_COOKIE['id_house'];

 //��� ��� �� ������
 if (!( $cookie>0)) {
        //echo "����1";
     //   $first_city =0;

        $r = new Select($db,'select * from city order by name ');
        while ( $r->next_row() ) {
                     unset($sub);
                    // if (!($first_city>0)) $first_city = $r->result('id');
                     $sub = new outTree();
                     $r->addFields($sub, $ar=array('id','name') );
                     if ( $_POST['id_city'] == $r->result('id') ) $sub->addField('selected','selected' );

                     //if ( $r->num_rows() ==1) $sub->addField('selected','selected' );
                     $main->addField('sub_city',$sub );
        };

        if (!( $_POST['id_city'] >0 )) $main->addField('no_city','' );


        $where='';
        //$first_street =0;
        if ( $_POST['id_city'] >0 )
        {
            $where = 'where id_city= '.$_POST['id_city'];
            // else  $where = 'where id_city= '.$first_city;
           $r = new Select($db,'select * from street '.$where.' order by id_city,name');
           while ( $r->next_row() ) {
                   unset($sub);
                   $sub = new outTree();
                 //  if (!($first_street>0)) $first_street = $r->result('id');
                   $r->addFields($sub, $ar=array('id','name') );
                   if ( $_POST['id_street']  == $r->result('id')) $sub->addField('selected','selected' );

                   //if ( $r->num_rows() ==1) $sub->addField('selected','selected' );
                   $main->addField('sub_street',$sub );
           };
        }
        if (!( $_POST['id_street'] >0 )) $main->addField('no_street','' );

        $where='';
        if ( $_POST['id_street'] >0 )
        {
               if ( $_POST['id_street'] >0 ) $where = 'where id_street= '.$_POST['id_street'];
                // else $where = 'where id_street= '.$first_street;

               $r = new Select($db,'select * from house '.$where.' order by number,fract ');
               while ( $r->next_row() ) {
                          unset($sub);
                          $sub = new outTree();
                          $r->addFields($sub, $ar=array('id','number') );
                          if ($r->result('fract')!=='')
                                if (is_numeric($r->result('fract'))) $sub->addField('fract','/'.$r->result('fract') );
                                  else $sub->addField('fract',' '.$r->result('fract') );

                          if ( $_POST['id_house']  == $r->result('id') ) $sub->addField('selected','selected' );
                          $main->addField('sub_house',$sub );
               };
        }
        if (!( $_POST['id_house'] >0 )) $main->addField('no_house','' );

       //���� ����������� � ������������ ����� ���
        if ( $_SESSION ['user']>0) {


           $r1 = new Select($db,'select c.name from users u,company c where c.id=u.id_company and u.id="'.$user.'"');
          // echotree($site->pageid);

           if ($r1->next_row() && ($parent=='135'))
                 $main->addField('logo','������ �������: '.$r1->result('name'));
           else
                 $main->addField('logo','������������� ������ ��� ������������� �����' );
        } else
           $main->addField('logo','������������� ������ ��� ������������� �����' );
        if($_POST['id_house']>0)  setcookie("id_house", $_POST['id_house']);
       // $r->unset_();
 }
 //��� ������
 else {
     // echo "����2";
     $r = new Select($db,'select * from house where id = '. $cookie.' ');
     if ( $r->next_row() ) {
         $house_id  =  $cookie;
         $street_id = $r->result('id_street');
         if ($street_id > 0 )
              {
                  $r = new Select($db,'select * from street where id = '.$street_id.' ');
                  if ($r->next_row()) $city_id=$r->result('id_city');
              };
     };

     $r = new Select($db,'select * from city order by name ');
     while ( $r->next_row() ) {
                unset($sub);
                $sub = new outTree();
                $r->addFields($sub, $ar=array('id','name') );
                if ( $r->result('id') == $city_id) $sub->addField('selected','selected' );
                $main->addField('sub_city',$sub );
     };

     $where='';
     if ( $city_id >0 ) $where = 'where id_city= '.$city_id;
     $r = new Select($db,'select * from street '.$where.' order by id_city,name');
     while ( $r->next_row() ) {
                unset($sub);
                $sub = new outTree();
                $r->addFields($sub, $ar=array('id','name') );
                if ( $street_id  == $r->result('id')) $sub->addField('selected','selected' );
                $main->addField('sub_street',$sub );
    };

     if ( $street_id >0 ) $where = 'where id_street= '.$street_id;
     $r = new Select($db,'select * from house '.$where.' order by number,fract ');
     while ( $r->next_row() ) {
                unset($sub);
                $sub = new outTree();
                $r->addFields($sub, $ar=array('id','number') );
                if ($r->result('fract')!=='')
                   if (is_numeric($r->result('fract'))) $sub->addField('fract','/'.$r->result('fract') );
                     else $sub->addField('fract',' '.$r->result('fract') );

                if ( $house_id  == $r->result('id') ) {
                        $sub->addField('selected','selected' );
                        if ($r->result('id_company') >0 ) {
                           $r2 = new Select($db,'select * from company where id = '.$r->result('id_company'));
                           if ( $r2->next_row() ) $main->addField('logo',$r2->result('name') );
                        };
                };
                $main->addField('sub_house',$sub );
     };

     $r->unset_();

 };

 $month=array("������","�������","�����","������","���","����","����","�������","��������","�������","������","�������");
 $main->addField('date',date("d",time())." ".$month[((int) date("m",time())-1)]." ".date("Y",time()));
//echoTree($main);


?>
