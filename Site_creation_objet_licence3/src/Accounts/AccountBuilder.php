<?php

require_once("Account.php");
class  AccountBuilder{
    protected $data;
    protected $errors;
    public static $LOGIN_REF = "login";
    public static $PASSWORD_REF = "password";
    public static $PASSWORDCONF_REF ="passwordConf";
    public static $DIFF_REF = "passwordiff";
    public static $NAME_REF = "name";



    public function __construct($data){
        $this->data = $data;
        $this->errors = null;
    }

    public function getData(){
        return $this->data;
    }

    public function getError(){
        return $this->errors;
    }
   // recupérer la valeur des champs static pour la creation du compte

    public static function buildFromAccount(Account $account) {
        return new AccountBuilder(array(
            self::$LOGIN_REF => $account->getLogin(),
            self::$PASSWORD_REF => $account->getPassword(),
            self::$PASSWORDCONF_REF => $account->getPassword(),
            self::$NAME_REF => $account->getName(),
        ));
    }
 // creation du compte 
    public function createAccount(){
        return new Account($this->data[self::$LOGIN_REF], password_hash($this->data[self::$PASSWORD_REF], PASSWORD_BCRYPT),$this->data[self::$NAME_REF]);
    }
  // verification des champs rentrer par les users et leur existence 
    public function isValidCreation(){
        $this->errors = array();
      
        if (!key_exists(self::$LOGIN_REF, $this->data) || $this->data[self::$LOGIN_REF] === "")
            $this->errors[self::$LOGIN_REF] = "Entrer un login";
        if (!key_exists(self::$NAME_REF, $this->data) || $this->data[self::$NAME_REF] === "")
            $this->errors[self::$NAME_REF] = " Entrer un nom";
        if (!key_exists(self::$PASSWORD_REF, $this->data) || $this->data[self::$PASSWORD_REF] === "")
            $this->errors[self::$PASSWORD_REF] = "Entrer un mot de passe";
        if (!key_exists(self::$PASSWORDCONF_REF, $this->data) || $this->data[self::$PASSWORDCONF_REF] === "")
            $this->errors[self::$PASSWORDCONF_REF] = "Confirmer votre mot passe";
            if ($this->data[self::$PASSWORD_REF] !=$this->data[self::$PASSWORDCONF_REF])
            $errors [self::$LOGIN_REF]= " Les mots de passes sont différents";

           $this->isValid($this->errors);
        return count($this->errors) === 0;
    }
    // vérification des champs du compte a modifier

    public function updateAccount(Account $account) {
        if (key_exists(self::$NAME_REF, $this->data))
            $account->setName($this->data[self::$NAME_REF]);
        if (key_exists(self::$PASSWORD_REF, $this->data))
            $account->setPassword(password_hash($this->data[self::$PASSWORD_REF],PASSWORD_BCRYPT));
    }

    //Valider modif  
    public function isValidUpdate(){
        $this->errors = array();
        $this->isValid($this->errors);
        return count($this->errors) === 0;
    }

    public function isValid()
    {
        return count($this->errors) === 0;
    }

  




}