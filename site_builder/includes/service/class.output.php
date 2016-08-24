<?php

/**
 * @package ALL
 *
 * ������ �������������
 *
 * @author Max Kudryavcev (icq 349-870-107)
 * @author �������� Milena Eremeeva (fenyx@ya.ru)
 * @version 1.10 - 21.09.2007 14:30
 *
 * .05 ������� ��������� ����� outTree_ <br>
 * .05 ��������� ����������� ���������������� "��������" ���������� (����� �� ������ ������ ������). <br>
 * .06 ������� ����� outTree_::mergeFields - ������������ ���� ���� � ������� <br>
 * .07 ��������� ������� <br>
 * .08 ������� ����� outTree_::getFirst -  �������� ������ ����� ��� ����� ������ <br>
 * .09 ������� ����� outTree_::getLast -  �������� ��������� ����� ��� ����� ������ <br>
 * .10 ��������� ����������������� ����������� "�������� ����" - ������ ������������ ! � ������ �������� ���� <br>
 *
 */

 define('OPEN_TAG','[%');   //����������� ���
 define('CLOSE_TAG','%]');  //����������� ��� (�� ������ �������� � ���� ��������� ������ ������������ ����)
 define('SEP','/');         //����������� ����� ������ ������ � �������
 define('UP','..');         //��������� �� ������� ����

 $templates_path = $document_root.'/shablons/';


 $GLOBALS['mainOutTree'] = new outTree();

 /* ������ ���������� ��� ������
  * ������ ���� ����� ����: ���� �������� ������ outTree,
  * ���� �������� �������, ���� ������� ���������
  */
  class outTree
  { var $PAR;  // ������ �� ��������

    /**
     * @param string $template ������ �� ���������
     * @return outTree
     */
    function outTree($template=null) {
            if (isset($template))
                    $this->TEMPLATE = $template;
    }

   /** ���������� ���� ������ <br>
     * ���� ����������� ���� � ������ ����� ��� ���������� <br>
     * �� �� ����������������� � ��������������� ������� <br>
     * ������ ������������ ����������������� �����: <br>
     * PAR <br>
     * TEMPLATE <br>
     * TEMPLATE_NOT_FILE <br>
     * ITEMTYPE <br>
     * �����, ������������ �� !
     *
     * @param string $name ��� ���� �������
     * @param mixed $val �������� ����, ����� ���� outTree
     */
    function addField($name,$val)
     { if (is_object($val)) $val->PAR=&$this;
       if (isset($this->$name))
        {  if (!is_array($this->$name))
            {  $tmp1 = array();
                if  ( is_object($this->$name) )
                   $tmp1[] = &$this->$name;
                else
                   $tmp1[] = $this->$name;
               $this->$name = &$tmp1;
            }

           $tmp = &$this->$name;
           if  ( is_object($val) )
                $tmp[] = &$val;
           else
                $tmp[] = $val;
        }
       else
        {  if  ( is_object($val) )
             $this->$name=&$val;
           else
             $this->$name=$val;
        }
     }
  }


 class out
  {
    /* ��������� � ����� ������� �� ����� ��� ������ (���� $is_str=1) $template_file
     * &$root - ��������� �� ���� ������ ������
     */
    function _echo(&$root,$template_file,$parent=null,$is_str=0,$I=0,$COUNT=1)
     {
       $ext_out=$int_out='';

       if (!$is_str)
        { $fp=@file($GLOBALS['templates_path'].$template_file);
          if (!$fp)
           { echo ' <p><b> ���� '.$GLOBALS['templates_path'].$template_file.' �� ��������! </b></p>';
             return false;
             //exit;
           }
          $str=implode('',$fp);
        }
       else
          $str=$template_file;
       $flag=true;
       $_exit=false;
       $evl_str='';
       while (!$_exit)
        {
          if ($flag)
           { $tag=OPEN_TAG;
             $out='ext_out';
           }
          else
           { $tag=CLOSE_TAG;
             $out='int_out';
           }

          $_pos=strpos($str,$tag);
          if (!$flag)                                          // �� ������ ����������� �����
           { $xpos=strpos($str,OPEN_TAG);
             while ( (!($xpos===false)) && ($xpos<$_pos) )
              { $_pos=strpos($str,$tag,$_pos+strlen($tag));
                $xpos=strpos($str,OPEN_TAG,$xpos+strlen(OPEN_TAG));
              }
           }                                                   //============================

          if ($_pos===false)
           { $$out=$str;
             $_exit=true;
           }
          else
           { $$out=substr($str,0,$_pos);
             $flag=!$flag;
             $str=substr($str,$_pos+strlen($tag));
           }

          if ($out=='int_out')
           { //echo '!'.$$out.'!';
             if ($is_str)
                out::evl($root,$$out,&$parent,$I,$COUNT);
             else
                $evl_str.=$$out;
           }
          else
           {// echo '!!!';
             if ($evl_str>'')
              { out::evl($root,$evl_str,&$parent,$I,$COUNT);
                $evl_str='';
              }
             echo $$out;
           }
        }
     }

    function evl(&$root,$cmd,$parent=null,$I=0,$COUNT=1)
     {
       $cmd=trim($cmd);
       switch ($cmd[0]) //����� ������� ������ ������ �������
        { case '(' : //)
                     eval(substr($cmd,1,-1));
                     break;

          case '{' : //}
                     out::_echo($root,substr($cmd,1,-1),&$parent,1,$I,$COUNT);
                     break;

          case '[' : //]
                     $template = isset($root->TEMPLATE) ? $root->TEMPLATE : substr($cmd,1,-1);
                     out::_echo($root,$template,&$parent,intval(isset($root->TEMPLATE_NOT_FILE)),$I,$COUNT);
                     break;

          case '?' : // switch
                     $coms=out::parsSwitch(substr($cmd,1));
                     for ($j=0; $j<count($coms[0]); $j++)
                      { if ($root==$coms[1][$j])
                         { out::_echo($root,$coms[0][$j],&$parent,1,$I,$COUNT);
                         }
                      }
                     break;
          case '.': //������� ����
                     $cmd=explode(SEP,$cmd,2);
                     if (isset($parent->PAR))
                        out::evl($parent,$cmd[1],&$parent->PAR,$I,$COUNT);
                     else
                        out::evl($parent,$cmd[1],null,$I,$COUNT);
                     break;

          case '*': //�������� ������
                     $cmd=explode(SEP,$cmd,2);
                     out::evl($GLOBALS['mainOutTree'],$cmd[1],&$root,$I,$COUNT);
                     break;

          default  : $cmd=explode(SEP,$cmd,2);
                                   $fieldName = $cmd[0];
                                   if (('!' == $fieldName[0]) && !isset($root->{substr($fieldName,1)})) {
                                           if (count($cmd)>1)
                                                   out::evl($root->$cmd[0],$cmd[1],&$root);
                                           break;
                                   }
                     if (isset($root->$cmd[0]))
                      { if (count($cmd)>1)
                         { if (is_array($root->$cmd[0]))
                            { //var_dump($root->$cmd[0]);
                              $cnt=count($root->$cmd[0]);
                              $pr=&$root->$cmd[0];
                              for ($j=0; $j<$cnt; $j++)
                               { out::evl($pr[$j],$cmd[1],&$root,$j,$cnt);
                               }

//                              $c = foreach_($pr,$keys = array());
//                              for ($j=0; $j<$c; $j++)
//                                out::evl($pr[$keys[$j]],$cmd[1],&$root,$j,$c);


                            }
                           else
                              out::evl($root->$cmd[0],$cmd[1],&$root);
                         }
                        else
                         { echo $root->$cmd[0];
                         }
                      }
        }
     }

    function parsSwitch($str) //��������� ������ �� �� ��� � { } � �� ����
     {
       $ext_out=array();
       $int_out=array();
       $open_tag='{';
       $close_tag='}';
       $flag=true;
       $_exit=false;
       $evl_str='';
       while (!$_exit)
        {
          if ($flag)
           { $tag=$open_tag;
             $out=@$ext_out;
           }
          else
           { $tag=$close_tag;
             $out=@$int_out;
           }

          $_pos=strpos($str,$tag);
          if (!$flag)                                          // �� ������ ����������� �����
           { $xpos=strpos($str,$open_tag);
             while ( (!($xpos===false)) && ($xpos<$_pos) )
              { $_pos=strpos($str,$tag,$_pos+strlen($tag));
                $xpos=strpos($str,$open_tag,$xpos+strlen($open_tag));
              }
           }                                                   //============================

          if ($_pos===false)
           { if ($flag)
                $ext_out[]=$str;
             else
                $int_out[]=$str;
             $_exit=true;
           }
          else
           { if ($flag)
                $ext_out[]=trim(substr($str,0,$_pos));
             else
                $int_out[]=substr($str,0,$_pos);
             $flag=!$flag;
             $str=substr($str,$_pos+strlen($tag));
           }

        }
       return array($int_out,$ext_out);
     }
  }

