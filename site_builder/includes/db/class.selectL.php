<?php

 /**
  * @package ALL
  */

 include_once('class.select.php');


/** 
 * ����� ��� ������ � ����������� ������� SELECT � �� <br>
 * �������� ����� ��������� ��� �������� ����� � ���������� EN
 * 
 * @uses Db
 * @uses outTree
 * @author Milena Eremeeva
 * @version 2.07 - 27.08.2007 9:50
 */ 
 class SelectL extends Select {
 	
  /**
   * �������������� ��� ���� ��� ��������� EN �� ������� ���� Text � $ot 
   * @param outTree $ot ������, � ������� ���������
   */
  function addAllLangD(&$ot)
   {  global $language,$languages;
        
      $numfields = $this->db->num_fields($this->query_id);
      for( $i =0 ; $i < $numfields; $i++ )  {
      	  $field_name = $this->db->field_name($i,$this->query_id);
      	  $flag = true;
      	  foreach ($languages as $value) {
      	  	 $flag = $flag && ( substr($field_name,-2) == $value );
      	  }
      	  if ($flag)
          	$ot->addField( $field_name, htmlspecialchars($this->result($field_name))); 
      }
   }

  /**
   * �������������� ��� ���� c ���������� EN �� ������� ���� Text � $ot 
   * @param outTree $ot ������, � ������� ���������
   */
  function addAllLang(&$ot,$LANG)
   {  $numfields = $this->db->num_fields($this->query_id);
      for( $i =0 ; $i < $numfields; $i++ )  {
      	  $field_name = $this->db->field_name($i,$this->query_id);
      	  if ($LANG == substr($field_name,-2))
          	$ot->addField( $field_name, htmlspecialchars($this->result($field_name))); 
      }
   } 
  
 }


?>