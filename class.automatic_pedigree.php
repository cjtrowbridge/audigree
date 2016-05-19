<?php 

function audigree_automatic_pedigree(){
  global $wpdb; /*this is the object that lets us run queries*/
  
  $path=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $number_of_slashes_in_request=substr_count($path, '/');
  
  if($number_of_slashes_in_request<=2){
    //this is a single argument path, and probably a page for a person
    $page_name=str_replace('/','',$path);
    $page_name=strtolower($page_name);
    $this_person=Audigree_Get_Person($page_name);
    
    if(!($this_person->image_uri=='')){
      echo '<a align="right" width="300" href="'.$this_person->image_uri.'" target="_blank"><img align="right" width="300" src="'.$this_person->image_uri.'"></a>';
    }
    
    
    ?><style>
      .audigree li{
        list-style-position: inside;
      }
    </style>
    <div class="audigree">
      <p>
      <?php 
      
      if(current_user_can( 'manage_options' )){
        ?>
         <!--
         
         You are an admin.
          
          <?php var_dump($this_person); ?>
          
          -->
        <?php
      }else{
        ?>
        <!--
        
        You are not an admin.
        
        -->
        <?php
      }
        
        echo '<b>Born:</b> ';
        if($this_person->date_of_birth==''){echo '<unknown>Unknown</unknown>';}else{echo $this_person->date_of_birth;}
        if($this_person->place_of_birth==''){echo ' <unknown>(Unknown Place)</unknown>';}else{echo ' (<a href="https://www.google.com/maps/place/'.urlencode($this_person->place_of_birth).'" target="_blank">'.$this_person->place_of_birth.'</a>)';}
        
        if(!($this_person->date_of_death=='')){
        
          ?> - <b>Died:</b> <?php 
          
          if($this_person->date_of_death==''){echo '<unknown>Unknown</unknown>';}else{echo $this_person->date_of_death;}
          if($this_person->place_of_death==''){echo ' <unknown>(Unknown Place)</unknown>';}else{echo ' (<a href="https://www.google.com/maps/place/'.urlencode($this_person->place_of_death).'" target="_blank">'.$this_person->place_of_death.'</a>)';}
          
        }
      ?></p>
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
        
        $siblings=Audigree_Get_Siblings_By_Parent_IDs($this_person->mother_id, $this_person->father_id,$this_person->person_id);
        if(count($siblings)==0){
          echo '<li>None Found</li>';
        }else{
          foreach($siblings as $sibling){
            $sibling_name=Audigree_Get_Name_By_ID($sibling->person_id);
            $sibling_slug=Audigree_Get_Slug_By_ID($sibling->person_id);
            ?>
            <li><a href="/<?php echo $sibling_slug; ?>"><?php echo $sibling_name; ?></a></li>
            <?php
          }
        }
        
        ?>
      </ul>
      
      <?php 
      $children=Audigree_Get_Children_By_Parent_ID($this_person->person_id);
      if(count($children)>0){
      ?>
      
      <b>Children:</b>
      <ul>
        <?php 
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
      if(!(trim($this_person->biography)=='')){
        $nl2p = str_replace("\n", "</p>\n<p>", '<p>'.$this_person->biography.'</p>');
        echo '<b>Biography and Notes:</b><br>';
        echo $nl2p;
      }
    ?>
    </div>
    <?php
  }
}

?>
