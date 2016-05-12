<?php
/*
* Plugin Name: Audigree
* Description: Create formatted pedigrees automatically
* Version: 1.0
* Author: CJ Trowbridge
* Author URI: https://github.com/audigree
*/

function audigree_automatic_pedigree(){
  global $wpdb; /*this is the object that lets us run queries*/
  
  $path=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $number_of_slashes_in_request=substr_count($path, '/');
  
  if($number_of_slashes_in_request<=2){
    //this is a single argument path, and probably a page for a person
    $page_name=str_replace('/','',$path);
    $page_name=strtolower($page_name);
    $this_person_name_parts=explode('-',$page_name);
    if(count($this_person_name_parts)<1){
      echo 'Person Not Found';
      return;
    }
    
    /*get info about this person*/
    $sql="
      SELECT * 
      FROM audigree_person 
      WHERE 
        name_first LIKE '".$wpdb->esc_like($this_person_name_parts[0])."'
    ";
    if(isset($this_person_name_parts[1])){
      $sql.="
        AND name_middle LIKE '".$wpdb->esc_like($this_person_name_parts[1])."'
      ";
    }
    if(isset($this_person_name_parts[2])){
      $sql.="
        AND 
        (
          name_last LIKE '".$wpdb->esc_like($this_person_name_parts[2])."' OR
          name_maiden LIKE '".$wpdb->esc_like($this_person_name_parts[2])."'
        )
      ";
    }
    if(isset($this_person_name_parts[3])){
      $sql.="
        AND name_suffix LIKE '".$wpdb->esc_like($this_person_name_parts[3])."'
      ";
    }
    $this_person = $wpdb->get_results($sql, OBJECT);
    var_dump($this_person);
    
    
    
    ?>
  
      <p><b>Current Page:</b> <?php echo $page_name; ?></p>
      <p><b>Father: </b> <a href="#"><?php /*$results = $wpdb->get_results( 'SELECT * FROM audigree_person WHERE person_id=3', OBJECT );*/ ?></a></p>
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
