<?php

 //include ($inc_path.'/classes/class.B.php');
 //include ($inc_path.'/classes/class.BF_P.php');
 //require_once("/../../setup.php");
 //include_once($inc_path.'/db_conect.php');
 //include_once($inc_path.'/func.front.php');
 //echo $adminpass;

 function house_delete($id) {

     $modulName = 'opros';
     $modulCaption = '������';
     $table_name  = 'opros';

     $back = new B($GLOBALS['db'],$modulName,$modulCaption,$table_name);
     $r = new Select($back->db,'select * from '.$back->table.' where id_house='.$id);
     while ($r->next_row())
        $back->deleteRecord($r->result('id'));

     unset($back);


     $modulName = 'galery';
     $modulCaption = '����';
     $table_name  = 'galery';
     $arFiles = array(
                'image1' => array($extent,$files_path,'image'),
                'image2' => array($extent,$files_path,'image')
     );

     $back = new BF($GLOBALS['db'],$modulName,$modulCaption,$table_name,$arFiles);
     $r = new Select($back->db,'select * from '.$back->table.' where id_house='.$id);
     while ($r->next_row())
        $back->deleteRecord($r->result('id'));
          unset($back);

     $modulName = 'tsjnews';
     $modulCaption = '�������';
     $table_name  = 'tsjnews';
     $arFiles = array(
                'image1' => array($extent,$files_path,'image'),
                'image2' => array($extent,$files_path,'image')
     );

     $back = new BF($GLOBALS['db'],$modulName,$modulCaption,$table_name,$arFiles);
     $r = new Select($back->db,'select * from '.$back->table.' where id_house='.$id);
     while ($r->next_row())
        $back->deleteRecord($r->result('id'));
          unset($back);
 };

 //�������� ���������� �� id ���
 function report_delete($id) {

     $modulName = 'reports';
     $modulCaption = '�������';
     $table_name  = 'reports';
     $arFiles = array(
                 'file' => array($extent,$files_path,'file')
         );

     $back = new BF($GLOBALS['db'],$modulName,$modulCaption,$table_name,$arFiles);
     $r = new Select($back->db,'select * from '.$back->table.' where id_company='.$id);
     while ($r->next_row())
        $back->deleteRecord($r->result('id'));
     unset($back);
 };
?>