<?php
/**
 * @package FRONT
 */

  $user_company = 0;
  if ( $_SESSION ['user']>0) {
     $r1 = new Select($db,'select * from users where id="'.$user.'"');
     if ($r1->next_row())
     $user_company = $r1->result('id_company');
  };

  //меню когда выбран дом или зашел председатель
  if (($_COOKIE['id_house']>0) || $user_company > 0) {
              $menuName = 'menu_sub';
              unset($menu);
              $menu = new Menu('front/menu/'.$menuName.'.html',135,0,' and id not in (152)');
              $menu->addMenu(&$site,$menuName);
             // if ($user_company == 0) $site->menu_sub->menu->sub[4]->href='zakupki';

     if (isset($site->menu_sub->menu->sub[5]))
                $site->menu_sub->menu->sub[5]->addfield('sep','');
   }
   else if ( $_SESSION ['vendor']>0) {
          //echo "1";
          $menuName = 'menu_sub';
          unset($menu);

          $menu = new Menu('front/menu/'.$menuName.'.html',135,0,' and id in (152,144)');
          // echotree($menu);
          $menu->addMenu(&$site,$menuName);
          $site->menu_sub->menu->sub[1]->name='Мои предложения';
   };

  //echotree($site->menu_sub);
?>
