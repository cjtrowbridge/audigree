<?php 

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

?>
