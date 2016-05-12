<?php
/*
* Plugin Name: Audigree
* Description: Create formatted pedigrees automatically
* Version: 1.0
* Author: CJ Trowbridge
* Author URI: https://github.com/audigree
*/

function audigree_automatic_pedigree(){
  
  $path=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $number_of_slashes_in_request=substr_count($path, '/');
  
  if($number_of_slashes_in_request<=2){
    //this is a single argument path, and probably a page for a person
    $page_name=str_replace('/','',$path);
    $page_name=strtolower($page_name);
    ?>
  
      <p><b>Current Page:</b> <?php echo $page_name; ?></p>
      
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
  
  
}

add_shortcode('audigree_automatic_pedigree', 'audigree_automatic_pedigree');

?>
