<?php

class user{
    private $UserId, $UserEmail, $UserPassword, $UserNom, $UserPrenom, $UserLastResumeLocation;

	/*Création de fonction pour allez chercher les informations */
	
	//Id
    public function getUserId(){
        return $this->UserId;
    }
    public function setUserID($UserId){
        $this->UserId=$UserId;
    }

    //Email
    public function getUserEmail(){
        return $this->UserEmail;
    }
    public function setUserEmail($UserEmail){
        $this->UserEmail=$UserEmail;
    }

    //Password
    public function getUserPassword(){
        return $this->UserPassword;
    }
    public function setUserPassword($UserPassword){
        $this->UserPassword=$UserPassword;
    }

    //Nom
    public function getUserNom(){
        return $this->UserNom;
    }
    public function setUserNom($UserNom){
        $this->UserNom=$UserNom;
    }

    //Prenom
    public function getUserPrenom(){
        return $this->UserPrenom;
    }
    public function setUserPrenom($UserPrenom){
        $this->UserPrenom=$UserPrenom;
    }

    //UserLastResumeLocation
    public function getUserLastResumeLocation(){
        return $this->UserLastResumeLocation;
    }
    public function setUserLastResumeLocation($UserLastResumeLocation){
        $this->UserLastResumeLocation=$UserLastResumeLocation;
    }
    
	/*Compar les donnés saisi par l'utilisateur avec ceux sur la BDD*/
    public function Userlogin(){
        include "conn.php";
        $req=$bdd->prepare("SELECT * FROM users WHERE email=:UserEmail AND password=:UserPassword");
        $req->execute(array(
            'UserEmail'=>$this->getUserEmail(),
            'UserPassword'=>$this->getUserPassword()
            ));
        if($req->rowCount()==0){
            header("Location: ../login.php?error=1"); /*Erreur de connexion*/
            return false;
        }
        else{
            while($data=$req->fetch()){
                $this->setUserId($data['id']);
                $this->setUserEmail($data['email']);
                $this->setUserPassword($data['password']);
                $this->setUserNom($data['nom']);
                $this->setUserPrenom($data['prenom']);

                //requete pour récuperer le last resume location
                $req2=$bdd->prepare("SELECT * FROM users_resumes WHERE user_id=:UserId ORDER BY id DESC LIMIT 1");
                $req2->execute(array(
                    'UserId'=>$this->getUserId()
                ));
                if($req2->rowCount()==0){
                    $this->setUserLastResumeLocation("templates/narrow-jumbotron/index.html"); //template de base
                }
                else{
                    while($data2=$req2->fetch()){
                        $this->setUserLastResumeLocation($data2['resume_location']);
                    }
                }
            }
            header("Location: ../../index.php?connect=ok");      /*Bien identifié la session est créée*/
            return true;
        }
        
    }

	public function InsertUser(){
        include "conn.php";
        $req=$bdd->prepare("INSERT INTO users(email,password,nom,prenom) VALUES (:UserEmail,:UserPassword,:UserNom,:UserPrenom)");
        $req->execute(array(
			'UserEmail'=>$this->getUserEmail(),
			'UserPassword'=>$this->getUserPassword(),
			'UserNom'=>$this->getUserNom(),
			'UserPrenom'=>$this->getUserPrenom(),
        ));

        $req2=$bdd->prepare("SELECT id FROM users WHERE email=:UserEmail");
        $req2->execute(array(
            'UserEmail'=>$this->getUserEmail()
        ));
        if($req2->rowCount()==0){
            header("Location: ../login.php?error=InsertUser"); /*Erreur de connexion*/
            return false;
        }
        else{
            while($data=$req2->fetch()){
                $this->setUserId($data['id']);
            }
        }
    }
}
    



?>