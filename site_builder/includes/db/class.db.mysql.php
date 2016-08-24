<?php

 /**
  * @package ALL
  */

 include_once('class.select.php');

 /**
 * ����� ��� ������ � ����� ������ MYSQL. <br>
 * ����� ������������� ���������, ����� ���� ��������� ��� ����� ���� <br>
 *
 * @author Milena Eremeeva
 * @version 2.03 - 24.01.2007 14:00
 *
 * .01 ��������� ������� affected_rows <br>
 * .02 �������� ������� fetch_assoc <br>
 * .03 �������������� � "�����������" ������� insert � update, ��������� ������� getFieldsNames,getInsertQuery,getUpdateQuery
 */
class Db {

  /**
   *  ���� ����
   *  @var string
   */
  var $host = '';

  /**
   *  ����
   *  @var int
   */
  var $port = 0;

  /**
   *  ����� ������� � ����� ����
   *  @var string
   */
  var $user = '';

  /**
   *  ������ ������� � ����� ����
   *  @var string
   */
  var $password = '';

  /**
   *  ��� ����
   *  @var string
   */
  var $database = '';

  /**
   *  ���������� �������� � ����
   *  @var string
   */
  var $link_id = 0;

  /**
   *  ���������� ���������� �������
   *  @var string
   */
  var $query_id = 0;

  /**
   *  ������ �� ������� (��. ����� next_row)
   *  @var int
   */
  var $result_row = -1;

  function Db(
           $_host,
           $_user,
           $_password,
           $_database,
           $_port = null
  ) {

    $this->host = $_host;
    if (isset($_port))
        $this->port = $_port;
    $this->user = $_user;
    $this->password = $_password;
    $this->database = $_database;
  }

  /**
   * ������������� ���������� � ����� (������ �� ���, ���� ����������� ���������� ��� ���)
   * ������������� ������ ������� ��� �������<br>
   */
  function connect() {

    if ( $this->link_id == 0 ) {
      $this->link_id = @mysql_connect($this->host.($this->port ? ':'.$this->port : ''),
                                      $this->user,
                                      $this->password
      );
      ///****/echo("<br>������� connect: (".$this->link_id.")");
      if ( !$this->link_id ) {
        $this->halt("����������� �� �����������...");
      };
      if ( !@mysql_select_db($this->database) ) {
        $this->halt("����������� � ����� �� �����������...");
      };
      @mysql_query('set names cp1251',$this->link_id);
    };
  }

  /**
   * ��������� ������ � ����, ���������� ��� ����������<br>
   * ������������� ������ ������� ��� �������<br>
   * ���������� ������<br>
   * @param string $query_string ������ �������
   * @param bool $try � �������� true �� ������������� ������ ��� �������
   * @return int ���������� �������
   */
  function query($query_string, $try = false) {
    $this->connect();
    //echo $query_string.'<br />';
    //exit;
    $this->query_id = @mysql_query($query_string, $this->link_id);
    if ( !$this->query_id && !$try)
          $this->halt("��������� SQL ������: ".$query_string);
    $this->result_num_rows = $this->num_rows();
    $this->result_row = -1;
    return $this->query_id;
  }



  /**
   * ��������� ������ � ����
   * @param string $query_string ������ �������
   * @return string ����� � �������� ������ � ������ ������������
   */
  function queryCron($query_string) {
    $this->connect();
    $this->query_id = @mysql_query($query_string, $this->link_id);
    $query_error = '';
    if ($errno = mysql_errno())
            $query_error = $errno.': '.mysql_error();
    return $query_error;
  }

  /**
   * �������� ������ �� ��������� ������, ���������� FALSE - ���� ����� �� �����
   * @param string $_query_id ���������� �������, �� ��������� ���������� - �� ����������
   * @return bool
   */
  function next_row($_query_id = null)
   {
     if ( $this->result_row < $this->num_rows( ( $_query_id ? $_query_id : $this->query_id ) )-1 )
      {
               $this->result_row++;
         return true;
      }
     else return false;
   }

