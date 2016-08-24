<?php

/**
 * ��������� ���� ������� <br>
 *  <br>
 * class BCt extends BTr <br>
 *  <br>
 * class BSe extends BSc <br>
 * class BIt extends BFTTO_FTTSFS_TOTS
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.03 - 13.04.2007 9:40
 *
 * .03 ��������� ��� � ������� ������� ������
 *
 */

include_once('class.BTr.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BSe_ {


 /**
  * ��������� ������ �������������� ������<br>
  * ��������� ����������
  * @param BSe $_this
  * @param outTree $main ������ ��������������
  * @param int $parent id ������������ ������
  * @return string ���� ������� ����������
  */
 function addIfcAddRecord(&$_this,&$main,$parent) {
          if ($_FILENAME = BT_::addIfcAddRecord($_this,$main,$parent)) {
                  $main->path->last->name = '���������� �������';
             return 'redact_s_'.($GLOBALS['br']->level+1).'.html';
          }
     return $_FILENAME;
 }

 /**
  * ��������� ������ �������������� ������<br>
  * ��������� ���������
  * @param BSe $_this
  * @param outTree $main ������ ��������������
  * @param int $id id ���������� ������
  * @return string ���� ������� ���������
  */
 function addIfcEditRecord(&$_this,&$main,$id) {
          if ($_FILENAME = BF_T_::addIfcEditRecord($_this,$main,$id)) {
             return 'redact_s_'.$GLOBALS['br']->level.'.html';
          }
     return $_FILENAME;
 }

}

/**
 * ������ �� ����������� ������� "������" c ������������ <br>
 *  F - ������� �������������� ����� <br>
 *  S - ��������� id � ������ <br>
 *  O - ����������� �� ���� sort <br>
 *  T - ����������� ������ �� ��������
 */
class BSe extends BSc {

  function addIfcEditRecord(&$main,$id) {
         return BSe_::addIfcEditRecord($this,$main,$id);
  }

  function addIfcAddRecord(&$main,$parent) {
         return BSe_::addIfcAddRecord($this,$main,$parent);
  }

}

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BIt_ {



 /**
  * ��������� ������ �������������� ������<br>
  * ��������� ����������
  * @param BIt $_this
  * @param outTree $main ������ ��������������
  * @param int $parent id ������������ ������
  * @param string $table ��� �������, ��� �������� ������������ ������
  * @return string ���� ������� ����������
  */
 function addIfcAddRecord(&$_this,&$main,$parent,$table) {
          if ($_FILENAME = BT_::addIfcAddRecord($_this,$main,$parent,$table)) {
                  $main->path->last->name = '���������� ������';
             return 'redact_i_'.$GLOBALS['br']->level.'.html';
          }
     return $_FILENAME;
 }


 /**
  * ��������� ������ �������������� ������<br>
  * ��������� ���������
  * @param BIt $_this
  * @param outTree $main ������ ��������������
  * @param int $id id ���������� ������
  * @param string $table ��� �������, ��� �������� ������������ ������
  * @return string ���� ������� ����������
  */
 function addIfcEditRecord(&$_this,&$main,$id,$table) {
          if ($_FILENAME = BF_T_::addIfcEditRecord($_this,$main,$id,$table)) {
                  $main->path->last->name = '�������������� ������';
             return 'redact_i_'.$GLOBALS['br']->level.'.html';
          }
     return $_FILENAME;
 }

 /**
  * ��������� ������ ��������� �������
  * @param BIt $_this
  * @param outTree $main ������ ���������
  * @return string ���� ������� ���������
  */
 function addManager(&$_this,&$main) {
         $param = &$_this->getParamMngr();
         $_this->addRecords($main,$param);
        return 'manager.html';
 }

}

/**
 * ������ �� ����������� ������� "�����" c ������������ <br>
 *  F - ������� �������������� ����� <br>
 *  S - ��������� id � ������ <br>
 *  O - ����������� �� ���� sort <br>
 *  T - ����������� ������ �� ��������
 */
class BIt extends BFTTO_FTTSFS_TOTS {
  function addIfcEditRecord(&$main,$id,$table) {
         return BIt_::addIfcEditRecord($this,$main,$id,$table);
  }

  function addIfcAddRecord(&$main,$parent,$table) {
         return BIt_::addIfcAddRecord($this,$main,$parent,$table);
  }

