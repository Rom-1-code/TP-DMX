<?php
//Romain FLEMAL
class Tcp{

    private $address = '192.168.64.227'; 
    private $port = 1234;
    private $socket;
    

    public function __construct(){
        // création du socket
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    public function SendColor($buf){


        // connexion au socket
        socket_connect($this->socket, $this->address, $this->port);
        //envoie du message
        socket_send($this->socket, $buf, 20, 0);
        $response = socket_read($this->socket,20, PHP_BINARY_READ);
        //fermeture du socket
        socket_close($this->socket);  

    }


}
?>
