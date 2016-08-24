<?php

 /**
  * @package ALL
  */

/**

 * ����� ��� ������ � ����������� ������� SELECT � ��
 *
 * @uses Db
 * @uses outTree
 * @author Milena Eremeeva
 * @version 2.07 - 27.08.2007 9:50
 *
 * .01 ��������� ������� isEOF <br>
 * .01 ��������� ����������� ���������� ������� ������� (�� ����� ������) <br>
 * .02 ��������� ������� fetch_assoc <br>
 * .03 �������� ������� addFieldIMG,addFieldsIMG - ��������� ��������� �� ������� �������� <br>
 * .04 ������� ���� clearstatcache � ������ addFieldFILE <br>
 * .05 �������� ��������� ����� Select_ - � ������������ Select. <br>
 * .06 ��������� ������� addField (�� �������-��!!!) <br>
 * .07 ��������� ������� addFieldDATE
 *
 */
 class Select
 {

  /**
   *  ������ ������ � �����
   *  @var Db
   */
  var $db;

  /**
   *  ������ �������
   *  @var string
   */
  var $query_string = '';

  /**
   *  ���������� �������
   *  @var string
   */
  var $query_id = 0;

  /**
   *  ���������� �����
   *  @var int
   */
  var $num_rows = 0;

  /**
   *  ������ �� ������� (��. ����� next_row)
   *  @var int
   */
  var $result_row = -1;

  /**
   *
   * @param Db $_db ������ ������ � �����
   * @param string $_query ������ SELECT
   * @param boolean $try � �������� true �� ������������� ������ ��� �������
   * @return Select
   */
  function Select( &$_db, $_query, $try = false )
   {
      $this->db = &$_db;
      $this->query_string = $_query;
      $this->query_id = $_db->query($_query, $try);
      $this->num_rows = $_db->num_rows($this->query_id);
      $this->result_row = -1;
   }

   /**
    * ���������, ��������� �������� �����
    * @return bool
    */
   function isEOF() {
                   return $this->result_row == $this->num_rows;
   }

  /**
   * ���������� ��������������� ������ ������� ������
   * @return array
   */
  function &fetch_assoc() {
      return $this->db->fetch_assoc($this->query_id,$this->result_row);
  }


  /**
   * ��������� ��������� � ����
   */
  function reSelect()
   {
      $this->query_id = $this->db->query($this->query_string);
      $this->num_rows = $this->db->num_rows($this->query_id);
      $this->result_row = -1;
   }


  /**
   * "�����������" ���������� �������
   */
  function unset_()
   {
      $this->db->free_result( $this->query_id );
   }

  /**
   * �������� ������ �� ��������� ������<br>
   * ���������� FALSE - ���� ����� �� ����� ������������ ��� ���������� ���������� � $this->end
   * @return bool
   */
  function next_row()
   {
     if ( ($this->result_row < $this->num_rows - 1) && (isset($this->end) ? $this->result_row < $this->end : true) )
      {
         $this->result_row++;
         return true;
      }
     else return false;
   }


  /**
   * ���������� �������� ���� �� ������ �������
   * @param string $field_name ��� ����
   * @param int $row ����� ������, �� ��������� ���, �� ������� ��������� ������ - �� ����������
   * @return mixed
   */
  function result($field_name, $row = null)
   {
     if ( $this->query_id )
           $r = $this->db->result( $field_name,
                                   ( isset($row) ? $row : $this->result_row ),
                                   $this->query_id
                                 );
     return stripslashes($r);
  }



  /**
   * ����������� ������ � ���������� ������
   * @return void
   */
  function free_result()
   {    $this->db->free_result( $this->query_id );
        $this->result_row = -1;
        $this->num_rows = 0;
        $this->query_id = null;
   }

  /**
   * ���������� ���������� ����� �������
   * @deprecated �� �����, ��� ��� ���������������� �������� $this->num_rows � ������������
   * @return int
   */
  function num_rows()
   {
        return $this->db->num_rows( $this->query_id );
   }

  /**
   * �������������� ��� ���� ����� Text �� ������� � $ot, ��������� ��������� ������� � ����
   * @param outTree $ot ������, � ������� ���������
   */
  function addAll(&$ot)
   {
      $numfields = $this->db->num_fields($this->query_id);
      for( $i =0 ; $i < $numfields; $i++ )  {
          $ot->addField( $field_name = $this->db->field_name($i,$this->query_id),
                         htmlspecialchars($this->result($field_name)));
      }
   }


  /**
   * �������������� ���� ���� Text � $ot, ��������� ��������� ������� � ����
   * @param outTree $ot ������, � ������� ���������
   * @param array $fields ����, ������� ���������
   */
  function addFields(&$ot,&$fields)
   {   $cnt = count($fields);
       for ( $i = 0; $i < $cnt; $i++ )
          $ot->addField( $fields[$i], htmlspecialchars($this->result($fields[$i])) );
   }


  /**
   * �������������� ���e ���� Text � $ot, ��������� ��������� ������� � ����
   * @param outTree $ot ������, � ������� ���������
   * @param string $fields ���t, ������� ���������
   */
  function addField(&$ot,$field) {
      $ot->addField( $field, htmlspecialchars($this->result($field)) );
  }

  /**
   * �������������� ���� ���� HTML � $ot
   * @param outTree $ot ������, � ������� ���������
   * @param string  $fieldHtml ���� �������
   */
  function addFieldHTML(&$ot, $fieldHtml)
   {   $ot->addField( $fieldHtml, $this->result($fieldHtml) );
   }

  /**
   * ������ addFieldHTML, ������ ���� ����� ��� ������� �����
   *
   * @param outTree $ot ������, � ������� ���������
   * @param string  $fields ������ �����
   */
  function addFieldsHTML(&$ot,&$fields)
   {   $cnt = count($fields);
       for ( $i = 0; $i < $cnt; $i++ )
          $this->addFieldHtml( $ot, $fields[$i] );
   }


  /**
   * �������������� ����� $fieldImg ���� IMG � $ot, ���� �������� ����������,
   * @deprecated  ����� 'not_'.$fieldImg - �������� ���� �� ���������� �����, ����������� � ������� ����� ��������� ���� [%!$fieldImg%]
   *
   * @param outTree $ot ������, � ������� ���������
   * @param string  $fieldImg ���� �������, ��� �������� ���� � ��������
   * @param string  $path - ���������� ���� � ��������
   */
  function addFieldIMG(&$ot, $fieldImg, $path = '') {
     global $document_root;
     $isize = @getImageSize($document_root.($file = $path.rawurldecode($this->result($fieldImg)))  );
     if ($isize) {
        $tmp = new outTree();
        $tmp->addField('w',$isize[0]);
        $tmp->addField('h',$isize[1]);
        $tmp->addField('src', $file );
        $ot->addField( $fieldImg, $tmp);
     }
     else
             $ot->addField('not_'.$fieldImg,'');
   }

  /**
   * ������ addFieldIMG, ������ ���� ����� ��� ������� �����
   *
   * @param outTree $ot ������, � ������� ���������
   * @param string  $fields ������ �����
   * @param string  $path ���������� ���� � ��������
   */
  function addFieldsIMG(&$ot,&$fields,$path = '') {
     $cnt = count($fields);
     for ( $i = 0; $i < $cnt; $i++ )
          $this->addFieldIMG( $ot, $fields[$i], $path );
  }

  /**
  * �������������� ����� fieldF ���� FILE � $ot, ���� ���� ����������
  * @param outTree $ot ������, � ������� ���������
  * @param string  $fieldF ���� �������, ��� �������� ���� � �����
  * @return void
  */
  function addFieldFILE(&$ot, $fieldF)
   { global $document_root;
     $file_name = $this->result($fieldF);
     if ($file_name && is_readable( $document_root.( $file=rawurldecode($file_name) ) ))
      { $tmp = new outTree();
        $tmp->addField('size',round(filesize($document_root.$file)/1024,2));
        $tmp->addField('type',strtolower(substr(strrchr($file,'.'),1)));
        $tmp->addField('href', $file );
        $ot->addField( $fieldF, $tmp);
      }
     clearstatcache();
   }

  /**
   * ������ addFieldFILE, ������ ���� ����� ��� ������� �����
   *
   * @param outTree $ot ������, � ������� ���������
   * @param string  $fields ������ �����
   */
  function addFieldsFILE(&$ot,&$fields)
   { $cnt = count($fields);
     for ( $i = 0; $i < $cnt; $i++ )
          $this->addFieldFILE( $ot, $fields[$i] );
   }

  /**
  * �������������� ����� fieldE ���� EMAILa � $ot
  * @param outTree $ot ������, � ������� ���������
  * @param string  $fieldE ���� �������
  * @return void
  */
  function addFieldEMAILa(&$ot, $fieldE)
   { global $document_root;
     if ($this->result($fieldE))
      { $tmp = new outTree();
        $tmp->addField('open',get_script_a_mail(textFormat($this->result($fieldE))));
        $tmp->addField('close','</a>');
        $ot->addField( $fieldE, $tmp);
      }
   }

  /**
   * ������ addFieldEMAILa, ������ ���� ����� ��� ������� �����
   *
   * @param outTree $ot ������, � ������� ���������
   * @param string  $fields ������ �����
   */
  function addFieldsEMAILa(&$ot,&$fields)
   { $cnt = count($fields);
     for ( $i = 0; $i < $cnt; $i++ )
          $this->addFieldEMAILa( $ot, $fields[$i] );
   }


  /**
  * �������������� ����� field � ������ $fieldName ���� DATE � $ot
  * @param outTree $ot ������, � ������� ���������
  * @param string  $field ���� �������
  * @param string  $fieldName ���� �������
  */
  function addFieldDATE(&$ot,$field,$fieldName = 'date') {
        $datetime = intval($this->result($field));
        $d =  new outTree();
        $d->addField('day',date('j',$datetime));
        $d->addField('year',date('Y',$datetime));
        $d->addField('month',date('n',$datetime));
        $ot->addField($fieldName,&$d);
  }

}

/**
 * @todo �� �����, ����� �������� ���� �����, ��������, ����������
 *
 */
class Select_ {

        /**
         * ���������� ������ Select
         *
         * @param Select $r
         */
        function unset_(&$r) {
                $r->unset_();
                unset($r);
        }
}


?>
