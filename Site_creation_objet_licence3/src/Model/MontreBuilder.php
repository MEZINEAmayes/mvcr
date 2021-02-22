<?php
class MontreBuilder{
    protected $data;
    public $errors;
    public static $MONTRE_REF = "montre_ref";
    public static $DATE_REF = "date_ajout";
    public static $DATEMOD_REF = "date_modification";
    public static $MODEL_REF = "model";
    public static $GENRE_REF = "genre";
    public static $PAYS_REF = "pays";
    public static $ANNEE_REF = "annee";
    public static $PRIX_REF = "prix";

    public function __construct($data){
        $this->data = $data;
        $this->errors= null;
    }

    public function getData(){
        return $this->data;
    }

    public function getErrors(){
        return $this->errors;
    }

// recuperer les données  dans les  variable refference
    public static function buildFromMontre(Montre $montre) {
        return new MontreBuilder(array(
            self::$MONTRE_REF => $montre->getMontreref(),
            self::$MODEL_REF => $montre->getModel(),
            self::$GENRE_REF => $montre->getGenre(),
            self::$PAYS_REF => $montre->getPays(),
            self::$ANNEE_REF => $montre->getAnnee(),
            self::$PRIX_REF => $montre->getPrix(),
        ));							
    }

//Création d'un montre
    public function createMontre(Account $account){

        return $account=new Montre($this->data[self::$MONTRE_REF], $this->data[self::$MODEL_REF], $this->data[self::$GENRE_REF],$account->getName(), $this->data[self::$ANNEE_REF], $this->data[self::$PRIX_REF]);
    }

//Modification d'une montre
    public function updateMontre(Montre $montre) {

        if (key_exists(self::$MONTRE_REF, $this->data))

            $montre->setMontreref($this->data[self::$MONTRE_REF]);
        if (key_exists(self::$MODEL_REF, $this->data))
            $montre->setModel($this->data[self::$MODEL_REF]);

        if (key_exists(self::$GENRE_REF, $this->data))
            $montre->setGenre($this->data[self::$GENRE_REF]);

        if (key_exists(self::$PAYS_REF, $this->data))
            $montre->setPays($this->data[self::$PAYS_REF]);

        if (key_exists(self::$ANNEE_REF, $this->data))
            $montre->setAnnee($this->data[self::$ANNEE_REF]);

        if (key_exists(self::$PRIX_REF, $this->data))
            $montre->setPrix($this->data[self::$PRIX_REF]);
    }


    public function isValid(){
        
      // verifier l'existence du la reffernce  et si n'est pas null
      if(key_exists($this->data[self::$MONTRE_REF],$this->data)){
        $this->errors="la référence existe déja !!";
    }
 
    if($this->data[self::$MONTRE_REF]==="" && !key_exists($this->data[self::$MONTRE_REF],$this->data)){
   

    $this->errors="vous devez enterr une référence de montre !!";


   }
       

     /* if (!key_exists($this->data[self::$GENRE_REF], $this->data) || $this->data[self::$GENRE_REF] === "" )
        {
            $this->errors= 3;
        }*/
        
       if (!key_exists($this->data[self::$MODEL_REF], $this->data) && $this->data[self::$MODEL_REF] === "" )
        {

            $this->errors ="Vous devez enter un model ";
        }


       if (!key_exists($this->data[self::$ANNEE_REF], $this->data) && $this->data[self::$ANNEE_REF] === "" )
        {
         // var_dump(!key_exists($this->data[self::$ANNEE_REF], $this->data));
          //var_dump($this->data);
          //var_dump($this->data[self::$ANNEE_REF] === "");
            $this->errors = "Vous devez entrer une annee";
        
        }

    if (!key_exists($this->data[self::$PRIX_REF], $this->data) && $this->data[self::$PRIX_REF] === "" )
        {

            $this->errors ="Vous dever entrer un prix" ;
        }

        /*

        on un probléme  du genre ;
  warning cannot modify header information - headers already sent by
  la resolution du problém necessite une autorsation root sur php.ini
        phpinfo();
    
        output_buffering ==0
        permissons non accorder(SUDO) 
        var_dump( $this->errors==="" );  
       */
      //var_dump($this->errors);
    return $this->errors ===null;
      

    }





}
?>
