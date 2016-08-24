<?php
/**
 * @package FRONT
 */

 include_once($inc_path."/service/class.menu.php");

// главное меню
 $menuName = 'menu_main';
 unset($menu);
 $menu = new Menu('front/menu/'.$menuName.'.html');
 $menu->addMenu(&$site,$menuName);



// if (1 < $count_) {
//         // подменю
//          $menuName = 'menu_sub';
//          unset($menu);
//         $menu = new Menu('front/menu/'.$menuName.'.html',$parent_,0);
//         $menu->addMenu(&$site,'menu_sub');
// }

 //echoTree($site->menu_main);

?>
