<?php session_start(); ?>
<?php require("Class/user.php"); ?>
<?php include('Class/Tcp.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DMX-Client-Serveur</title>
    <!-- icons -->
    <link href="img/apple-touch-icon.png" rel="icon">
    <link href="img/apple-touch-icon.png" rel="apple-touch-icon">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="css/btn.css" rel="stylesheet">
    <!--===============================================================================================-->
</head>

<body>
    <?php   //si la session n'est pas ouverte on affcihe le formulaire de connexion
    if (!isset($_SESSION['identifiant'])) { 
    ?>
        <div class="limiter">
            <div class="container-login100" style="background-image: url('images/img-01.jpg');">
                <div class="wrap-login100 p-t-190 p-b-30">
                    <form class="login100-form validate-form" action="index.php" method="post">


                        <span class="login100-form-title p-t-20 p-b-45">
                            Bienvenue
                        </span>

                        <div class="wrap-input100 validate-input m-b-10" data-validate="Veuillez renseigner votre identifiant">
                            <input class="input100" type="text" name="identifiant" placeholder="Identifiant" value="Marco">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>

                        <div class="wrap-input100 validate-input m-b-10" data-validate="Veuillez renseigner votre mot de passe">
                            <input class="input100" type="password" name="mdp" placeholder="Mot de passe" value="0000">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-lock"></i>
                            </span>
                        </div>
                        <?php // Tout les champs du formulaire
                        if (isset($_POST['identifiant']) && isset($_POST['mdp'])) {
                            //Le mot de passe est correct, on crée l'objet user
                            $coUser = new user(); 
                            // Méthode de connexion dans class user
                            $base = $coUser->Connexionbdd(); 
                            //  Méthode de autorisation dans class user 
                            $coUser->ConnexionUser($_POST['identifiant'], $_POST['mdp'], $base);  
                        }

                        ?>  <!-- Bouton de pour envoyer la requête de connexion -->
                        <div class="container-login100-form-btn p-t-10"> 
                            <button class="login100-form-btn h1">
                                Connexion
                            </button>
                        </div>


                        <div class="text-center w-full">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php // si la session est connecté on affiche la page du site
    } else { ?>
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link active"><?php echo "Bonjour " . $_SESSION['identifiant']; ?></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="deconnexion.php">Déconnexion</a> <!-- Bouton de déconnexion -->
            </li>
        </ul>

        <div class="etat">
            <form action="" method="POST">
                <input type="HIDDEN" name="ButtonOn" />
                <input type="submit" class="btn btn-secondary col-6 col-md 4" value="Allumer" />
            </form>
            <form action="" method="POST">
                <input type="HIDDEN" name="ButtonOff" />
                <input type="submit" class="btn btn-secondary col-6 col-md 4" value="Eteindre" />
            </form>
        </div>
        <!-- Slider pour varié l -->
        <div class="range-slider">
            <input class="range-slider__range slider" id="myRange" type="range" value="0" min="0" max="255">
            <span style="color: red;" id="demo" class="range-slider__value ">0</span>
        </div>

        <div class="range-slider">
            <input class="range-slider__range slider1" id="myRange1" type="range" value="0" min="0" max="255">
            <span style="color: green;" id="demo1" class="range-slider__value">0</span>
        </div>

        <div class="range-slider">
            <input class="range-slider__range slider2" id="myRange2" type="range" value="0" min="0" max="255">
            <span style="color: #00FFFF" id="demo2" class="range-slider__value">0</span>
        </div>

        <div class="range-slider">
            <input class="range-slider__range slider3" id="myRange3" type="range" value="0" min="0" max="255">
            <span class="range-slider__value" id="demo3">0</span>
        </div>
       <!-- Bouton pour envoyer les message de couleur au serveur DMX -->
            <div>
                <div class="color1">
                    <form action="" method="post">
                        <input type="HIDDEN" name="rougeColor" />
                        <input type="submit" class="btn btn-danger col-6 col-md 4" value="Rouge" />
                    </form>

                    <form action="" method="post">
                        <input type="HIDDEN" name="greenColor" />
                        <input type="submit" class="btn btn-success col-6 col-md 4" value="Vert" />
                    </form>

                    <form action="" method="post">
                        <input type="HIDDEN" name="blueColor" />
                        <input type="submit" class="btn btn-primary col-6 col-md 4" value="Bleu" />
                    </form>

                    <form action="" method="post">
                        <input type="HIDDEN" name="whiteColor" />
                        <input type="submit" class="btn btn-light col-6 col-md 4" value="Blanc" />
                    </form>
                </div>

            </div>
        
    <?php
    }
    

    $ObjetTCP1= new TCP();
    
    // connexion au port
    
    if (isset($_POST['ButtonOn']))
    {
        $ObjetTCP1->SendColor('a;');
    }
    if (isset($_POST['ButtonOff']))
    {
        $ObjetTCP1->SendColor('e;');
    }
    if (isset($_POST['rougeColor']))
    {
        $ObjetTCP1->SendColor('r;');
    }
    if (isset($_POST['blueColor']))
    {
        $ObjetTCP1->SendColor('b;');
    }
    if (isset($_POST['greenColor']))
    {
        $ObjetTCP1->SendColor('v;');
    }
    if (isset($_POST['whiteColor']))
    {
        $ObjetTCP1->SendColor('w;');
    }
    ?>

    <script> // Script pour afficher la variation des slider

        var slider = document.getElementById("myRange");
        var slider1 = document.getElementById("myRange1");
        var slider2 = document.getElementById("myRange2");
        var slider3 = document.getElementById("myRange3");
        var output = document.getElementById("demo");
        var output1 = document.getElementById("demo1");
        var output2= document.getElementById("demo2");
        var output3 = document.getElementById("demo3");
        // afficher la valeur
        output.innerHTML = slider.value; 
        output1.innerHTML = slider1.value;
        output2.innerHTML = slider2.value;
        output3.innerHTML = slider3.value;

        // mise a jour en temps réel de la valeur
        slider.oninput = function() {
            output.innerHTML = this.value;
            sendColor();
        }
        slider1.oninput = function() {
            output1.innerHTML = this.value;
            sendColor();
        }
        slider2.oninput = function() {
            output2.innerHTML = this.value;
            sendColor();
        }
        slider3.oninput = function() {
            output3.innerHTML = this.value;
            sendColor();
        }

        function sendColor(){

            fetch("traitementphp.php?val="+slider.value+";"+slider1.value+";"+slider2.value+";"+slider3.value+";")
            .then(response => response.json())
            .then(response => console.log(response))
            .catch(error => console.log(error));

        }

    </script>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>
</body>

</html>