/**
 * ��������� ����� ��� ������ � ��������� outTree
 */
class outTree_ {

 /**
  * ��� ������� - ������� ��� ������
  *
  * @param outTree $ot
  * @param string $name ��� ����
  * @param int $color ���� ����� ������ �������
  */
 function echoOutTree($ot,$name='',$color=128) {
         if ('PAR' !== $name) {

                 if ($color < (255-20)) $color+=8;
                 $col = (dechex($color));
                echo '<div style="padding: 10px 0 10px 20px; margin: 0 1px 2px 0; clear:both;border: 1px solid #'.$col.$col.$col.'">';
                if (is_object($ot)) {
                        echo '<span style="color:#00c">'.$name.' (Object '.get_class($ot).')</span>';
                         $fields = get_object_vars($ot);
                         foreach ($fields as $key => $value) {
                                outTree_::echoOutTree($value,$key,$color);
                         }
                }
                elseif (is_array($ot)) {
                        echo '<span style="color:#0b0"><b>'.$name.'</b> (Array '.count($ot).')</span>';
                        foreach ($ot as $key => $value) {
                                outTree_::echoOutTree($value,$key,$color);
                        }
                }
                else
                        echo '<span><b>'.$name.'</b> ('.gettype($value).')</span><br clear="all" />'.$ot;
                echo '</div>';
         }
 }

