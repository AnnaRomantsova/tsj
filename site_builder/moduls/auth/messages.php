<?php

include ('config.php');

$FILENAME = $front_html_path . 'auth.html';
$reg_FILENAME = $front_html_path . 'reg.html';
$forgot_FILENAME = $front_html_path.'forgot.html';

require_once($inc_path."/phpmailer/func.mailViaSMTP.php");

//require_once($inc_path."/phpmailer/func.mailViaSMTP.php");


$patch = $HTTP_SERVER_VARS [HTTP_REFERER];
$main->addfield ( 'site_path', $patch );

 //если нажали выход
  if  (isset($_GET['exit'])) {

      //$_SESSION['vendor']=null;
      //$_SESSION['user']=null;
      $_SESSION = array ();
      setcookie("e_mail",'');
      setcookie("password",'');
      unset($_COOKIE['e_mail']);
      unset($_COOKIE['password']);
      //var_dump($_SESSION);
      header ( "location: http://" . $_SERVER['HTTP_HOST'] );
  };

// echotree($main);
  if ($_GET['forgot']>0) {
       unset($main);
       $main = new outTree($forgot_FILENAME);
  }
//если нажали "Войти' и ввели логин, пароль
  if (isset ( $_POST ['log_in'] )) {
        //проверочки
        $r = new Select ( $db, 'select * from users where email="' . htmlspecialchars ( addslashes ( $_POST ['email'] ) ) . '" and pass="' . md5 ( $_POST ['password'] ) . '"' );
        if ($r->next_row ()) {
                $_SESSION = array ();
                if ($r->result('is_chairman') == '1')  $_SESSION ['user'] = $r->result ( 'id' );
                   else  $_SESSION ['vendor'] = $r->result ( 'id' );

                //если юзер нажал "запомнить меня"
                if (isset ( $_POST ['save-me'] )) {
                        $username = "" . addslashes ( $_POST ["email"] ) . "";
                        $passw = "" . addslashes ( $_POST ["password"] ) . "";
                        $pasw = md5 ( $passw );
                        setcookie ( 'e_mail', $username, time () + 864000 );
                        setcookie ( 'password', $pasw, time () + 864000 );
                } ;

                setcookie("id_house",'');
                //если председатель идем в ред. страницы о нас личного кабинета.
                if ($r->result('is_chairman') == '1') header ( "location: http://" . $_SERVER['HTTP_HOST'].'/about');
                //если поставщик то идем в личный кабинет
                 else header ( "location: http://" . $_SERVER['HTTP_HOST'].'/lk');

        } else {
                //сохраняем введенные параметры
                foreach ( $_POST as $pkey => $val ) {
                        if ($_POST [$pkey] != '' ) {
                                $par .= '/' . $pkey . '/' . htmlspecialchars ( strip_tags ( stripslashes ( urldecode ( $val ) ) ) );
                        };
                };
               unset($main);
               $main = new outTree ( $FILENAME );
               foreach ( $_POST as $pkey => $val ) {
                        if (($pkey =='email') or ($pkey =='password') )
                                $main->addField ( $pkey, urldecode ( $val ) );
               };
               $message = 'Логин или пароль введен не правильно!';
               $main->addField ( 'message', 'Ошибка! ' . $message );

        };

//если нажали "Регистрация"
} else if (isset ( $_POST ['register'] ))  {

        //проверочки
        $err = '';
        if (strip_tags ( addslashes ( $_POST ['email'] ) ) == '' or strip_tags ( addslashes ( $_POST ['pass1'] ) ) =='' or strip_tags ( addslashes ( $_POST ['pass2'] ) ) == '')
                $err = 1;
        if (! preg_match ( "([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)", $_POST ['email'] ) and $_POST ['email'] != "") {
                $err = 2;
        };
        if ($_POST['pass1'] !== $_POST['pass2']) $err=4;

        $r = new Select ( $db, 'select * from users where email="' . strip_tags ( addslashes ( $_POST ['email'] ) ) . '"' );
        if ($r->num_rows > 0)
                $err = 3;
        //удачно
        if ($err == '') {

              foreach ( $_POST as $key => $value)
                $$key= htmlspecialchars ( addslashes ($value));


              $r1 = new Select ( $db, "insert into users (pass,pass_text,name,sort,fio,act_category,inn,link,image1,about,tel,adress,email,is_chairman,surname,secname,date,id_company,id_city)
                        values('".md5($pass1)."','$pass1','$name','1','$fio','$act_category','$inn','$link','$image1','$about','$tel','$adress','$email','0','','',".time().",'',$city)");
              //echo  "insert into users (pass,pass_text,name,sort,fio,act_category,inn,link,image1,about,tel,adress,email,is_chairman,surname,secname,date,id_company,id_city)
               //         values('".md5($pass1)."','$pass1','$name','1','$fio','$act_category','$inn','$link','$image1','$about','$tel','$adress','$email','0','','',".time().",'',$city)";

              //$main->addField ( 'log', '' );
              $_SESSION ['vendor'] = mysql_insert_id ();
              //письмо на мыло
              $letter = "Спасибо за регистрацию на сайте " . $GLOBALS ['mainOutTree']->SERVER_NAME."
Ваш Логин: ".$_POST ['email']. "\r\nПароль: ".$_POST ['password'];
              $mail = &newViaSMTP('mail_register');
              $mail->Subject = "Регистрация на сайте " . $GLOBALS ['mainOutTree']->SERVER_NAME;
              $subm = sendViaSMTP($mail,$letter,false);

               header ( "location: /lk" );
        } else {

                unset($main);
                $main = new outTree ( $reg_FILENAME );

                switch ($err) {
                  case '1' :
                          $message = "Вы не ввели E-mail или пароль";
                          break;
                  case '2' :
                          $message = "Введите корректный E-mail адрес!";
                          break;
                  case '3' :
                          $message = 'Такой E-mail уже существует!';
                          break;
                  case '4' :
                          $message = 'Пароли не совпадают!';
                          break;
                };
                if ($err>0) $main->addField ( 'message', 'Ошибка! ' . $message );

                $r = new Select ( $db, 'select * from act_category order by name' );
                while ($r->next_row() > 0) {
                    unset($sub);
                    $sub = new outTree();
                    $r->addFields($sub,$ar=array('id','name'));
                    if ($r->result('id')==$act_category) $sub->addfield('selected','selected');
                    $main->addField('act_category',$sub);
                };
                $r = new Select ( $db, 'select * from city order by name' );
                while ($r->next_row() > 0) {
                    unset($sub);
                    $sub = new outTree();
                    $r->addFields($sub,$ar=array('id','name'));
                    if ($r->result('id')==$city) $sub->addfield('selected','selected');
                    $main->addField('city',$sub);
                };
                foreach ( $_POST as $pkey => $val )
                    $main->addField ( $pkey, urldecode ( $val ) );

                $r = new Select ( $db, 'select * from site_pages where id =5' );
                if ($r->next_row() > 0) {
                       $main->addField('license',strip_tags($r->result('content')));
                };

        };
        //восстановление пароля
} else if  ($_GET ['register'] >0) {
    unset($main);
    $main = new outTree ( $reg_FILENAME );
    $r = new Select ( $db, 'select * from act_category order by name' );
    while ($r->next_row() > 0) {
         unset($sub);
         $sub = new outTree();
         $r->addFields($sub,$ar=array('id','name'));
         if ($r->result('id')==$act_category) $sub->addfield('selected','selected');
         $main->addField('act_category',$sub);
     };
     $r = new Select ( $db, 'select * from city order by name' );
     while ($r->next_row() > 0) {
         unset($sub);
         $sub = new outTree();
         $r->addFields($sub,$ar=array('id','name'));
         if ($r->result('id')==$city) $sub->addfield('selected','selected');
         $main->addField('city',$sub);
     };
     $r = new Select ( $db, 'select * from site_pages where id =5' );
     if ($r->next_row() > 0) {
         $main->addField('license',strip_tags($r->result('content')));
     };

} else if (isset ( $_POST ['repair'] )) {
        unset($main);
        $main = new outTree ( $forgot_FILENAME );
        //проверочки
        if (! preg_match ( "/^([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)$/", htmlspecialchars ( stripslashes ( $_POST ['email'] ) ) ) and htmlspecialchars ( stripslashes ( $_POST ['email'] ) ) != "")
             $main->addField ( 'message', 'Введите корректный E-mail адрес!' );
        else {
             if ( $_POST ['email'] !=='') {
              $r = new Select ( $db, 'select * from users where email="' . strip_tags ( addslashes ( $_POST ['email'] ) ) . '"' );
              $r->next_row ();
              if ($r->num_rows == 1) {

                 $letter = "Ваш пароль : " . $r->result ( 'pass_text' );
                 $mail = &newViaSMTP('mail_register');
                 $mail->Subject = 'Восстановление пароля на сайте ' . $GLOBALS ['mainOutTree']->SERVER_NAME;
                 $subm = sendViaSMTP($mail,$letter,false);

                 if ($subm>0) {
                         unset($main);
                         $main = new outTree ($FILENAME );
                         $main->addField ( 'message', 'Письмо c паролем отправлено на Ваш E-mail.' );
                   }
                   else $main->addField ( 'message', 'Ошибка отправки письма.' );

              } else $main->addField ( 'message','Указанный E-mail не зарегестрирован на нашем сайте.');
             };
       }

       foreach ( $_POST as $pkey => $val ) {
                if (($pkey =='email') or ($pkey =='password') )
                        $main->addField ( $pkey, urldecode ( $val ) );
       };
};

if (isset ( $main )) {
        $site->addField ( $GLOBALS ['currentSection'], &$main );
        unset ( $main );
}

?>