  /**
   * ���������� �������� ���� �� ������ �������
   * @param string $field ��� ����
   * @param int $row ����� ������, �� ��������� ���, �� ������� ��������� ������ - �� ����������
   * @param string $_query_id ���������� �������, �� ��������� ���������� - �� ����������
   * @return mixed
   */
  function result($field_name, $row = null, $_query_id = null)
   { if ( !isset($_query_id) ) $_query_id = $this->query_id;
     if ( $_query_id )
           $r = @mysql_result(
                   $_query_id,
                   ( isset($row) ? $row : $this->result_row ),
                   $field_name );
     return $r;
  }

  /**
   * ����������� ������ � ���������� ������
   * @param string $_query_id ���������� �������, �� ��������� ���������� - �� ����������
   */
  function free_result($_query_id = null)
   {
             @mysql_free_result( ($_query_id ? $_query_id : $this->query_id) );
        if (!$_query_id)
         {      $this->result_row = -1;
                $this->query_id = null;
         }
   }

  /**
   * ������ ������� ������, ���������� ������
   * @param string $_query_id
   */
  function set_current_result($_query_id)
   {    $this->query_id = $_query_id;
        $this->result_row = -1;
   }


  /**
   * ���������� ���������� ����� �������
   * @param string $_query_id ���������� �������, �� ��������� ���������� - �� ����������
   * @return int
   */
  function num_rows( $_query_id = null )
   {
            return @mysql_num_rows( ($_query_id ? $_query_id : $this->query_id) );
   }

  /**
   * ���������� ������ ����� � ������� ������<br>
   * ���������� ������
   * @return array
   */
  function tables_names()
   {    $_tables_names = array();
        $this->query('SHOW tables');
        while ($this->next_row())
               array_push( $_tables_names, $this->result(0));
        return $_tables_names;
   }

  /**
   * ���������� ���� ����� ���� � �������
   * @param string $_table_name ��� �������
   * @param string $_field_name ��� ����
   * @param string $_field_name_after ����� ������ ���� ���������
   * @param string $_field_type ��� ������������ ����
   */
  function add_field_after($_table_name, $_field_name, $_field_name_after = null, $_field_type)
   {    $this->query('ALTER TABLE '.$_table_name.'
                      ADD '.$_field_name.' '.$_field_type.
                      ($_field_name_after ? ' AFTER '.$_field_name_after  : '') );
   }

  /**
   * �������� ���� �� �������
   * @param string $_table_name ��� �������
   * @param string $_field_name ��� ����
   */
  function drop_field($_table_name, $_field_name)
   {    $this->query('ALTER TABLE '.$_table_name.' DROP '.$_field_name );
   }

  /**
   * ���������� ��� ���� �� ������
   * @param int $col ����� ����
   * @param string $_query_id ���������� �������, �� ��������� ���������� - �� ����������
   * @return string
   */
  function field_name( $col, $_query_id = null )
   {
            return @mysql_field_name(($_query_id ? $_query_id : $this->query_id), $col);
   }


  /**
   * ���������� ��� ���� �� ������
   * @param int $col ����� ���� �������
   * @param string $_query_id ���������� �������, �� ��������� ���������� - �� ����������
   * @return string
   */
  function field_type( $col, $_query_id = null )
   {
            return @mysql_field_type(($_query_id ? $_query_id : $this->query_id), $col);
   }

  /**
   * ���������� ���������� ����� �������
   * @param string $_query_id ���������� �������, �� ��������� ���������� - �� ����������
   * @return int
   */
  function num_fields( $_query_id = null )
   {
            return @mysql_num_fields( ($_query_id ? $_query_id : $this->query_id) );
   }

  /**
   * ���������� ����� ���� id ��� �������
   * @param string $table_name ��� �������
   * @return int
   */
  function next_id($table_name)
  {
    $this->query('select max(id) from '.$table_name);
    return $this->result(0,0) + 1;
  }

