<?php
 /**
  * @package FRONT
  */


 /**
 * ������ ������ outTree ��� �������������� ���� �� ������� $site_tree
 * @todo ����� ������ �������������� ���� �����, �������� ��� ����������� ����� �������� � JOIN
 *
 * @uses Db
 * @uses outTree
 * @uses func.service.php
 *

 * @version 2.11 - 11.09.2007 17:50
 *
 * .04 ������ �������� �� ���� menu ������� ��� ������ ���� �� �������� <br>
 * .05 ������� ������ ��������� ������� <br>
 * .06 �������� ��������� ������ addMenu <br>
 * .06 ����� addMenu ������������ ����� ����� <br>
 * .07 ��������� ���� � ������������� ����� <br>
 * .08 ������������ ����� -  c �������������� ������� separateGroup �� func.service.php <br>
 * .09 �������� �� $_GET['i'] � $_GET['s'] <br>
 * .10 ��������� ���� count - ���� � ������� ���� �������� - one, ����� many <br>
 * .11 ��� ����� ���������
 *
 */
 class Menu {

    /**
    *  @var outTree
    */
    var $ot;

    /**
    *  ������ ����
    *  @var string
    */
    var $template = '';

    /**
    *  id ������������ ������ � ����
    *  @var int
    */
    var $parent;

    /**
    *  �������� ������ ��� ��������
    *  @var int
    */
    var $section;

    /**
    *  ���������� ������� ���� � ������
    *  @var int
    */
    var $count_in_group;

    /**
    *  ���������� ����� - ���� ������ - $count_in_group ���������������
    *  @var int
    */
    var $count_group;

    /**
    *  ��� ������ ��������
    *  @var string
    */
    var $where = '';

    /**
    *  ���� �������� ������ � �������
    *  @var bool
    */
    var $open_in_section = false;

    /**
    *  ������������� ������ ���� (id ������ => '����� ������/��� �����' ��� .php), ��������
    * <code>
    * $this->pagesModul = array (
    *                                   14 => 'catalog/menu_sub',
    *                                   17 => 'portfolio/menu_sub'
    *                                 )
    * </code>
    * ��������� ����������� ����� ������������ ������ ����, �������� �� �����������<br>
    * � �������� �������� ������� ����� � ���������� $GLOBALS['current_outTree']
    *
    *  @var array
    */
    var $pagesModul = array();

    function Menu(
        $template,
        $parent = 1,
        $section = 1,
        $where = '')
     {
       $this->template = $template;
       $this->parent = $parent;
       $this->section = $section;
       $this->where = $where;
     }

    /**
     * ��������� ������
     *
     * @param outTree $menu ������ ����
     * @param outTree $sub ������ ������ ����
     * @param int $parent id ������ ����
     * @return bool ������������������ ������, ��� ���
     */
    function init_section(&$menu, &$sub, $parent)
     { global $db, $site_table, $page;

       $flag_in_section = false;
       //echo 'select page,id from '.$site_table.' where parent="'.$parent.'" and section="0" and pabl="1" order by sort limit 2';
       $rp = new Select($db,'select page,id from '.$site_table.' where parent="'.$parent.'" and section="0" and pabl="1" order by sort limit 2');
       if ($rp->next_row())
        {
           $rsp = new Select($db,'select parent from '.$site_table.' where page="'.$page.'" and section="0" and pabl="1"');
           $page_sub = $rp->result('page');
           if (isset($this->pagesModul[$parent])) {
                           unset($GLOBALS['current_outTree']);
                           $GLOBALS['current_outTree'] = &$sub;
                           include($GLOBALS['moduls_root'].'/'.$this->pagesModul[$parent].'.php');
           }
               else {

                   // ���� ������� �������� �������� � ����� ��������� ������ $parent
                   if ( $rsp->next_row() && ($parent == $rsp->result('parent'))  )
                    { // ���� ������ �������� ������� - �� �������
                      if ( $page_sub != $page )
                         $T = 'SA';

                      // ���� ������ ������, ������
                      elseif ( isset($_GET['s']) || isset($_GET['i']) )
                         $T = 'SA';

                      else
                         $T = 'S';

                      $flag_in_section = true;

                    }
                   else  $T = 'A';

                   $rsp->unset_();

                   $sub->addField(
                       'href',
                       'index' != $page_sub ? textFormat($page_sub) : ''
                   );

                   $sub->addField('page', textFormat($page_sub) );
                   $sub->addField('T', $T );
                   $sub->addField('count',$rp->num_rows > 1 ? 'many' : 'one');
               }

           $menu->addField('sub',&$sub);
        }
       $rp->unset_();

       return $flag_in_section;
     }


    /**
     * ��������� ������ ���� � $this->ot
     *
     * @return bool ������� ��� ���
     */
    function init_()
     { global $db, $site_table, $page;
       //echo 'select * from '.$site_table.' where id="'.$this->parent.'" and section="1" and pabl="1"';
       $r_main = new Select($db,'select * from '.$site_table.' where id="'.$this->parent.'" and section="1" and pabl="1"');
       if (!$r_main->next_row()) return false;
       $main = new outTree();
       $flag_in_section = false; // �������� �� ���������� � ������

// ������������� ������ �������� � �������
       $flag_in_section = $this->init_section($first = new outTree(), $sub = new outTree(), $this->parent) || $flag_in_section;
       $r_main->addFields($sub, $f = array('name','id'));
       $main->addField('first',&$first);
       $r_main->unset_(); unset($sub);

       $r = new Select($db,'select * from '.$site_table.' where parent="'.$this->parent.'" and section="'.$this->section.'" and menu="1" and pabl="1" '.$this->where.' order by sort');
       // echo 'select * from '.$site_table.' where parent="'.$this->parent.'" and section="'.$this->section.'" and menu="1" and pabl="1" '.$this->where.' order by sort';
       if ($r->num_rows)
        {
           $menu = new outTree();
           $num_rows=1;
           while ($r->next_row())
            {
               $sub = new outTree();
               $r->addFields($sub, $f = array('name','id'));
               $sub->addField('num',$num_rows);
               $num_rows+=1;
// ���� ���� �� ��������
               if (1 == $this->section)
                   $flag_in_section
                     = ($this->init_section($menu, $sub, $r->result('id')) || $flag_in_section);

// ���� ���� �� ���������
               else
                {  $page_sub = $r->result('page');
                   $id_sub = $r->result('id');

                //���������� ����.
                   if (isset($this->pagesModul[$id_sub])) {
                                   $GLOBALS['current_outTree'] = &$sub;
                                           include($GLOBALS['moduls_root'].'/'.$this->pagesModul[$id_sub].'.php');
                   }
                       else {
                                   $T =  (   $page_sub != $page
                                   ? 'A'
                                   : (isset($_GET['s']) || isset($_GET['i']) ? 'SA' : 'S')
                           );
                           $sub->addField(
                                   'href',
                                   'index' != $page_sub ? textFormat($page_sub) : ''
                           );
                           $sub->addField('T', $T );
                           $sub->addField('page', textFormat($page_sub) );
                   }
                   $menu->addField('sub',&$sub);

                }

               unset($sub);

            }

           $this->ot = &$main;

           // ���� �� ��������� � ������� - �������
           if ( $this->open_in_section && !$flag_in_section )
               return true;

// ������������� ���� � ������������ ������������
           $cm = ( isset($menu->sub) ? ( is_array($menu->sub) ? count($menu->sub) : 1 ) : 0);
           if ($cm)
            { for($i = 0; $i < ($cm-1); $i++)
                  $menu->sub[$i]->addField('separator','');

              if (isset ($this->count_group))
                        separateGroup($menu->sub,$this->count_group,'separator_group');

  // ���� �� ������ ���������� �����, �� ������ ���������� � ������
              elseif (isset ($this->count_in_group))
                  for($i = $this->count_in_group-1; $i < ($cm-1); $i+=$this->count_in_group)
                      $menu->sub[$i]->addField('separator_group','');

              $main->addField('menu',&$menu);
            }

        }

        return true;
     }


     /**
      * ������������ $this->ot �� ������� this->template
      *
      */
     function print_() {
        if (isset($this->ot) )
           out::_echo( $this->ot, $this->template );
     }

         /** ��������� ����� ���� Menu � ������
          * @param  outTree $ot  ������, � ������� ���������
          * @param  string  $name ��� �����
          * @param  boolean $flagInit ���������������� ���� ��� ���
          * @return void
          */
     function addMenu(&$ot,$name,$flagInit = true) {
             if ($flagInit)
                     $this->init_();
             if (isset($this->ot) ) {
                $this->ot->TEMPLATE = $this->template;
           $ot->addField($name,$this->ot);
             }
     }

 }

?>
