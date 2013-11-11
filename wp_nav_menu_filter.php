<?php
/*
Plugin Name: WordPress wp_nav_menu Filter
Plugin URI: http://www.travishoglund.com
Description: Want to show submenus on internal pages controlled by your main navigation?  Now you can!  Plugin supports passing of Page Title along with passing on Page ID.
Version: 1.1
Author: Travis Hoglund
Modified: Chris Burnham chris@burnhamup.com
Aurhor URI: http://www.travishoglund.com
License: GPL2
*/

/*  Copyright 2011  Travis Hoglund  (email : travis@travishoglund.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_filter('wp_nav_menu_objects', 'wp_nav_menu_filter', 10, 2);

function wp_nav_menu_filter ($items, $args) {
  // Check $wp_obj  for array
  $selectedId = $args->submenu;
  if (!isset($selectedId) || empty($selectedId))
    return $items;
	
  $menuType = $args->menutype;
  if (!isset($menuType) || empty($menuType)) {
	$menuType = 'siblings';
  }
  
  $showParent = false;
  $showSelf = true;
  $showGrandparent = false;
  $depth = 0;
  $ancestors = array();
  switch ($menuType) {
	case 'level2':
		$showSelf = false;
		$depth = 3;
		break;
	case 'children':
		$showParent = false;
		$depth = 1;
		break;
	case 'siblings':
		$showParent = true;
		$depth = 1;
		break;
	case 'siblingsAndParent':
		$showGrandparent = true;
		$depth = 2;
		break;
	case 'siblingsAndChildren':
		$showParent = true;
		$depth = 2;
		break;
  }


  //  find the selected parent item ID
  $cursor = 0;

	if ($selectedId != null) {
		foreach ($items as $item) { //Function was passed a Page ID	
		//We have the menu ID (Ex: 134), but we need to convert it to the actual page ID (Ex: 22)
		  if ($item->object_id == $selectedId) {
		    if ($showGrandparent) {
				//Find grandparent in the menu structure.
				foreach ($items as $itemAgain) {
					if ($itemAgain->ID == $item->menu_item_parent) {
						$cursor = $itemAgain->menu_item_parent;
						$ancestors = array($cursor, $item->menu_item_parent, $item->ID);
						break;
					}
				}
			}
			elseif ($showParent) {
				$cursor = $item->menu_item_parent;
				$ancestors = array($cursor, $item->ID);
			} else {
				$cursor = $item->ID;
				$ancestors = array($cursor);
			}
			break;
		  }
		}
	} 
	//Menu item not found in selected menu.
	if ($cursor === 0) {
		return array();
	}
  
  //  walk found items until all levels are exhausted
  $parents = array($cursor);
  $out = array();
  while (!empty($parents) && $depth) {
    $newparents = array();

    foreach ($items as $item) {
	  if ($showSelf && $item->ID == $cursor && in_array($item->ID, $parents)) {
		$out[] = $item;
		$item->menu_item_parent = 0;
	  }
      elseif (in_array($item->menu_item_parent, $parents)) {
        if ($item->menu_item_parent == $cursor && !$showSelf) {
          $item->menu_item_parent = 0;
		}
        $out[] = $item;
		//Items only get added if they are children of one of the ancestors. This prevents my siblings from showing their children. 
		//It also ignores the requirement in the case of a Level 2 Page. It wants to show the children of my children, etc.... 
		if ($menuType == 'level2' || in_array($item->ID,$ancestors)) {
			$newparents[] = $item->ID;
		}
      }
    }
    $parents = $newparents;
	$depth--;
  }

  return $out;
}
