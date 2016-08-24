<?php

 /**
  * @package ALL
  */

 /**
 * ����� ��� ������ c ������� ������, ����������� � ����� �������
 *
 * @uses Db
 * @uses outTree
 *
 * @author Milena Eremeeva (fenyx@yandex.ru)
 * @version 2.03 - 11.01.2007 10:30
 *
 * .03 ��������� ����������� ��������� ������ Db (�� ��������� ������� ���������� $db)<br>
 *     ��������� ��������� ������������
 */
 class Brunch {

          /**
          *  ��� �������
          *  @var string
          */
          var $table;

          /**
          *  ��� ���� - ��� ����� �������� �����
          *  @var string
          */
          var $name;

          /**
          *  id ������� �������
          *  @var int
          */
          var $id;

          /**
          *  ������� ������� �������
          *  @var int
          */
          var $level;

          /**
          *  ������ id ���� ������ �� ������� �� �����, ��������������� ��������
          *  @var array
          */
          var $ids = array();

          /**
          *  ������ �������� ���� ������ �� ������� �� �����, ��������������� ��������
          *  @var array
          */
          var $names = array();

          var $db;

          function Brunch($_id, $_table, $_where = ' AND pabl="1"', $_db = null, $_name = 'name') {
                    $this->table = $_table;
              $this->where = $_where;
              $this->name = $_name;
              $this->id = $_id;
              $this->db = !isset($_db) ? $GLOBALS['db'] : $_db;
              $this->initLevel();
          }

          function initLevel() {
                    $this->level = $this->getLevel($this->id);
          }

          /**
            * ���������� ��������� ������� �������� �������,
            * id � �������� ���� ������ �� ����� �� �������
            * @param int $_id � ������ id ����������
            * @return int
          */
          function getLevel($_id) {
                    global $db;
               //     echo 'select parent,'.$this->name.' from '.$this->table.' where id="'.$_id.'"'.$this->where;
              $r = $db->query('select parent,'.$this->name.' from '.$this->table.' where id="'.$_id.'"'.$this->where);
              if ($db->next_row()) {
                       $parent = $db->result(0,0,$r);
                 $level = $parent ? 1+$this->getLevel($parent) : 0;
                 $this->ids[$level] = $_id;
                 $this->names[$level] = textFormat($db->result(1,0,$r));

                       return $level;
              }
              else
                 return -1000;
          }

         /**
         * ��������� sub � ���e ���� Path � $ot
         * @param outTree $path ������, � ������� ���������
         * @param string $href �����, � �������� ������������� id
         * @param array $levels_ignore ������, ������� �� ������ ���� ��������
         * @param bool $with_end �������� �����, ��� ���
         * @return void
         */
         function addFieldPATH(&$path, $href, &$levels_ignore, $with_end = false) {
                   // var_dump($this->ids);
                   // echo $this->id;
                    foreach($this->ids as $key => $value) {
                                $flag_with_end = ($with_end ?  true : ($this->id != $value) );
                              //  echo $this->id." ".$value."<br>";
                               // $flag_with_end = true;
                           if ($key && $flag_with_end)  { // ��� ����� � ������
                           //if ($flag_with_end)  { // ���� ����� ��� - �� ���.
                               $sub =  new outTree();
                               $sub->addField('separator','');
                               $sub->addField('href',$href.$value);
                               $sub->addField('name',$this->names[$key]);
                               $sub->addField('T', in_array($key,$levels_ignore) ? 'S' : 'A');
                               $path->addField('sub',&$sub);
                               unset($sub);
                           }
               }
             // echotree($path);
         }


        function addFieldPATHmy(&$path, $href, &$levels_ignore, $with_end = false) {
                   // var_dump($this->ids);
                   // echo $this->id;
                    foreach($this->ids as $key => $value) {
                                $flag_with_end = ($with_end ?  true : ($this->id != $value) );
                              //  echo $this->id." ".$value."<br>";
                               $flag_with_end = true;
                           if ($key && $flag_with_end)  { // ��� ����� � ������
                           //if ($flag_with_end)  { // ���� ����� ��� - �� ���.
                               $sub =  new outTree();
                               if ($this->id != $value) $sub->addField('separator','');
                               $sub->addField('href',$href.$value);
                               $sub->addField('name',$this->names[$key]);
                               $sub->addField('T', in_array($key,$levels_ignore) ? 'S' : 'A');
                               $path->addField('sub',&$sub);
                               unset($sub);
                           }
               }
             // echotree($path);
         }
 }




?>
