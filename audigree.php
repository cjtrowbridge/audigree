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
  
  <p><b>Current Page:</b> <?php echo $pagename; ?></p>
  
  <p><b>Father: </b> <a href="#">Father's Name</a></p>
  <p><b>Mother: </b> <a href="#">Mother's Name</a></p>
  
  <b>Siblings:</b>
  <ul>
    <li>sibling a</li>
    <li>sibling b</li>
  </ul>
  
  <b>Children:</b>
  <ul>
    <li>child a</li>
    <li>child b</li>
  </ul>
  
  <?php
}

add_shortcode('audigree_automatic_pedigree', 'audigree_automatic_pedigree');

?>
