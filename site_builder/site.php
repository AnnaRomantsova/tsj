<?php

/**
 * ������� ������ �����
 * @package FRONT

 * @version 3.03.cms - 16.01.2008 15:00
 *
 * �������������� �������
 * .01 �� ��������� �������������� ������� �� �������� error404 <br>
 * .01 � ������� ��������� page - "��������" ����������  <br>
 * .02 ���������� ����������� ������ �������� ������� <br>
 * .03 � ������� ��������� site - "��������" ���������� - ������ ������ ����� <br>
 */

session_start();
header('Last-modified: '.gmdate('D, d M Y H:i:s', time()-3600).' GMT');
header('Content-Language: ru');

ini_set('register_globals', 'off');
include($_SERVER['DOCUMENT_ROOT'].'/setup.php');
include($inc_path.'/initGET.php');
include_once($inc_path.'/db_conect.php');
include_once($inc_path.'/func.front.php');

$site = new outTree();

$site->pageid = $page;
$mainOutTree->addField('site',&$site);
$mainOutTree->addField('datetime',mktime());
$mainOutTree->addField('page',$page);
$mainOutTree->addField('SERVER_NAME',$_SERVER['HTTP_HOST']);


$site->addField('phone',$phone);
$site->addField('index'!=$page ? 'inner' : 'index','');
 //var_dump($_SESSION);

// ����������� �������� � ����, ���� ��� - ������� ������
if ($page > '') {
        $r_sp = new Select($db,'select * from '.$site_table.' where page="'.$page.'" and section="0" and pabl="1"');
    $page_num = $r_sp->num_rows;
}

if (!$page_num) {
        $r_sp->unset_();
        $page='error404';
        $r_sp = new Select($db,'select * from '.$site_table.' where page="'.$page.'" and section="0"');
}

if (!$r_sp->next_row()) echo_error();

$parent_ = $r_sp->result('parent');
if ($parent_ == '135') {}
else {
      // echo  $parent_;
       setcookie("id_house", '');
       $_COOKIE['id_house']='';
};

//���� ���� ������� ����
if (isset($_COOKIE['e_mail']) && !isset($_SESSION ['vendor']) && !isset($_SESSION ['user'])) {
         $r = new Select($db,'select id from users where email="'.addslashes($_COOKIE['e_mail']).'" and pass="'.addslashes($_COOKIE['password']).'"');
         //���� � ����� ��� ��������� �� ���������
         if ($r->next_row()) {
            $_SESSION=array();
            if ($r->result('is_chairman') == '1')  $_SESSION ['user'] = $r->result ( 'id' );
                     $_SESSION ['vendor'] = $r->result ( 'id' );


          } else {
         //������ ������������ ����
            setcookie("e_mail",'');
            setcookie("password",'');
        };
};
/*
//�����������
if (isset($_SESSION['user'])) {
      $site->addField('log','');
      $r = new Select($db,'select * from users where id="'.$_SESSION['user'].'"');
      if ($r->next_row())
           $site->addfield('name',$r->result('name')." ".$r->result('secname'));

} else if (isset($_SESSION['vendor'])) {
        $site->addField('log','');

      $r = new Select($db,'select * from users where id="'.$_SESSION['vendor'].'"');
      if ($r->next_row())
           $site->addfield('name',$r->result('name'));
} else $site->addField('not_log','');
 */
//������ �� ������ �������
 $parent=0;
 $r2 = new Select($db,'select parent from site_tree where page="'.$site->pageid.'"');
 $r2->next_row();
 $parent = $r2->result('parent');

if (($_SESSION ['user']>0) || ($_SESSION ['vendor']>0)) {
 if (($parent!=='135') || ($_COOKIE['id_house'] >0)) {
   // echo "��";
    $site->addField('log_onclick','onclick="del_cookie();"');
    if ($_SESSION ['user']>0) {
       $site->addField('log_link','/about' );
       $site->addField('log_name','������ �������' );
    } else if ($_SESSION ['vendor']>0) {
       $site->addField('log_link','/lk' );
       $site->addField('log_name','������ �������' );
    };

  } else {
       $site->addField('log_link','/auth?exit=1' );
       $site->addField('log_name','�����' );
  };
} else {
       $site->addField('log_link','' );
       $site->addField('log_name','���� � ������ �������' );
       $site->addField('log_id','id="js-enter"' );
};
//��� ������������ ��� �����������
$r = new Select ( $db, 'select * from act_category order by name' );
while ($r->next_row() > 0) {
    unset($sub);
    $sub = new outTree();
    $r->addFields($sub,$ar=array('id','name'));
    //if ($r->result('id')==$act_category) $sub->addfield('selected','selected');
    $site->addField('act_category',$sub);
};