  function addManager(&$main) {
         return BIt_::addManager($this,$main);
  }

}

/**
 * @uses Select
 * @uses outTree
 */
class BCt_ {


 /**
  * ��������� ������ �������������� ������ "������"<br>
  * ��������� ���������
  * @param BCt $_this
  * @param outTree $main ������ ��������������
  * @param int $id id ���������� ������ "������"
  * @return string ���� ������� ����������
  */
 function addIfcEditItem(&$_this,&$main,$id) {
         return $_this->Item->addIfcEditRecord($main,$id,$_this->Section->table);
 }

 /**
  * ��������� ������ �������������� ������ "������"<br>
  * ��������� ����������
  * @param BCt $_this
  * @param outTree $main ������ ��������������
  * @param int $parent id ������������ ������
  * @return string ���� ������� ����������
  */
 function addIfcAddItem(&$_this,&$main,$parent) {
         return $_this->Item->addIfcAddRecord($main,$parent,$_this->Section->table);
 }

 /**
  * ��������: ������� "������"
  * @param BCt $_this
  * @param int $id id ���������� "�������"
  * @param int   $type   ��� �������:<br>
  *                      0 - ��;<br>
  *                      3 - ������ ��������������� ��������� "������";<br>
  *                      2 - ������ "����������"<br>
  *                      ����� ���������� ����� �������, ���������� �� ������������:<br>
  *                      �������� ������� � ������ = 3*2 = 6
  */
 function clearSection(&$_this,$id,$type = 0) {
    // �������� �����������
    if (0 == ($type%2))
            $_this->deleteSections($id);

    // �������� �������
    if (0 == ($type%3))
            $_this->deleteItems($id);
 }

 /**
  * ������� ��� "������" � "�������"
  * @param BCt $_this
  * @param int $id id ���������� "�������"
  */
 function deleteItems(&$_this,$id) {
    $r = new Select($_this->db,'select * from '.$_this->Item->table.' where parent="'.$id.'"');
    while ($r->next_row())
       $_this->Item->deleteRecord($r->result('id'),$r,false);
    $r->unset_();
        $_this->db->query('delete from '.$_this->Item->table.' where parent="'.$id.'"');
 }

 /**
  * ��������: ��������� ���������� ������<br>
  * ���������� ��������� ��������, ����� ������� ������
  * @param BCt $_this
  * @param array $param ��������� ���������
  */
 function pastRecords(&$_this,&$param) {
        BTr_::pastRecords($_this,$param);
         if (!isset($GLOBALS['br'])) {
                 $br = new Brunch($param['parent'],$_this->Section->table,'',$_this->db);
                $GLOBALS['br'] = &$br;
         }
         $param['nottree'] = true;
         $_this->Item->pastRecords($param);
 }


 /**
  * ��������� ������ �������� � ������ ���������
  * @param BCt $_this
  * @param outTree $main ������ ���������
  * @param array $param ��������� ���������
  */
 function addActions(&$_this,&$main,&$param) {
  //���� ������ ���� � ����� - ������� �� �������� �� ���������.
        if ( 0 > $GLOBALS['br']->level)
       header('Location: ?sct=1');

        $main->addField('actAddSection','');
        $main->addField('actAddItem','');

        if (   isset($_SESSION['idCuts']) &&
              ( $co = count($_SESSION['idCuts'][$_this->Item->table])+count($_SESSION['idCuts'][$_this->Section->table]))
             )
                 $main->addField('actPast',$co);

  // ���� ������������������� ���� �� ���� �� ��������
        if (     isset($main->actAddSection)
              ||  isset($main->actAddItem)
              ||  isset($main->actClear2)
              ||  isset($main->actClear3)
              ||  isset($main->actPast)
            )
                 $main->addField('actions','');
 }

