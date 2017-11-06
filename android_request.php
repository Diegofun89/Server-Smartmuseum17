<?php
$conn=mysqli_connect("localhost","id3156624_g_accogli2","ingegneria","id3156624_dbsmartmuseum");
if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
//sostituire l'indirizzo con l url che si manda da android
$q=mysqli_query($conn,"SELECT post_title , ID FROM wp_posts  WHERE post_content LIKE '%".$_REQUEST['myurl']."%'");
if($q){
       while($e=mysqli_fetch_array($q)){
        $output[]=$e;
       }
       //recupero l' ID del post
       $numberpost =  $output[0]['ID'];
       //memorizza il titolo nell'array finale
       $results["title"] = $output[0]['post_title'];
       
    }
    
$q=mysqli_query($conn,"SELECT meta_key , meta_value FROM wp_postmeta  WHERE post_id = $numberpost ORDER BY meta_key");
if($q){
       while($e=mysqli_fetch_array($q)){
        $output2[]=$e;
       }
       foreach($output2 as $value){
           if($value[0]!= "_edit_last" && $value[0]!="_edit_lock" && $value[0]!="_pods_immagine" && $value[0]!="_pods_video"){
                  if($value[0]=="immagine" || $value[0]=="video"){
                    $q=mysqli_query($conn,"SELECT guid FROM wp_posts  WHERE ID = $value[1] ");
                    if($q){
                          while($e=mysqli_fetch_array($q)){
                          $output3[] = $e;
                        }
                       $results[$value[0]]=$output3[0][0];
                       unset($output3);
                       $output3=array();
                    }
                  }else
                  $results[$value[0]]=$value[1];
                } 
       }
       print(json_encode($results));
    }
mysqli_close($conn);

?>

