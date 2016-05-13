<?php 

function Audigree_Get_Siblings_By_Parent_IDs($id1,$id2,$self_id){
  global $wpdb; /*this is the object that lets us run queries*/
  
  $id1=intval($id1);
  $id2=intval($id2);
  if(
    ($id1==0)&&
    ($id2==0)
  ){
    //echo 'Person Not Found';
    return;
  }
    $sql="
      SELECT person_id
      FROM audigree_person 
      WHERE 
      person_id NOT LIKE ".$self_id." AND
      (
        mother_id = '".$id1."' OR
        father_id = '".$id1."' OR
        mother_id = '".$id2."' OR
        father_id = '".$id2."'
      )
    ";
    $siblings = $wpdb->get_results($sql, OBJECT);
    return $siblings;
}

?>
