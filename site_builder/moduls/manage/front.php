<?php

 include('config.php');
 require_once($inc_path."/phpmailer/send.php");

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

                 $r1 = new Select($db,'select * from users where id_company="'.$id_company.'"');
                 if ($r1->next_row())
                    $user_mail = $r1->result('email');

            } else
              $id_company = $user_company;

          //    echo $id_company;
            $main = &addInCurrentSection($FILENAME);
            unset($main->content);
            $r->addFields($site,$ar=array('title','description','keywords'));
            $r->addFields($main,$ar=array('id','name','alt3'));


            foreach ( $_POST as $key => $value)
                    $$key=$value;
                    //var_dump($_POST);

            // ������ ��� JavaScript � �������� ������� ������������ �����
            $str_fieldsWF = '';
            foreach ( $fieldsWithoutFail as $value) {
                    $str_fieldsWF.= ('\''.$value.'\',');
            }

            $main->addField('fieldsWithoutFail',substr($str_fieldsWF,0,-1));
            if ($id_company >0) {
                  //���� ����������� � ������������ ����� ���
                  if ( $_SESSION ['user']>0) {
                      //���� ������ ���
                      if ($cookie>0) $main->addField('mode','mode_show');
                      //���� ���� - ������������ ����� ���
                      else if ($user_company == $id_company)
                      {
                            //������ ������ �������������
                            if ($_POST['edit_submit']>0){
                                  $main->addField('mode','mode_redact');
                            }
                            //������ ������ ���������
                            else if ($_POST['save_submit']>0){
                                 // echo 'update company set pre_manage = "'.$_POST['pre_manage'].'",pabl=0 where id='.$id_company;
                                  $r1 = new Select($db,'update company set pre_manage = "'.$_POST['pre_manage'].'",pabl=0 where id='.$id_company);
                                  $main->addField('mode','mode_redact');
                            }
                            //������ �� ��������
                            else $main->addField('mode','mode_redact');
                      }
                      //���� - �� ������������ ����� ���
                      else  $main->addField('mode','mode_show');

                  } else  $main->addField('mode','mode_show');

                  $r1 = new Select($db,'select * from company where id="'.$id_company.'"');
                  if ($r1->next_row()) {
                       $r1->addFieldHTML($main,'pre_manage');
                       $r1->addFieldHTML($main,'manage');
                  };

                  if ($_POST['send']>0){
                          // ������ ��� JavaScript � �������� ������� ������������ �����
                        $flag = true;
                        foreach ( $fieldsWithoutFail as $value) {
                                $flag = $flag && !empty($$value);
                        }
                        /*switch ($type) {
                          case 'jaloba': $type1 = '������';
                               }
                         */
                        if ( $flag)  {

                          $text = date('d.m.Y').' � '.date('H:i:s').' �� ����� '.$_SERVER['SERVER_NAME'].' ���� ���������� ���������.
 ���� ���������: '.$type.'
 ��: '.stripslashes($fio).'
 Email: '.stripslashes($email).'
 ���.: '.stripslashes($tel).'
 � ��.: '.stripslashes($flat).'
 �����: '.stripslashes($text);

                        $subm=mailViaSMTP($text,'mail_manage',$user_mail,'��������� � ���������',false,$email);
                        //reload('', $f = array('subm' => intval($subm)) );

                        }

                        if (isset($subm)){
                             unset($main->mode);
                             $main->addField('message',$subm ? 'ok' : 'error');
                        };
                   };
            };
   }  else  header('Location: /error404');


// echotree($main);

 ?>

