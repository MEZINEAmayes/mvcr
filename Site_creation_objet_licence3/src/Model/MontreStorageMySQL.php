<?php

require_once("MontreStorage.php");
require_once("MontreBuilder.php");

class MontreStorageMySQL implements MontreStorage{


    protected $bd;
    protected $tableau;
    protected $montresTab;
    protected $mesMontresTab;
    protected $prochId;

    public function __construct($bd){
        $this->prochId=1;
        $this->bd=$bd;
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $res = $this->bd->query("SELECT id,montre_ref,date_ajout, date_modification,model,genre,pays,annee,prix FROM  montres");
        $this->montresTab=array();
        $tableau = $res->fetchAll(PDO::FETCH_OBJ);
        foreach ($tableau as $ligne) {
            $this->montresTab[$ligne->id]=new Montre($ligne->montre_ref,
                $ligne->model,$ligne->genre,$ligne->pays,$ligne->annee,$ligne->prix,$ligne->date_ajout,$ligne->date_modification);
            $this->prochId=$ligne->id+1;

        }
    }




    public function read($id){
        if (key_exists($id, $this->montresTab))
            return $this->montresTab[$id];
        else
            return null;
    }

    public function readAll(){
        return $this->montresTab;
    }
        //Ajouter la montre crée à la base de données
    public function create(Montre $montre){
        $rq = "INSERT INTO montres (montre_ref, date_ajout, date_modification,model, genre,pays, annee, prix) VALUES (:montre_ref, :date_ajout,:date_modification ,:model, :genre, :pays, :annee, :prix)";
        //$this->controller->UploadImg();
        $stmt = $this->bd->prepare($rq);
        $data = array(
            ':montre_ref' => $montre->getMontreref(),
            ':date_ajout' => $montre->getDateAjout()->format('Y-m-d H:i:s'),
            ':date_modification' => $montre->getModifDate()->format('Y-m-d H:i:s'),
            ':model' => $montre->getModel(),
            ':genre' => $montre->getGenre(),
            ':pays' => $montre->getPays(),
            ':annee' => $montre->getAnnee(),
            ':prix' => $montre->getPrix(),
        );
        $stmt->execute($data);

        $this->montresTab[$this->prochId]=$montre;
        return $this->prochId;


    }

    //lire les montres de la bd
    public function readMesMontres(Account $account){


        $rq = "SELECT montres.id,montre_ref,date_ajout, date_modification,model,genre,pays,annee,prix  FROM montres,accounts WHERE pays=:pays";
        $stmt = $this->bd->prepare($rq);
        $data = array(
            ':pays' => $account->getName(),
        );
        $stmt->execute($data);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($result as $ligne) {
            $this->mesMontresTab[$ligne->id]=new Montre($ligne->montre_ref,
                $ligne->model,$ligne->genre,$ligne->pays,$ligne->annee,$ligne->prix,$ligne->date_ajout,$ligne->date_modification);
        }
        return $this->mesMontresTab;


    }

    public function deleteM($id){
        $rq = "DELETE FROM montres WHERE id = :id";
        $stmt = $this->bd->prepare($rq);
        $data = array(
            ':id' => $id
        );
        $stmt->execute($data);
    }

//mondifier une montre
    public function modifyM($id, Montre $montre){
        if (key_exists($id, $this->montresTab)) {
            $this->montresTab[$id] = $montre;
            $rq = "UPDATE montres SET montre_ref =:montre_ref, date_ajout=:date_ajout,date_modification=:date_modification, model=:model, genre=:genre, pays=:pays, annee=:annee, prix=:prix WHERE id =:id";
            $stmt = $this->bd->prepare($rq);
            $data = array(
                ':id'=> $id,
                ':montre_ref' => $montre->getMontreref(),
                ':date_ajout' => $montre->getDateAjout(),
                ':date_modification' => $montre->getModifDate()->format('Y-m-d H:i:s'),
                ':model' => $montre->getModel(),
                ':genre' => $montre->getGenre(),
                ':pays' => $montre->getPays(),
                ':annee' => $montre->getAnnee(),
                ':prix' => $montre->getPrix(),
            );
            $stmt->execute($data);
            return $id;
        }
        return false;
    }


    public function getMontresTab(){
        return $this->montresTab;
    }
}






?>