<?php

 include('config.php');

 unset($main);
 $FILENAME = $front_html_path.'front.html';

 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];

 //���� ����������� � ������������ ����� ���
 if ( $_SESSION ['user']>0) {
    $r1 = new Select($db,'select * from users where id="'.$user.'"');
    if ($r1->next_row())
    $user_company = $r1->result('id_company');
 };

// var_dump($_POST);

// ������ ���
 if ($cookie>0 || $user_company>0) {

            if ($cookie>0) {
              $r = new Select($db,'select * from house where id="'.$cookie.'"');
              if ($r->next_row())
                 $id_company = $r->result('id_company');
              //echo $id_company;
            } else
              $id_company = $user_company;

           // echo $id_company;
            $main = &addInCurrentSection($FILENAME);
            unset($main->content);
            $r->addFields($site,$ar=array('title','description','keywords'));
            $r->addFields($main,$ar=array('id','name','alt3'));

            if ($id_company >0) {
                        if ( $_SESSION ['user']>0) {
                                    //���� ������ ���
                                    if ($cookie>0) $main->addField('mode','mode_show');
                                   //���� ���� - ������������ ����� ���
                                    else if ($user_company == $id_company)
                                    {
                                          //������ ������ �������������
                                          if ($_POST['edit_submit']>0)
                                                $main->addField('mode','mode_redact');
                                          //������ ������ ���������
                                          else if ($_POST['save_submit']>0){
                                                //echo 'update company set about = "'.$_POST['about'].'" where id='.$id_company;
                                                $r1 = new Select($db,'update company set pre_about = "'.$_POST['pre_about'].'",pabl=0 where id='.$id_company);
                                                $main->addField('mode','mode_edit');
                                          }
                                          //������ �� ��������
                                          else $main->addField('mode','mode_redact');
                                    }

                                    //���� - �� ������������ ����� ���
                                    else  $main->addField('mode','mode_show');

                        }
                           else $main->addField('mode','mode_show');
                        $r1 = new Select($db,'select * from company where id="'.$id_company.'"');
                        if ($r1->next_row()) {
                                     $r1->addFieldHTML($main,'pre_about');
                                     $r1->addFieldHTML($main,'about');
                        };
            }
            else
                        header('Location: /error404');
            $r->unset_();
    }
  else header('Location: /');
// echotree($main);

 ?>