$r = new Select ( $db, 'select * from city order by name' );
while ($r->next_row() > 0) {
    unset($sub);
    $sub = new outTree();
    $r->addFields($sub,$ar=array('id','name'));
    //if ($r->result('id')==$act_category) $sub->addfield('selected','selected');
    $site->addField('city',$sub);
};


  //echo $parent_;

$r_ss  =  new Select($db,'select * from '.$site_table.' where id="'.$parent_.'" and pabl="1"');
if (!$r_ss->next_row())
        die('Internal site Error');

else {
// ������� ����������� ������� � ������� ��� ����
        $r_c = new Select($db,'select count(*) from '.$site_table.' where parent="'.$parent_.'" and pabl="1" and menu="1"');
        $count_ = $r_c->next_row() ? $r_c->result(0) : 0;
        $r_c->unset_();

// ������� ����������� ������� � ������� ��� �����
        $r_c = new Select($db,'select count(*) from '.$site_table.' where parent="'.$parent_.'" and pabl="1"');
        $pcount_ = $r_c->next_row() ? $r_c->result(0) : 0;
        $r_c->unset_();
}

$r_shablon = new Select($db,'select * from '.$shablons_table.' where id="'.$r_sp->result('shablon').'"');
if (!$r_shablon->next_row()) echo_error();

$site_FILENAME = 'front/'.$r_shablon->result('location');
$r_shablon->unset_();


// ���������� ������
$apages = array('index','contacts','map','prices','order','delivery', 'sandwiches', 'refrigerators');
foreach($apages as $value)
  $site->addField( $value!=$page ? 'a_'.$value : 'noa_'.$value, '');


//������������� �����
 if ('index' !=$page ) {
        $flagNameFirstPage = true;
        $path = new outTree();
        $path->addField('first','');

        $ot_last = new outTree();
        $ot_last->addField('name', textFormat(1 == $pcount_ ? $r_ss->result('name') : $r_sp->result('name')) );
        $path->addField('last',&$ot_last);

        if ($pcount_) {
                $rt = new Select($db,'select page from '.$site_table.' where parent="'.$parent_.'" and pabl="1" order by sort limit 1');
                if ($rt->next_row()) {
                    $pageFirst = $rt->result('page');
                        if ($pageFirst == $page) {
                                $path->last->name =  textFormat($flagNameFirstPage ? $r_sp->result('name') : $r_ss->result('name'));
                        }
                        else {
                          $sub = new outTree();
                          $sub->addField('name',textFormat($r_ss->result('name')));
                          $sub->addField('href',$pageFirst);
                      $sub->addField('T','A');
                          $sub->addField('separator','');
                      $path->addField('sub',&$sub);
                          unset($sub);
                        }
                }
                $rt->unset_();
        }

        $site->addField('path',&$path);
 }

// ������������� ����
 include($moduls_root.'/menu/panel.php');
  //include($moduls_root.'/menu/menu_sub.php');


// ������������� ������, ������� �����!
 addSections($site,$ar = array('section1','section2','section3','section4','section5','section6','main_section'));

 //if($page == 'cabinet' or $page =='basket')
 // include($moduls_root.'/menu/menu_sub.php');
 //echo $page;
// ��������� �������������������� �����
$params = array('title','description','keywords');

 // �� ������������������� � ������� - ����� �� ��������
 $paramsNotInit = array();
 foreach($params as $value)
        if (empty($site->$value)) { $paramsNotInit[]=$value; unset($site->$value); }
 $r_sp->addFields($site,$paramsNotInit);

 // �� ������������������� � �������� - ����� �� �������
 $paramsNotInit = array();
 foreach($params as $value)
        if (empty($site->$value)) { $paramsNotInit[]=$value; unset($site->$value); }
 $r_ss->addFields($site,$paramsNotInit);
// echotree($site);

 out::_echo($site,$site_FILENAME);

?>
