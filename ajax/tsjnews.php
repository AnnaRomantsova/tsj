<?php

// ������ �� �����������
header("Expires: Mon, 23 May 1995 02:00:00 GTM");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GTM");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//


//� ���� ����� �������� ����� � ������ � ��
require_once("../setup.php");
include_once($inc_path.'/db_conect.php');
include_once($inc_path.'/func.front.php');

if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];
// var_dump($_POST);

// ������ ���
 if ($cookie>0) {
            $r = new Select($db,'select * from house where id="'.$cookie.'"');
            if ($r->next_row()) $id_company=$r->result('id_company');
 } else die;

   $sql1="select * from setup where var='tsjnews_fcount'";
   $r1 = new Select($db,$sql1);
   $r1->next_row();
   $news_count=$r1->result('value');

   $sql1="select count(n.id) as cnt from tsjnews n,house h where pabl=1 and h.id_company=".$id_company." and h.id=n.id_house ";
   $r1 = new Select($db,$sql1);
   $r1->next_row();


   if ( !($news_count >0) ) die;

  //������� ����������
   $from=$_POST['from']*$news_count;

   $sql="select * from tsjnews n,house h where pabl=1 and h.id_company=".$id_company." and h.id=n.id_house order by datetime desc limit 0,$from";


  // echo $sql['sql'];
   $ri = new Select($db,$sql);

   $d = '';
   $d1=1;

   $month=array("������","�������","�����","������","���","����","����","�������","��������","�������","������","�������");
   $main = new outTree();

   //echo $from;
   //die;
   if( $r1->result('cnt') <= $from) {
         // echo "1";
         //  die;
           $main->addfield("theend","<input type='hidden' name='theend'>");
   };

   if ($ri->next_row()) {
  // echo "d";
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
                  //var_dump($sub1);
                  // echo "1";
                  $strdate = date("d",$ri->result('datetime'))." ".$month[((int) date("m",$ri->result('datetime'))-1)];
                  $strdate.=" ".date('Y',$ri->result('datetime'));
                  $sub->addField('datetime',$strdate);
                  $sub->addField('sub1',&$sub1);
                  unset($sub1);
  };
  while ($ri->next_row()) {
                  unset($sub1);
                  // echo "1";
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
                  $strdate.=" ".date('Y',$ri->result('datetime'));
                 // $sub->addField('datetime',$strdate);
                  if(!isset($sub->datetime)) $sub->addField('datetime',$strdate);
                  $sub->addField('sub1',&$sub1);


  };

  if(isset($sub))  {
    $main->addField('sub',&$sub);
    unset($sub);
  }
  $ri->unset_();

 // var_dump($main);
   $site_FILENAME = 'front/tsjnews/panel_ajax.html';
   out::_echo($main,$site_FILENAME);
?>