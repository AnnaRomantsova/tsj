<?php
/**
 * ��������� �������
 * @package ALL
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 3.01 - 22.10.2007 16:40
 *
 * .01 ��������� ������� addFieldIMG
 *
 */

include('class.brunch.php');

 /**
 * ������� ���� �� ������
 * @param outTree $outTree ������
 * @param array $fields ������ ���� �����, ������� �������
 */
 function removeFields(&$outTree,&$fields) {
         $cnt = count($fields);
    for ( $i = 0; $i < $cnt; $i++ )
       unset($outTree->$fields[$i]);
 }

 /**
  * ��������� ���� ���� FILE � ������
  *
  * @param outTree $ot ������
  * @param string $fieldF ��� ����
  * @param string $file_name ���� � ����� �� ����� �����
  */
 function addFieldFILE(&$ot, $fieldF, $file_name = '') {
  global $document_root;
  if ($file_name && is_readable( $document_root.( $file=rawurldecode($file_name) ) )) {
            $tmp = new outTree();
            $tmp->addField('size',round(filesize($document_root.$file)/1024,2));
            $tmp->addField('type',strtolower(substr(strrchr($file,'.'),1)));
            $tmp->addField('href', $file );
            $ot->addField( $fieldF, $tmp);
  }
  clearstatcache();
 }

 /**
  * ��������� ���� ���� IMG � ������
  *
  * @param outTree $ot ������
  * @param string $fieldI ��� ����
  * @param string $file_name ���� � ����� �� ����� �����
  */
 function addFieldIMG(&$ot, $fieldI, $file_name = '') {
  global $document_root;
  $isize = @getImageSize($document_root.($file = $path.rawurldecode($file_name))  );
  if ($isize) {
    $tmp = new outTree();
    $tmp->addField('w',$isize[0]);
    $tmp->addField('h',$isize[1]);
    $tmp->addField('src', $file );
    $ot->addField( $fieldI, $tmp);
  }
  else
         $ot->addField('not_'.$fieldI,'');
  clearstatcache();
 }

 /**
  * ������������ $_str � �������� � ��� ��� ���� �� entity<br>
  * �.�. ������ ���������� ������� ��� ������ � html-���
  *
  * @param string $_str
  * @return string
  */
 function textFormat($_str) {
         return htmlspecialchars(stripslashes($_str));
 }

 /**
  * ������������ $_str
  *
  * @param string $_str
  * @return string
  */
 function htmlFormat($_str) {
         return stripslashes($_str);
 }

 /**
  * �������� js-��� ��� ������ ������ � �������
  * <code>
  * <a href="mailto:$_email">
  * </code>
  *
  * @param string $_email
  * @return string
  */
 function get_script_a_mail($_email) {
   if (!$_email) return '';
   $ex = explode('@',$_email);
   return '<script type="text/javascript"><!--
     name = "'.$ex[0].'";
     am = "@";
     domain = "'.$ex[1].'";
     document.write(\'<a href="mailto:\'+name+am+domain+\'">\');
    // --></script>';
 }

  /**
  * �������� js-��� ��� ������ ������
  *
  * @param string $_email
  * @return string
  */
 function get_script_mail($_email) {
         if (!$_email) return '';
    $ex = explode('@',$_email);
    return '<script type="text/javascript"><!--
     name = "'.$ex[0].'";
     am = "@";
     domain = "'.$ex[1].'";
     document.write(name+am+domain);
    // --></script>';
 }

 /**
  * @deprecated �������� js-������ ��� ��������� �� �������� $href
  *
  * @param string $href
  */
 function link_href($href) {
         echo '<script type="text/javascript">
              <!-- //
              location.href = "'.$href.'";
              // -->
          </script>';
    exit;
 }

 /**
  * @deprecated �������� js-������ ��� ��������� �� �������� /error404
  */
 function link_error() {
         link_href('/error404');
 }

 /**
  * ������������� ������ ��� ������� � ������������� ������
  * @param outTree $main ������
  */
 function echoTree(&$main) {
         outTree_::echoOutTree($main);
         exit;
 }

 /**
  * @deprecated �������� js-������ ��� ��������� �� �������� $action � ��������� ���������� POST
  *
  * @param string $action
  * @param array $postvar �����.������ ���������� (��� => ��������) ��� �������� � POST
  */
 function reload($action = '?', $postvar = array() )  {
     echo '<html><body><form method="post" action="'.$action.'" id="reload">';
     foreach ( $postvar as $key => $value)
        echo '<input type="hidden" name="'.$key.'" value="'.textFormat($value).'">';
     echo '
<input type="hidden" name="reload_" value="reload">
</form>
<script type="text/javascript">document.getElementById("reload").submit();</script></body></html>';
     exit;
 }


 /**
  * �������� HTML-�������� � js-�������� � ����� $str
  *
  * @param string $str
  */
 function echoJS($str)  {
     echo '<html><body>
<script type="text/javascript">
'.$str.'
</script></body></html>';
     exit;
 }

 /**
  * �������� HTML-�������� � js-�������� � ������� js-������� alert
  *
  * @param string $str ��� �������� �������
  */
 function alert($str)  {
          echoJS('alert("'.addslashes($str).'");');
 }


 /**
  * �������������� ���� � ����� ��������� �������� �����
  * �������� 1 ����, 2 �����, 5 ������
  *
  * @param outTree $ot
  * @param string $field ��� ����
  * @param int $count �����
  */
 function getCountEnd(&$ot,$field,$count) {
         $val = 5;
         $c = round($count)%10;
         if (1 == $c)
                 $val = 1;
        elseif ($c && ($c < 5))
                 $val = 2;
         $ot->addField($field,$val);
 }


 /**
  * ����� �� ������� ���������� ����� ������ outTree
  *
  * @param array $sub ������ outTree
  * @param int $cg ����������� �����
  * @param string $field ���� �����������
  */
 function separateGroup(&$sub,$cg,$field = 'separator') {
           $c =  count($sub);
           if ($c > 1) {
                    $separators =  array_fill(0,$cg,0);
                    for ($i = 0; $i < $c; $i++)
                            $separators[$i%$cg]++;

                    $separator = -1;
                    for ($i=0;$i<$cg;$i++) {
                            if ($separators[$i]) {
                                $separator+=$separators[$i];
                                if ($separator < ($c-1))
                                        $sub[$separator]->addField($field,'');
                            }
                    }
           }
 }

 /**
  * �������� �� $uri � ���������� �������
  *
  * @param string $uri
  */
 function go($uri) {
         header('Location: '.$uri);
        exit;
 }


function ru2Lat($string) {

        $rus = array('�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�');
        $lat = array('yo','zh','tc','ch','sh','sh','yu','ya','YO','ZH','TC','CH','SH','SH','YU','YA');
        $string = str_replace($rus,$lat,$string);
        $string = strtr($string,
            "��������������������������������������������������",
            "ABVGDEZIJKLMNOPRSTUFH_I_Eabvgdezijklmnoprstufh_i_e");

        return($string);
}


?>
