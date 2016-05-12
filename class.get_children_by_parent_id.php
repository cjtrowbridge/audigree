<?php 

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

?>
