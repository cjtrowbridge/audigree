<?php
/*
* Plugin Name: Audigree
* Description: Create formatted pedigrees automatically
* Version: 1.0
* Author: CJ Trowbridge
* Author URI: https://github.com/audigree
*/

function audigree_automatic_pedigree(){
  ?>
  <b>Siblings:</b>
  <ul>
    <li>sibling a</li>
    <li>sibling b</li>
  </ul>
  <?php
}

add_shortcode('audigree_automatic_pedigree', 'audigree_automatic_pedigree');

?>
