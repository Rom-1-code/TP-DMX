<?php
class user
{

    //Propiétés
    private $_idUser;
    private $_identifiant;
    private $_mdp;
    
    
    //Methodes
    public function getIdUser()
    {
        return $this->_idUser;
    }
    public function getIdentifiant() 
    {
        return $this->_identifiant;
    }
    public function getMdp() 
    {
        return $this->_mdp;
    }
    // Constructeur
    public function construct($id_user, $identifiant, $mdp) 
    {
        $this->_idUser = $id_user;
        $this->_identifiant = $identifiant;
        $this->_mdp = $mdp;
    }
    // Connexion avec la base de données 
    public function Connexionbdd() 
    {
        try {
            $bdd = new PDO('mysql:host=192.168.64.104; dbname=TPSPOTS; charset=utf8', 'root', 'root');
        } catch (Exception $erreur) {
            echo 'Erreur : ' . $erreur->getMessage();
        }
        return $bdd;
    }

    public function ConnexionUser($identifiant, $mdp, $bdd) //Romain FLEMAL
    {
        // Vérifie si l'identifiant et le mdp sont les même que dans la bdd
        $requser = $bdd->query('SELECT * FROM user WHERE "' . $identifiant . '"=`identifiant` && "' . $mdp . '"=`mdp'); 
        // mise dans un tableau
        $requser->execute(array($identifiant, $mdp));
        $userexist = $requser->rowCount(); 
        if ($userexist == 1) {
            $userinfo = $requser->fetch();
            // Verification avec la base de données
            $_SESSION['identifiant'] = $userinfo['identifiant'];  
            
            ?>
                <meta http-equiv="refresh" content="0.01;URL=index.php"> 
            <?php
        } else {

            echo "Identifiant ou mot de passe incorrect !";    
        }
    
}
    public function InscriptionUser(){






        
    }

}
?>