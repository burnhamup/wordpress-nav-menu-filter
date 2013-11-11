=== WordPress wp nav menu Filter ===
Contributors: travis.hoglund
	Chris Burnham chris@burnhamup.com
Donate link: http://travishoglund.com/donate/
Tags: wp_nav_menu, sub-menu, menu, nav, wp nav menu, wordpress submenu, wordpress filter submenu, submenu, wordpress menus, filter, wordpress nav filter, nav filter, submenu filter
Requires at least: 3.0.0
Tested up to: 3.3.1
Stable tag: trunk

Adds the ability to pass an argument (pageID or page name) to show a filtered submenu with wp_nav_menu().

== Description ==

If you have built custom WordPress themes, you have probably ran into needing to display navigation on subpages.  Rather than try to manage several navigation menus, or try to output menus with wp_list_pages(), why not manage everything from a single menu, and just pass different parameters to show what you want?  Makes sense to me...

To use it, simply add a 'submenu' parameter to the arguments of wp_nav_menu, like so:

    wp_nav_menu(array(
      'menu' => 'header',
      'submenu' => 'Solutions' //Using parameter of Page Name
    ));
	
	----  OR  ----
	
	wp_nav_menu(array(
      'menu' => 'header',
      'submenu' => '46' //Using parameter of Page ID (!important - Passing ID in STRING FORMAT)
    ));
	
== Menu Types ==
By default, this plugin will build a menu containing all of the children of the page id that was passed in.
Use the 'menutype' parameter to vary this.

	'children'
		Example
		SELF
			Child 1
			Child 2
	
	'siblings'
		Example:
		Parent
			Sibling 1
			Self
			Sibling 2
			
	'siblingsAndParent'
		Example
		Grandparent
			Parent Sibling 1
			Parent
				Sibling 1
				Self
				Sibling 2
			Parent Sibling 2
	
	'siblingsAndChildren'
		Example:
		Parent
			Sibling 1
			Self
				Child 1
				Child 2
				Child 3
			Sibling 2
	
wp_nav_menu Example:
wp_nav_menu(array(
	'menu'=> 'header,
	'submenu' => '46',
	'menutype' => 'children'
));

== Installation ==

Install this plugin in the normal way:

1. Place it in your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the `wp_nav_menu` function with a `submenu` parameter in your templates

    wp_nav_menu(array(
      'menu' => 'header',
      'submenu' => 'Solutions'
    ));
	
	wp_nav_menu(array(
      'menu' => 'header',
      'submenu' => '46'
    )); //Important - You MUST pass the ID as a STRING!

== Frequently Asked Questions ==


= What will happen if I pass an invalid page ID? =

It defaults to the main menu, the same as if the plugin wasn't activated/installed.

= How do I select a menu more than one level deep? =

You can go multiple levels deep by putting slashes in:

    wp_nav_menu(array(
      'menu' => 'header',
      'submenu' => 'Solutions/Company Solutions'
    ));

Or you can also use an Array:

    wp_nav_menu(array(
      'menu' =>
      'submenu' => array('Solutions', 'Company Solutions')
    ));

= Can I use a page's slug instead of the page title? =

Currently, I see no reason to use a page slug.  This functionality can be added at a later date if I see it to be necessary.

= Does the title need to match exactly? =

This plugin should compare the page title with what you entered and be slightly forgiving (Capital letters, etc), but you should strive to enter it exactly.

  Example for inside template files:
  
	wp_reset_query();  //Good practice to clear any of your custom loops before this code

    wp_nav_menu(array(
      'menu' => 'header',
      'submenu' => ''.$post->ID.'' //This will always provide an exact match to the current loop page
    ));
	
== Screenshots ==

1. Display just the highlighted part of an entire navigation menu based on wp_nav_menu.

== Changelog ==

= 1.0 =
* Version 1.0 Released.

== Upgrade Notice ==

= 1.0 =
Future versions to come.

== A brief Markdown Example ==



What can it do for me?

* Display submenus controlled by one simple navigation menu created through the WordPress Interface

Most Common applications:

1. Display submenu on internal pages
2. Display submenu in footer blocks

**Inside template files:**

*By Page Name*
`<?php wp_nav_menu(array(
      'menu' => 'header',
      'submenu' => 'Solutions'
    )); ?>`
	
*By Current Page*
	
`<?php wp_nav_menu(array(
      'menu' => 'header',
      'submenu' => ''.$post->ID.''
    )); ?>`
	
	
*By Specific Page ID*

`<?php wp_nav_menu(array(
      'menu' => 'header',
      'submenu' => '46'
    )); ?>`
