<?php
$host = 'localhost';
$dbname = 'id3156624_dbsmartmuseum';
$username = 'id3156624_g_accogli2';
$psw = 'ingegneria';
$dsn = "mysql:host=$host;dbname=$dbname";
try{
    $pdo = new PDO($dsn, $username, $psw);
}catch(PDOException $e) {
  // notifica in caso di errorre
  echo "Attenzione: $e->getMessage()";
}
//$url = 'http://smartmuseum17.000webhostapp.com/opera/il-david/';
$url = $_POST['myurl'];
$sh = $pdo->prepare('SELECT post_title , ID FROM wp_posts WHERE post_content LIKE "%":url"%"');
$sh->bindParam(':url',$url);
$sh->execute();
$output = $sh->fetchAll();
$numberpost =  $output[0]['ID'];
//memorizza il titolo nell'array finale
$results['title'] = $output[0]['post_title'];
$sh = $pdo->prepare("SELECT meta_key , meta_value FROM wp_postmeta  WHERE post_id = $numberpost ORDER BY meta_key");
$sh->execute();
$output2 = $sh->fetchAll();
       foreach($output2 as $value){
           if(strcmp($value[0], '_edit_last')!==0 && strcmp($value[0],'_edit_lock')!==0 && strcmp($value[0],'_pods_immagine')!==0 && strcmp($value[0],'_pods_video')!==0){
                  if(strcmp($value[0],'immagine')===0) {
                      $idimg=$value[1];
                  }
                  else if(strcmp($value[0],'video')===0){
                      $idvideo=$value[1];
                  }else
                  $results[$value[0]]=$value[1];
           }
        }
//inserisco immagini e video nei risultati
$sh = $pdo->prepare("SELECT guid FROM wp_posts  WHERE ID = $idimg ");
                    $sh->execute();
                    $output3 = $sh->fetchAll();
                    $results['immagine']=$output3[0][0];
                    unset($output3);
                    $output3=array();
$sh = $pdo->prepare("SELECT guid FROM wp_posts  WHERE ID = $idvideo ");
                    $sh->execute();
                    $output3 = $sh->fetchAll();
                    $results['video']=$output3[0][0];
                    unset($output3);
                    $output3=array();
                    
echo json_encode($results);

