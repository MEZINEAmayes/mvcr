<?php
class Montre {

    protected $montre_ref;
    protected $date_ajout;
    protected $model;
    protected $genre;
    protected $pays;
    protected $annee;
    protected $prix;
    protected $modifDate;


    public function __construct($montre_ref, $model, $genre, $pays, $annee, $prix, $date_ajout=null, $modifDate=null)
    {
        $this->montre_ref = $montre_ref;
        $this->model = $model;
        $this->genre = $genre;
        $this->pays = $pays;
        $this->annee = $annee;
        $this->prix = $prix;
        $this->date_ajout = $date_ajout !== null? $date_ajout: new DateTime();
        $this->modifDate = $modifDate !== null? $modifDate: new DateTime();

    }

    public function getMontreref()
    {
        return $this->montre_ref;
    }

    public function getDateAjout()
    {
        return $this->date_ajout;
    }


    public function getModifDate() {
        return $this->modifDate;
    }

    public function getModel(){
        return $this->model;
    }

    public function getGenre(){
        return $this->genre;
    }

    public function getPays(){
        return $this->pays;
    }

    public function getAnnee(){
        return $this->annee;
    }

    public function getPrix(){
        return $this->prix;

    }

    /**
     * @param mixed $annee
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
        $this->modifDate = new DateTime();

    }

    /**
     * @param mixed $pays
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
        $this->modifDate = new DateTime();

    }

    /**
     * @param mixed $date_ajout
     */
    public function setDateAjout($date_ajout){
        $this->date_ajout = $date_ajout;
        $this->modifDate = new DateTime();

    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre){
        $this->genre = $genre;
        $this->modifDate = new DateTime();

    }

    /**
     * @param mixed $montre_ref
     */
    public function setMontreref($montre_ref){
        $this->montre_ref = $montre_ref;
        $this->modifDate = new DateTime();

    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix){
        $this->prix = $prix;
        $this->modifDate = new DateTime();

    }

    /**
     * @param mixed $model
     */
    public function setModel($model){
        $this->model = $model;
        $this->modifDate = new DateTime();

    }

}
?>