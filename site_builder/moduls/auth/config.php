<?php 
 $GLOBALS['modulName'] = $modulName = 'auth';
 $modulCaption = '�����������';
 
 $back_html_path='back/'.$modulName.'/';
 $front_html_path='front/'.$modulName.'/';

 $fcount = $GLOBALS['fcount'] = $GLOBALS[$modulName.'_fcount'];
 $acount = $GLOBALS[$modulName.'_acount'];
 $lcount = $GLOBALS[$modulName.'_lcount'];
 
 $table_name = $GLOBALS['table_name'] = $GLOBALS[$modulName.'_table'];

 $files_path = '/_files/Moduls/'.$modulName.'/images/';
 $extent = array('jpg','png','gif');
 
 $arFiles = array(
 	'image1' => array($extent,$files_path,'image'), 
 );
?>