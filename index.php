<!-- CONNEXION A LA BDD -->
<?php



function bdd(){
    try{
        $bdd = new PDO("mysql:host=localhost;dbname=abclight", "root", "");
    }
    catch(PDOException $error){
        echo "Connexion impossible: " . $error ->getMessage();
    }
    return $bdd;
}


echo 'bonjour';


