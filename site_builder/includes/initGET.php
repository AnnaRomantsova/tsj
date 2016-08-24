<?php

 /** 
 * ������������� ��������� $_GET �� ������� �� ��������<br>
 * ��������� �������� ��� (�������� �������� �����)<br>
 * ��������� URI ��������� ������� /var1/var2/var3/var4/var5...<br>
 * var1 ������������� ���������� $page, ��������� ��������� �������������� 
 * ��� ������ GET ?var2=var3&var4=var5...<br>
 * !�����! �������� index ���������� GET ����� ���� ��������� ������ ������ �������� ?var1=var2&...
 * 
 * @package FRONT 
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.03 - 14.06.2007 16:50
 * 
 * .01 $page ���������������� ������, ���� �� ���� ������������������� ������ <br>
 * .02 ������ ���������� ������������� $_GET['sect'],$_GET['id'] <br>
 * .03 ��������� ���������� $page ! �������� - ��� ������ ���� ������� ����� !
 * 
 */ 
 
     $PATH = $_SERVER['REQUEST_URI'];
     if (strstr($PATH, '?')) 
            $PATH = substr($PATH, 0, strpos($PATH, '?'));

     $PATH = ( $PATH = trim($PATH, '/') ? explode('/', trim($PATH, '/')) : array() );
     $c_PATH = count($PATH);

	 
     $GLOBALS['strPATH'] = '/'.implode('/',$PATH);
     if (!isset($page))
     	$page = addslashes( 0 != $c_PATH ? array_shift($PATH) : 'index' );
     
     for ($i = 0; $i < $c_PATH; $i+=2) {
     	$_GET[$PATH[$i]] = $PATH[$i+1];
     }
     
?>