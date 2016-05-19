<?php 

function Audigree_Get_Person($query){
  global $wpdb; /*this is the object that lets us run queries*/
  
  $person_id=intval($query);
  if($person_id==0){
    /*this was probably text passed instead of an id*/
    $this_person_name_parts=explode('-',$query);
    if(count($this_person_name_parts)<1){
      //echo 'Person Not Found';
      return;
    }
    $sql="
      SELECT 
        person_id,
        name_prefix,
        name_first,
        name_middle,
        name_last,
        name_suffix,
        date_of_birth,
        date_of_death,
        father_id,
        mother_id,
        place_of_birth,
        place_of_death,
        biography,
        image_uri
      FROM audigree_person 
      WHERE 
        name_first LIKE '".$wpdb->esc_like($this_person_name_parts[0])."'
    ";
    if(isset($this_person_name_parts[1])){
      $sql.="
        AND (
          name_middle LIKE '".$wpdb->esc_like($this_person_name_parts[1])."' OR
          name_last LIKE '".$wpdb->esc_like($this_person_name_parts[1])."'
        )
      ";
    }
    if(isset($this_person_name_parts[2])){
      $sql.="
        AND 
        (
          name_last LIKE '".$wpdb->esc_like($this_person_name_parts[2])."'
        )
      ";
    }
    if(isset($this_person_name_parts[3])){
      $sql.="
        AND name_suffix LIKE '".$wpdb->esc_like($this_person_name_parts[3])."'
      ";
    }
   $sql.="
      LIMIT 1
    ";
    if(is_admin()) {
      ?>
      <!--
      <?php echo $sql; ?>
      -->
      <?php
    }
    $this_person = $wpdb->get_results($sql, OBJECT);
    return $this_person[0];
  }else{
    //get person by id
    if($person_id==0){
      //echo 'Invalid Person ID';
      return;
    }
    $sql="
      SELECT 
        person_id,
        name_prefix,
        name_first,
        name_middle,
        name_last,
        name_suffix,
        date_of_birth,
        date_of_death,
        father_id,
        mother_id,
        place_of_birth,
        place_of_death,
        /*biography,*/
        image_uri
      FROM audigree_person 
      WHERE 
        person_id = '".$person_id."'
      LIMIT 1
    ";
    if(is_admin()) {
      ?>
      <!--
      <?php echo $sql; ?>
      -->
      <?php
    }
    $this_person = $wpdb->get_results($sql, OBJECT);
    return $this_person[0];
  }
}

?>
