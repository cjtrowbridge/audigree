<?php
/*
* Plugin Name: Audigree
* Description: Create formatted pedigrees automatically
* Version: 1.0
* Author: CJ Trowbridge
* Author URI: https://github.com/audigree
*/
function Audigree_Get_Siblings_By_Parent_IDs($id1,$id2){
  global $wpdb; /*this is the object that lets us run queries*/
  
  $id1=intval($id1);
  $id2=intval($id2);
  if(
    ($id1==0)||
    ($id2==0)
  ){
    echo 'Person Not Found';
    return;
  }
    $sql="
      SELECT person_id
      FROM audigree_person 
      WHERE 
        mother_id = '".$id1."' OR
        father_id = '".$id1."' OR
        mother_id = '".$id2."' OR
        father_id = '".$id2."'
    ";
    $siblings = $wpdb->get_results($sql, OBJECT);
    return $siblings;
}
function Audigree_Get_Children_By_Parent_ID($id){
  global $wpdb; /*this is the object that lets us run queries*/
  
  $id=intval($id);
  if(
    ($id==0)
  ){
    echo 'Person Not Found';
    return;
  }
    $sql="
      SELECT person_id
      FROM audigree_person 
      WHERE 
        mother_id = '".$id."' OR
        father_id = '".$id."'
    ";
    $children = $wpdb->get_results($sql, OBJECT);
    return $children;
}
function Audigree_Get_Person($query){
  global $wpdb; /*this is the object that lets us run queries*/
  
  $person_id=intval($query);
  if($person_id==0){
    /*this was probably text passed instead of an id*/
    $this_person_name_parts=explode('-',$query);
    if(count($this_person_name_parts)<1){
      echo 'Person Not Found';
      return;
    }
    $sql="
      SELECT 
        person_id,
        name_prefix,
        name_first,
        name_middle,
        name_maiden,
        name_last,
        ifnull(date_of_birth,'Unknown') as date_of_birth,
        ifnull(date_of_death,'Unknown') as date_of_death,
        father_id,
        mother_id,
        ifnull(place_of_birth,'Unknown') as place_of_birth,
        ifnull(place_of_death,'Unknown') as place_of_death,
        /*biography,*/
        image_uri
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
   $sql.="
      LIMIT 1
    ";
    $this_person = $wpdb->get_results($sql, OBJECT);
    return $this_person[0];
  }else{
    //get person by id
    if($person_id==0){
      echo 'Invalid Person ID';
      return;
    }
    $sql="
      SELECT 
        person_id,
        name_prefix,
        name_first,
        name_middle,
        name_maiden,
        name_last,
        ifnull(date_of_birth,'Unknown') as date_of_birth,
        ifnull(date_of_death,'Unknown') as date_of_death,
        father_id,
        mother_id,
        ifnull(place_of_birth,'Unknown') as place_of_birth,
        ifnull(place_of_death,'Unknown') as place_of_death,
        /*biography,*/
        image_uri
      FROM audigree_person 
      WHERE 
        person_id = '".$person_id."'
      LIMIT 1
    ";
    $this_person = $wpdb->get_results($sql, OBJECT);
    return $this_person[0];
  }
}

function pd($var){
  echo '<pre>';
  var_dump($var);
  echo '</pre>';
}

function Audigree_Get_Slug_By_ID($id){
  $this_person=Audigree_Get_Person($id);
  $this_person_slug= $this_person->name_first;
  
  foreach(array(
      $this_person->name_middle,
      $this_person->name_last,
      $this_person->name_suffix
  ) as $name_part){
    if(!($name_part=='')){
      $this_person_slug.='-'.$name_part;
    }
  }
  return strtolower($this_person_slug);
}

function Audigree_Get_Name_By_ID($id){
  $this_person=Audigree_Get_Person($id);
  $this_person_name= $this_person->name_first;
  
  foreach(array(
      $this_person->name_middle,
      $this_person->name_last,
      $this_person->name_suffix
  ) as $name_part){
    if(!($name_part=='')){
      $this_person_name.=' '.$name_part;
    }
  }
  return $this_person_name;
}

function audigree_automatic_pedigree(){
  global $wpdb; /*this is the object that lets us run queries*/
  
  $path=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $number_of_slashes_in_request=substr_count($path, '/');
  
  if($number_of_slashes_in_request<=2){
    //this is a single argument path, and probably a page for a person
    $page_name=str_replace('/','',$path);
    $page_name=strtolower($page_name);
    $this_person=Audigree_Get_Person($page_name);
    pd($this_person);
    ?>
    <p><?php echo $this_person->date_of_birth.' ('.$this_person->place_of_birth.')'; ?> - <?php echo $this_person->date_of_birth.' ('.$this_person->place_of_death.')'; ?></p>
    <br>
    <?php
    /*get father name and slug*/
    $father_name=Audigree_Get_Name_By_ID($this_person->father_id);
    $father_slug=Audigree_Get_Slug_By_ID($this_person->father_id);
    ?>
    <p><b>Father: </b> <a href="/<?php echo $father_slug; ?>"><?php echo $father_name; ?></a></p>
    <?php 
    /*get mother name and slug*/
    $mother_name=Audigree_Get_Name_By_ID($this_person->mother_id);
    $mother_slug=Audigree_Get_Slug_By_ID($this_person->mother_id);
    ?>
    <p><b>Mother: </b> <a href="/<?php echo $mother_slug; ?>"><?php echo $mother_name; ?></a></p>
    
    <b>Siblings:</b>
    <ul>
      <?php 
      
      $siblings=Audigree_Get_Siblings_By_Parent_IDs($this_person->mother_id, $this_person->father_id);
      if(count($siblings)==0){
        echo '<li>None Found</li>';
      }
      foreach($siblings as $sibling){
        $sibling_name=Audigree_Get_Name_By_ID($sibling->person_id);
        $sibling_slug=Audigree_Get_Slug_By_ID($sibling->person_id);
        ?>
        <li><a href="/<?php echo $sibling_slug; ?>"><?php echo $sibling_name; ?></a></li>
        <?php
      }
      
      ?>
    </ul>
    
    <b>Children:</b>
    <ul>
      <?php 
      
      $children=Audigree_Get_Children_By_Parent_ID($this_person->person_id);
      if(count($children)==0){
        echo '<li>None Found</li>';
      }
      foreach($children as $child){
        $child_name=Audigree_Get_Name_By_ID($child->person_id);
        $child_slug=Audigree_Get_Slug_By_ID($child->person_id);
        ?>
        <li><a href="/<?php echo $child_slug; ?>"><?php echo $child_name; ?></a></li>
        <?php
      }
      
      ?>
    </ul>
  <?php
  }
  
  
}

add_shortcode('audigree_automatic_pedigree', 'audigree_automatic_pedigree');

?>
