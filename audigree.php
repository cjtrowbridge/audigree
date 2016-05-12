<?php
/*
* Plugin Name: Audigree
* Description: Create formatted pedigrees automatically
* Version: 1.0
* Author: CJ Trowbridge
* Author URI: https://github.com/audigree
*/

include_once('class.get_siblings_by_parent_id.php');
include_once('class.get_children_by_parent_id.php');
include_once('class.get_person.php');
include_once('class.pd.php');
include_once('class.get_name_by_id.php');
include_once('class.get_slug_by_id.php');
include_once('class.automatic_pedigree.php');

add_shortcode('audigree_automatic_pedigree', 'audigree_automatic_pedigree');

?>