  /**
   * � ������ ������ �������� � ��������� ���������
   * @param string $msg ��������� �� ������
   */
  function halt($msg)
  {
    echo('<html><meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /><body>
      <div class="center"><b class="message_error">
        ��������, ������:  '.$msg.'<br />�������� ��������� �� ��������� ����������� ���������
      </b></div></body></html>
    ');
    die('');
  }

  /**
   * ���������� ��������������� ������ ������
   * @param int $result ���������� �������
   * @param int $row ����� ������
   * @return array
   */
  function &fetch_assoc($_query_id,$row = null) {
            if (isset($row))
                            mysql_data_seek($_query_id,$row);
      return mysql_fetch_assoc($_query_id);
  }

  /**
   * ���������� id ��������� ����������� ������
   * @return int
   */
  function insert_id()
  { return mysql_insert_id();
  }

 /**
  * ���������� ����������� ���������� ����� � ��������� �������
  * @return int
  */
 function affected_rows() {
          return mysql_affected_rows();
 }

 /**
  * ���������� ������ � ������� ����� � �������
  *
  * @param string $table_name ��� �������
  * @return array
  */
 function &getFieldsNames($table_name) {
   $this->query('select * from '.$table_name.' limit 1');
   $numfields = $this->num_fields();
   $fieldsNames = array();
   for($i =0; $i < $numfields; $i++)
                   $fieldsNames[] = $this->field_name($i);
   return $fieldsNames;
 }


 /**
  * ��������� ������ � �������
  * @param string $table_name ��� �������
  * @param array $insert_array ������������� ������ (��� ���� => ��������)
  * @return void
  */
 function insert($table_name, &$insert_array) {
          if ( count($insert_array) > 0 )
                  $fieldsNames = &$this->getFieldsNames($table_name);

     if ($query = $this->getInsertQuery($fieldsNames, $table_name, &$insert_array))
             $this->query($query);
 }

 /**
  * ��������� SQL-������ ������ ��� ������� (INSERT) ������ � ������e<br>
  * "������������" ������� ������
  * @param array $fieldsNames ������ � ������� ����� � �������
  * @param string $table_name ��� �������
  * @param array $insert_array ������������� ������ (��� ���� => ��������)
  * @return string
  */
 function getInsertQuery(&$fieldsNames, $table_name, &$insert_array) {
     if ( count($insert_array) > 0 ) {
             $insert_query = 'insert into '.$table_name.' values(';
                  foreach ($fieldsNames as $fn) {
                $value = (   isset($insert_array[$fn])
                             ? '"'.addslashes($insert_array[$fn]).'"'
                             : 'null' );
                $insert_query .= $value;
                    $insert_query .= ", ";
                  }
                  return substr($insert_query,0,-2).')';
     }
     return '';
 }


 /**
  * �������� ������ � ������e
  * @param string $table_name ��� �������
  * @param array $update_array ������������� ������ (��� ���� => ��������)
  * @param string $where ������� ��������� - �� ����������
  * @return void
  */
 function update($table_name, &$update_array, $where = null) {
          if ( count($update_array) > 0 )
                  $fieldsNames = &$this->getFieldsNames($table_name);
     if ($query = $this->getUpdateQuery($fieldsNames,$table_name,&$update_array,$where))
             $this->query($query);
           //  echo $query;
 }

 /**
  * ��������� SQL-������ ������ ��� ��������� (UPDATE) ������ � ������e
  * "������������" ������� ������
  * @param array $fieldsNames ������ � ������� ����� � �������
  * @param string $table_name ��� �������
  * @param array $update_array ������������� ������ (��� ���� => ��������)
  * @param string $where ������� ��������� - �� ����������
  * @return string
  */
 function getUpdateQuery(&$fieldsNames, $table_name, &$update_array, $where = null) {
   $update_query = 'update '.$table_name.' set  ';
   $field_query = '';

   foreach ($fieldsNames as $fn) {
      if ( !isset($update_array[$fn]) ) continue;
      $value = '"'.addslashes($update_array[$fn]).'"';
      $field_query .= $fn.' = '.$value.' ,';
   }
   if ($field_query) {
      $update_query.= substr($field_query, 0, -2) ;
      if ( $where )
        $update_query .= ' where '.$where;
      return $update_query;
   }
   return '';
 }


}



?>
