<?php

 include('config.php');

 unset($main);
 $FILENAME = $front_html_path.'front.html';

 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];

 include($inc_path.'/classes/class.BF.php');
 include($inc_path.'/admin_functions.php');

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
          if ($r->next_row()) $id_company = $r->result('id_company');
     } else
              $id_company = $user_company;

     $main = &addInCurrentSection($FILENAME);
     unset($main->content);

     if ( $_SESSION ['user']>0) {

           //���� ���� - ������������ ����� ���
           if ($user_company == $id_company)
           {

                  //������ ������ �������
                  if ($_POST['rep_del']>0){

                        if ($_POST['id']>0) {
                           $back = new BF($db,$modulName,$modulCaption,$table_name,$arFiles);
                           $back->deleteRecord($_POST['id']);
                           $r1 = new Select($db,'delete from reports where id='.$_POST['id']);
                        };
                        $main->addField('mode','mode_edit');
                  }
                  //������ ������ ������������� ���� �����
                  else if ($_POST['rep_edit']>0){
                        if ($_POST['id']>0)
                            $main->addField('mode','mode_edit_one');
                  }
                  //������ ������ ������������� ���� �����
                  else if ($_POST['new_rep']>0){
                            $main->addField('mode','mode_new');
                  }
                  //������ ������ ���������
                  else if ($_POST['rep_save']>0){

                         $values['name'] = $_POST['name'];
                         $values['file'] = $_POST['file'];
                         $values['kn_file'] = $_POST['kn_file'];
                         $values['d_file'] = $_POST['d_file'];
                          var_dump($values);
                         $back = new BF($db,$modulName,$modulCaption,$table_name,$arFiles);
                         $back->saveRecord($values,$_POST['id']);
                         $main->addField('mode','mode_edit');
                  }
                  // ��������� ����� �����
                  else if ($_POST['new_rep_submit']>0){
                         $values['name'] = $_POST['name'];
                         $values['file'] = $_POST['file'];
                         $values['kn_file'] = $_POST['kn_file'];
                         $values['id_company'] = $id_company;

                         $back = new BF($db,$modulName,$modulCaption,$table_name,$arFiles);
                         $back->saveNewRecord($values);

                         $main->addField('mode','mode_edit');
                  }
                  //���� ������ ���
                  else if ($cookie>0) $main->addField('mode','mode_show');
                  //������ �� ��������
                  else  $main->addField('mode','mode_edit');
           } //���� - �� ������������ ����� ���
           else $main->addField('mode','mode_show');
     } else $main->addField('mode','mode_show');

     if (($_POST['id']>0)&&($main->mode=='mode_edit_one')) {
          $r1 = new Select($db,'select * from reports where id='.$_POST['id']);
          $main->addField('id_company',$id_company);
          while ($r1->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $r1->addFields($sub,$ar=array('id','name'));
                  $r1->addFieldsFILE($sub,$ar=array('file'));
                  $main->addField('sub',&$sub);
          };
     } else {
          $r1 = new Select($db,'select * from reports where id_company='.$id_company);
          $main->addField('id_company',$id_company);
          if (($main->mode=='mode_show') && ($r1->num_rows() == 0)) $main->addField('no_sub','');

          while ($r1->next_row()) {
                  unset($sub);
                  $sub = new outTree();
                  $r1->addFields($sub,$ar=array('id','name'));
                  $r1->addFieldsFILE($sub,$ar=array('file'));
                  $main->addField('sub',&$sub);
          };
     };
  }

  else header('Location: /');
// echotree($main);

 ?>