 /**
  * ��������� ������ ��������� �������
  * @param BCt $_this
  * @param outTree $main ������ ���������
  * @return string ���� ������� ���������
  */
 function addManager(&$_this,&$main) {
         $_GET['sct'] =  ( !empty($_GET['sct']) ? $_GET['sct'] : 1 );

         $r = new Select($_this->db,'select * from '.$_this->Section->table.' where id="'.$_GET['sct'].'"');
    $GLOBALS['r']  = &$r;
    if ($r->next_row()) {
            // ��������� �������� �������
               $r->addFields($main,$ar = array('name','id'));

                $br = new Brunch($_GET['sct'], $_this->Section->table, '', $_this->db);
            $GLOBALS['br']  = &$br;

                $_this->Section->initPath($br,$main,$r);
                 $_this->Section->addButtons($main,$ar = array('id'=>$_GET['sct'],'root'=>1));

                $_this->Section->addManager($main);
                 if (isset($main->actClear)) {
                         unset($main->actClear);
                    $main->addField('actClear2','');
                 }
                 if (isset($main->records)) {
                    $main->addField('sections',&$main->records);
                         unset($main->records);
                 }

                $_this->Item->addManager($main);
                 if (isset($main->actClear)) {
                         unset($main->actClear);
                    $main->addField('actClear3','');
                 }
                 if (isset($main->records)) {
                    $main->addField('items',&$main->records);
                         unset($main->records);
                 }

                 $_this->addActions($main,$param);

                return 'manager.html';
    }
 }

 /**
  * ���������� "�������" �� ��������� ���������� $_GET
  * @param BCt $_this
  * @param string $location ��� ������������� ��������� �������� �� ?event=1&'.$location
  * @return bool ��������� ������� ��� ���
  */
 function createEvent(&$_this) {

          $location = '&sct='.$_GET['sct_back'];

         // �������� �������
     if ( isset($_GET['undoPast']) ) {
                unset(
                 $_SESSION['idCuts'][$_this->Section->table],
                 $_SESSION['idCuts'][$_this->Item->table]
                );
        header('Location: ?event=1'.$location);
     }
          elseif (BTr_::createEvent($_this));
          else {



                  if ('i' == $_GET['type'])
                     $GLOBALS['b'] = &$_this->Item;



        // ��������� ���������� ������
                 if (isset($_GET['add'])&& ('i' == $_GET['type'])) {
                         $GLOBALS['main'] = new outTree();
                        $GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addIfcAddItem($GLOBALS['main'],$_GET['add']);
                 }

        // ��������� ��������� ������
                 elseif (isset($_GET['edit'])&&('i' == $_GET['type'])) {
                         $GLOBALS['main'] = new outTree();
                        $GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addIfcEditItem($GLOBALS['main'],$_GET['edit']);
                 }

         // ����������� �������� ��� ������
             elseif ( isset($GLOBALS['b']) ) {
                     return $GLOBALS['b']->createEvent($location);
             }
             else return false;
          }
          return true;
 }

}


/**
 * ������� �� ������ ���� ������
 */
class BCt extends BTr {

 /**
  * @var BSe $Section ������-"������" ��� ������ � "�������" ����������
  */
 var $Section;

 /**
  * @var BIt $Item ������-"�����" ��� ������ � "�������" ����������
  */
 var $Item;

 function BCt(&$_db,$_name = null,$_caption = null,$table_sections,$table_items,&$arFilesS,&$arFilesI) {
         $this->initBCt(&$_db,$_name,$_caption,$table_sections,$table_items,&$arFilesS,$arFilesI);
 }

 function initBCt(&$_db,$_name,$_caption,$table_sections,$table_items,&$arFilesS,&$arFilesI) {
        $this->Item = new BIt(&$_db,$_name,$_caption,$table_items,$arFilesI);
        $this->Section = new BSe(&$_db,$_name,$_caption,$table_sections,$arFilesS);
        $this->initModule(&$_db,$_name,$_caption);
 }

 function clearSection($id,$type = 0) {
         BCt_::clearSection($this,$id,$type);
 }

 function deleteItems($id) {
         BCt_::deleteItems($this,$id);
 }

 function pastRecords(&$param) {
         BCt_::pastRecords($this,$param);
 }

 function addIfcEditItem(&$main,$id) {
         return BCt_::addIfcEditItem($this,$main,$id);
 }

 function addIfcAddItem(&$main,$parent) {
         return BCt_::addIfcAddItem($this,$main,$parent);
 }

 function addManager(&$main) {
         return BCt_::addManager($this,$main);
 }

 function addActions(&$main,&$param) {
         BCt_::addActions($this,$main,$param);
 }

 function createEvent() {
         return BCt_::createEvent($this);
 }


}


?>