 /**
  * ���������� ��������� �� ���� � ������� �� �������� ITEMTYPE
  * ���� �� ����� - NULL
  *
  * @param outTree $_this
  * @param string $field    ��� �������
  * @param string $ITEMTYPE ����� ����
  * @param bool   $flag     ���� ����  - ���������� ����� ����
  * @return outTree
  */
 function &find(&$_this,$field,$ITEMTYPE,$flag = true) {

         if (is_array($_this->$field)) {
                 $c = count($_this->$field);
                 for ($i = 0; $i < $c; $i++)
                         if (is_object($_this->{$field}[$i]) && ($ITEMTYPE == $_this->{$field}[$i]->ITEMTYPE))
                                return  $flag ? $_this->{$field}[$i] : $i;
         }

         elseif (is_object($_this->$field) && ($ITEMTYPE == $_this->$field->ITEMTYPE))
                 return $flag ? $_this->$field : 0;
    return NULL;

 }

 /**
  * ����������� ����
  *
  * @param outTree $out   ������ ���������
  * @param outTree $in    ���� �������
  * @param string $field ��� ����
  */
 function changeParent(&$out,&$in,$field) {
         if (isset($out->$field)) {
             $in->$field = &$out->$field;
             $in->$field->PAR = &$in;
             unset ($out->$field);
         }
 }

 /**
  * ������������ � ���� $tree->$field1 ���� $tree->$field2
  * @param outTree $tree ������
  * @param string $field1 � ���� ������������
  * @param string $field2 ��� ������������
  */
 function mergeFields(&$tree,$field1,$field2) {
         if (isset($tree->$field2)) {
                 if (is_array($tree->$field2)) {
                         $c = count($tree->$field2);
                         for ($i = 0; $i < $c; $i++)
                                 $tree->addField($field1,&$tree->{$field2}[$i]);
                 }
                 else
                         $tree->addField($field1,&$tree->$field2);
         }
 }

 /**
  * �������� ������ ������� � ���� ������
  *
  * @param outTree $tree ������
  * @param string $field ��� ����
  * @return outTree
  */
 function &getFirst(&$tree,$field) {
        if (is_array($tree->$field))
                return $tree->{$field}[0];

        elseif (isset($tree->$field))
                return $tree->$field;

        return null;
 }

 /**
  * �������� ��������� ������� � ���� ������
  *
  * @param outTree $tree ������
  * @param string $field ��� ����
  * @return outTree
  */
 function &getLast(&$tree,$field) {
        if (is_array($tree->$field))
                return $tree->{$field}[count($tree->{$field})-1];

        elseif (isset($tree->$field))
                return $tree->$field;

        return null;
 }

}

?>
