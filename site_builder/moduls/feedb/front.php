<?
  include('config.php');

 require_once($inc_path."/phpmailer/func.mailViaSMTP.php");

 $main = &incPage($front_html_path.'front.html');

 foreach ( $_POST as $key => $value)
               $$key=$value;

 // ������ ��� JavaScript � �������� ������� ������������ �����
 $flag = true; $str_fieldsWF = '';
 foreach ( $fieldsWithoutFail as $value) {
         //echo "dd";
       $str_fieldsWF.= ('\''.$value.'\',');
       $flag = $flag && !empty($$value);
 }

  $r = new Select($db,'select * from site_pages where id="9"');
 // if ($r->next_row()) $r->addFieldHTML($main,'content');


 $main->addField('fieldsWithoutFail',substr($str_fieldsWF,0,-1));

 if (isset($_POST['send']) && $flag)  {

 $text = date('d.m.Y').' � '.date('H:i:s').' �� ����� '.$_SERVER['SERVER_NAME'].'� ������� �������� ����� ���� ���������� ���������.
���� ���������: '.$tema.'
��: '.stripslashes($fio).'
Email: '.stripslashes($email).'
�����: '.stripslashes($text);

         $mail = &newViaSMTP('mail_feed');
         $mail->Subject = $tema;
         $subm = sendViaSMTP($mail,$text,false);
       //  header('Location: '.$GLOBALS['strPATH'].'?subm='.$subm.'#f');



// if (isset($subm))
    $mess = ($subm>0 ? 'ok' : 'er');

    $main->addField('message',$mess);

 }

 unset($main);

?>

