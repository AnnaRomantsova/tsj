<?php

 include('config.php');

 unset($main);
 $s_FILENAME = $front_html_path.'front.html';
 $i_FILENAME = $front_html_path.'item.html';

// ������
    if (isset($_GET['i'])) {
            $r = new Select($db,'select * from news where id="'.addslashes($_GET['i']).'" and pabl="1" ');
            if ($r->next_row()) {
                    $main = &addInCurrentSection($i_FILENAME);
                    unset($main->content);
                    $r->addFields($site,$ar=array('title','description','keywords'));
                    $r->addFields($main,$ar=array('id','name'));
                    $r->addFieldHTML($main,'about');
                     //   $main->addField('date',date('d.m.Y',$r->result('datetime')));

                    $r_next = new Select($db,'select id from news  where  ntype=2 and sort  > '.$r->result('sort').' order by sort limit 1');
                    if ($r_next->next_row())
                        $main->addField('next',$r_next->result('id'));



                    $r_next->unset_();

                    $r_prev = new Select($db,'select id from news  where ntype=2 and  sort  < '.$r->result('sort').' order by sort limit 1');
                    if ($r_prev->next_row())
                           $main->addField('prev',$r_prev->result('id'));
                    $r_prev->unset_();

                     $r = new Select($db,'update '.$GLOBALS['table_name'].' set watch=watch+1 where id = '.$_GET['i']);

                    addLast($GLOBALS['site']->path,$main->name);
            }
            else
                        header('Location: /error404');
            $r->unset_();
    }

// ��� ������
    else {
   $main = new outTree($s_FILENAME);


           $ri = new Select($db,"select id as cnt from news where pabl=1 and ntype=2 ");
           if( $ri->num_rows() > $GLOBALS[$modulName.'_fcount'])  $main->addField('escho','');

           $sql['sql'].="select * from news where pabl=1 and ntype=2 order by datetime desc,ntype limit 0,".$GLOBALS[$modulName.'_fcount'];

          // echo $sql['sql'];
           $ri = new Select($db,$sql['sql']);

           $d = '';
           $d1=1;

            $month=array("������","�������","�����","������","���","����","����","�������","��������","�������","������","�������");

           if ($ri->next_row()) {
                          $sub = new outTree();

                          $d1 = date('d.m.Y', $ri->result('datetime'));
                          $d=$d1;
                          $sub1 = new outTree();
                          $ri->addFields($sub1,$ar=array('id','watch'));

                          if ($ri->result('ntype')==1 ) $sub1->addField('ntype','news');
                            else if ($ri->result('ntype')==2 ) $sub1->addField('ntype','law');

                          $ri->addFieldIMG($sub1,'image1');
                          $str=trim($ri->result('name'));
                          $str1=substr_replace($str, strtoupper($str[0]),0,1);
                          $sub1->addField('name',$str);
                           $ri->addFieldHTML($sub1,'preview');

                          $strdate = date("d",$ri->result('datetime'))." ".$month[((int) date("m",$ri->result('datetime'))-1)];
                          $strdate.=" ".date('Y',$ri->result('datetime'));
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

                          if ($ri->result('ntype')==1 ) $sub1->addField('ntype','news');
                            else if ($ri->result('ntype')==2 ) $sub1->addField('ntype','law');

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
          //echotree($main);

          if(isset($sub))  {
            $main->addField('sub',&$sub);
            unset($sub);
          }
          $ri->unset_();


          if (isset($main)) {
                  //  echoTree($main);
                    $site->addField($GLOBALS['currentSection'],&$main);
                    unset($main);
          }

    }

         unset($main);

 ?>

