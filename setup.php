<?

/**
 * настройки сайта
 * подключение к базе, пароль к системе администрирования
 * @package ALL
 */


//администрирование
 $adminpass='1';      //пароль администратора

 $document_root=$_SERVER['DOCUMENT_ROOT'];
 $moduls_root=$document_root.'/site_builder/moduls';
 $inc_path=$document_root.'/site_builder/includes';

 $auth_path='/admin/exit.php';

//база данных
 $db_host='localhost';
 $db_user='root';
 $db_password='';
 $db_name='tsg';


//база данных
// $db_host='mysqlserver';
// $db_user='z33656_pl';
// $db_password='p8426i4vCq';
// $db_name='z33656_pl';

 //error_reporting(E_ALL & E_NOTICE);
// error_reporting(0);
// ini_set('session.use_trans_sid','0');

?>
