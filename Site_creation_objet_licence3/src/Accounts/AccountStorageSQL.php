<?php

require_once("AccountStorage.php");
require_once("AccountBuilder.php");

class AccountStorageSQL implements AccountStorage {

    protected $bd;
    protected $accountTab;
    protected $tableau; 
    protected $prochId;

    // interaction avec la base de données compte

    public function __construct($bd){
        
        $this->bd=$bd;
        $this->prochId=1;

        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $res = $this->bd->query("SELECT id,login,password,statut,nom FROM accounts");
        $this->accountTab=array();
        $tableau = $res->fetchAll(PDO::FETCH_OBJ);
        foreach ($tableau as $ligne) {
            $this->prochId=$ligne->id+1;
            $this->accountTab[$ligne->id]=new Account($ligne->login,
                $ligne->password,$ligne->nom,$ligne->statut);
        }

    }

//récuperer un compte
    public function read($id){
        if (key_exists($id, $this->accountTab))
            return $this->accountTab[$id];
        else
            return null;
    }
//récuperer tout les comptes
    public function readAll(){
        return $this->accountTab;
    }

//Creation d'un compte dans la bd
    public function createAccount(Account $account){
        $rq = "INSERT INTO accounts  (login, password, statut, nom) VALUES (:login, :password, :statut, :nom)";
        $stmt = $this->bd->prepare($rq);
        $data = array(
            ':login'=> $account->getLogin(),
            ':password'=>$account->getPassword(),

            ':statut'=>$account->getStatut(),
            ':nom'=>$account->getName(),
        );
        $stmt->execute($data);
        /*var-dump($data) */
        $this->montresTab[$this->prochId]=$account;
        return $this->prochId;
    }

 // supprimé un account de la bd
    public function deleteAccount($id){
        $rq = "DELETE FROM  montres WHERE id = :id";
        $stmt = $this->bd->prepare($rq);
        $data = array(
            ':id' => $id
        );
        $stmt->execute($data);
    }

 // modifier un compte bd
    public function modifyAccount($login,Account $account){


        $data = array(
            ':password' => $account->getPassword(),
            ':nom' => $account->getName(),
            ':login' => $login,
        );

    

        $rq = "UPDATE accounts SET password=:password, nom=:nom  WHERE login =:login";


        $stmt = $this->bd->prepare($rq);
        $stmt->execute($data);

    }



// verfication des infos connexion
    public function checkAuth($login, $password){
        foreach ($this->accountTab as $compte){

            if($compte->getLogin() === $login && password_verify($password, $compte->getPassword())){
                $_SESSION['user'] = $compte;
                return true;
            }
        }
        return false;
    }
 
 
    public function getAccountsTab(){
        return $this->accountTab;
    }
}