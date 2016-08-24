<?php

/**
 * ������ c ������������ <br>
 *  F - ������� �������������� ����� <br>
 * <br>
 * class BF extends B <br>
 * �������� ������ BF,B
 *
 * @package BACK

 * @version 1.02 - 5.04.2008 15:00
 *
 * .02 - ����� deleteRecord ���������� ������ <br>
 *       � ���������� ���������� � �� ����������� ���
 *
 */

include_once('class.B.php');

/**
 * @uses Select
 * @uses outTree
 */
class BF_ {


 /**
  * ��������: ������� ������
  * @param BF $_this
  * @param int $id id ��������� ������
  * @param Select $r ������ � ��������� �������
  * @param bool $qd ������� ���� ������ ��� ������������ "����������������� ����������"
  * @return int ���������� ��������� �������
  */
 function deleteRecord(&$_this,$id,$r = null,$qd = true) {

        if (!isset($r)) {
                $r = new Select($_this->db,'select * from '.$_this->table.' where id="'.$id.'"');
                $r->next_row();
                $flag = true;
                $GLOBALS['r'] = &$r;
        }

        if ($r->num_rows)        {
                // �������� ������
                 foreach ($_this->arFiles as $file => $param) {
                         @unlink($GLOBALS['document_root'].rawurldecode($r->result($file)));
                 }
        }

        if ($qd) {
                $_this->db->query('delete from '.$_this->table.' where id="'.$id.'"');
                 return $_this->db->affected_rows();
        }
 }


 /**
  * ��������� ����
  * @param BF $_this
  * @param string $field ��� ���� �������, ���� ������������ ���� � �����
  * @param int $id id ���������� ������
  * @param mixed $keep_name ��������� ������������ ��� ����� ��� ���.
  * @param string $extent ������, ��� �������� ����������� ���������� ��� �����
  * @param string $files_path ����, ��� ������ ��������� ����
  * @return string ���� � ������� ������������ ����� ��� ������.
  */
 function uploadFile(&$_this,$field,$id,$keep_name,$extent,$files_path) {

   $fileName = '';
   $file = $_FILES[$field];
    
   if (isset($_FILES[$field])) {

            $file = &$_FILES[$field];

            $ext=strtolower(substr($file['name'],strrpos($file['name'],'.')+1));
            if (in_array($ext,$extent))
                          $fileName=prepare_file( $_this->table,
                                                  $id,
                                                  $field,
                                                  $file,
                                                  $files_path,
                                                  'T_'.$_this->table.'_F_'.$field.'_I_'.$id.'_v%ver%.'.$ext,
                                                  intval(isset($keep_name)));
   }

   if ( '' < $fileName )
        $_this->db->query('update '.$_this->table.' set '.$field.'="'.addslashes($fileName).'" where id="'.$id.'"');

   return $fileName;

 }

 /**
  * ��������� ���� � �������<br>
  * ��� � ����������� �� ����� delete - ������� �������� ����, ���� �� ���
  * @param BF $_this
  * @param string $field ��� ���� �������, ���� ������������ ���� � �����
  * @param int $id id ���������� ������
  * @param mixed  $keep_name ��������� ������������ ��� ����� ��� ���.
  * @param mixed  $delete ������� ������� ���� - ��� ��� - � ���� ������ �������� ����� �� ����������!
  * @param string $extent ������, ��� �������� ����������� ���������� ��� �����
  * @param string $files_path ����, ��� ������ ��������� ����
  * @return string ���� � ������� ������������ ����� ��� ������.
  */
 function uploadAndReplaceFile(&$_this,$field,$id,$keep_name,$delete,$extent,$files_path) {

   $fileName = '';
  // var_dump($_FILES);
   $file = $_FILES[$field];
   if (!isset($delete)) {
           if (isset($_FILES[$field])) {
                        $file = &$_FILES[$field];
                    $ext=strtolower(substr($file['name'],strrpos($file['name'],'.')+1));
                    if (in_array($ext,$extent)) {
                                  $fileName=prepare_file( $_this->table,
                                                          $id,
                                                          $field,
                                                          $file,
                                                          $files_path,
                                                          'T_'.$_this->table.'_F_'.$field.'_I_'.$id.'_v%ver%.'.$ext,
                                                          intval(isset($keep_name)));
                    }
           }
   }
   else {
       $_this->db->query('select '.$field.' from '.$_this->table.' where id="'.$id.'"');
       @unlink($GLOBALS['document_root'].rawurldecode($_this->db->result(0,0)));
   }
   return $fileName;
 }

