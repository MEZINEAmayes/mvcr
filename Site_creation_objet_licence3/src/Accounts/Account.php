<?php

class Account{

    protected $login;
    protected $password;
    protected $statut;
    protected $nom;



    public function __construct($login,$password,$nom,$statut=1){
        $this->login=$login;
        $this->password=$password;
        $this->statut=$statut;
        $this->nom=$nom;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @param mixed $name
     */
    public function setName($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

}

?>