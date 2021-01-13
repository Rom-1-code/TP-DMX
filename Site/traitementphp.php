<?php
try{

    include('Class/Tcp.php'); 
    $ObjetTCP1= new TCP();
    $ObjetTCP1->SendColor($_GET["val"]);
    echo  json_encode("1");

}catch(Exception $e){

    echo json_encode("0");
}

?>