 /**
  * ��������������� ��������� ����� ��� ���������� ����� ������
  * @param BF $_this
  * @param array $values �����.������ �������� �����
  * @param int $id id ����������� ������
  */
 function uploadFiles($_this,&$values,$id) {
            foreach ($_this->arFiles as $file => $param) {

                    $_this->uploadFile($file,$id,$values['kn_'.$file],$param[0],$param[1]);
            }
 }

 /**
  * ��������������� ��������� ����� ��� ���������� ������������ ������
  * @param BF $_this
  * @param array $values �����.������ �������� �����
  * @param int $id id ���������� ������
  */
 function uploadAndReplaceFiles($_this,&$values,$id) {
            foreach ($_this->arFiles as $file => $param) {
              if ( ($tmp = $_this->uploadAndReplaceFile($file,$id,$values['kn_'.$file],$values['d_'.$file],$param[0],$param[1]))
                || isset($values['d_'.$file]) )
                      $values[$file] = $tmp;
            }
 }

 /**
  * ��������: ��������� ����� ������
  * @param BF $_this
  * @param array $values �����.������ �������� �����
  */
 function saveNewRecord(&$_this,&$values) {
     $id = B_::saveNewRecord(&$_this,&$values);
     $_this->uploadFiles($values,$id);
     return $id;
 }

 /**
  * ��������: ��������� ������������ ������
  * @param BF $_this
  * @param array $values �����.������ �������� ���������� �����
  * @param int $id id ���������� ������
  */
 function saveRecord(&$_this,&$values,$id) {
          $_this->uploadAndReplaceFiles($values,$id);
          //var_dump($values);
     B_::saveRecord(&$_this,&$values,$id);
 }

 /**
  * ��������� �������� ������ � ������ ��������������.��������� ���������
  * @param BF $_this
  * @param outTree $main ������ ��������������
  */
 function addFiles(&$_this,&$main) {
         $r =  &$GLOBALS['r'];
         foreach ($_this->arFiles as $file => $param) {
                 unset ($main->$file);
                 if     ('image' == $param[2])
                         $r->addFieldIMG($main,$file);
                 elseif ('file' == $param[2])
                         $r->addFieldFILE($main,$file);
           }
 }

 /**
  * ��������� ������ �������������� ������<br>
  * ��������� ���������
  * @param BF $_this
  * @param outTree $main ������ ��������������
  * @param int $id id ���������� ������
  * @return string ���� ������� ���������
  */
 function addIfcEditRecord(&$_this,&$main,$id) {
     if ($_FILENAME = B_::addIfcEditRecord($_this,$main,$id)) {
                  $_this->addFiles($main);
          }
     return $_FILENAME;
 }

}


/**
 * ������ �� ����������� ������� c ������������ <br>
 *  F - ������� �������������� ����� <br>
 */
class BF extends B {
 var $arFiles;

 function BF(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
        $this->initBF(&$db,$_name,$_caption,$_table,&$arFiles);
 }

 function initBF(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
        $this->arFiles = &$arFiles;
        $this->initB($db,$_name,$_caption,$_table);
 }


 function deleteRecord($id,$r = null,$qd = true) {
         BF_::deleteRecord($this,$id,$r,$qd);
 }

 function uploadFile($field,$id,$keep_name,$extent,$files_path) {
         BF_::uploadFile($this,$field,$id,$keep_name,$extent,$files_path);
 }

 function uploadFiles(&$values,$id) {
         BF_::uploadFiles($this,$values,$id);
 }

 function uploadAndReplaceFile($field,$id,$keep_name,$delete,$extent,$files_path) {
         return BF_::uploadAndReplaceFile($this,$field,$id,$keep_name,$delete,$extent,$files_path);
 }

 function uploadAndReplaceFiles(&$values,$id) {
         BF_::uploadAndReplaceFiles($this,$values,$id);
 }

 function saveNewRecord(&$values) {
         return BF_::saveNewRecord($this,$values);
 }

 function saveRecord(&$values,$id) {
         BF_::saveRecord($this,$values,$id);
 }

 function addFiles(&$main) {
         BF_::addFiles($this,$main);
 }

 function addIfcEditRecord(&$main,$id) {
         return BF_::addIfcEditRecord($this,$main,$id);
 }

}


?>
