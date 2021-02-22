<?php

require_once("View.php");
require_once("Accounts/Account.php");

class PrivateView extends View{

    protected $account;

    public function __construct(Router $router, $feedback, Account $account)
    {
        parent::__construct($router, $feedback);
        $this->account = $account;
    }


    
    public function render()

    {
        $feedback = $this->feedback;
        $title = $this->title;
        $content = $this->content;
        $menu = array(
            "Accueil" => $this->router->getHomeURL(),
            "Liste montres" => $this->router->getListURL(),
            "Ajouter une Montre" => $this->router->getMontreCreationURL(),
            "Mes montres" => $this->router->getMesMontresURL(),
            "Mon Profil" => $this->router->getMonProfilURL(),
           
            "À propos " => $this->router->getPageAproposURL(),


            "Deconnexion " => $this->router->getDeconnexionURL()

        );
        include("template.php");
    }

    public function makeHomePage()
    {
        $this->title = "Bienvenue " .$this->account->getLogin();
        $this->content = "<center><p style='color: green;' >Bienvenue sur notre site </p></center>";


    }

    public function makeMesMontresPage($tab){
        $this->title = "Mes montres";
        $this->content .="<center><p style='font-size:25px; color: green;' >Private listes </p></center>";
        if (sizeof($tab)>0){
            foreach ($tab as $key => $value) {
                $url = $this->router->getMontrePrivateURL($key);
                $this->content .= "<center><a  style='color:white;margin:5px; text-decoration:none;' href = $url >" . $value->getMontreref() . "<a/></center></br>";
            }
        }

    }

    public function makeMontrePagePrivate(Montre $montre, $id){
      
       
        $this->title = "Montre " . $montre->getMontreref();
        $this->content = "<center><p> Le montre" . $montre->getMontreref() . " a été crée par " . $montre->getPays() ."</center></p>";
        $this->content .= "<center><p>Elle a été créée le ".$montre->getDateAjout()." et modifiée le ".$montre ->getModifDate()."</p></center>\n";
        $this->content.='<center><button><a href='.$this->router->getMontreModificationURL($id) .'>Modifier cette montre</a></button></center><br/>';
        $this->content.='<center><button> <a href='.$this->router->getMontreAskDeletionURL($id) .'>Supprimer cette montre</a> </center></button><br/>';
    }


    public function makeMonProfilPage(Account $account){
        $this->title= "profil";
        $this->content= "<center><p>Votre Login est " . $account->getLogin(). "</p></center><br/>";
        $this->content .= "<center><p>  Votre Pseudo est: " . $account->getName() ."</p></center><br/>";
        $this->content.='<center><button><a href='.$this->router->getAccountModificationURL() .'>Modifier mon profil</a></button></center>';
    }


    public function makeAccountModificationPage(AccountBuilder $accountBuilder){
        $data = $accountBuilder->getData();
        $this->title = "Modification de profil";
        $url = $this->router->getAccountUpdateURL();
        $this->content .= '<form method="post" action =" '.$url.' ">';
       
        $this->content .=
        '<div id="finscription">
            <fieldset>
                    <label  id = "inlog" for = ""> Nom : </label><input type = "text"   id = "inlog" id = "name" name = "name" value = "'.$data[$accountBuilder::$NAME_REF].'"/>                       	 	
                    <label  id = "inlog"  for = ""> Votre ancien mot de passe : </label><input type = "password"  id = "inlog"  id = "password" name = "password" value = "'.$data[$accountBuilder::$PASSWORDCONF_REF].'"/>	
                    <label  id = "inlog" for = ""> Votre nouveau mot de passe : </label><input type = "password"  id = "inlog"  id = "password" name = "passwordConf" value = "'.$data[$accountBuilder::$PASSWORD_REF].'"/>	                                 	
                    <button>Modifier mon profil</button>
                </fieldset>
            </form>
            </div>';
    }

    public function displayAccountModificationSuccess(){
        $this->router->POSTRedirect($this->router->getMonProfilURL(), '<p>Modification de votre compte réussi</p>');
    }

    public function displayAccountModificationFailure(){
        $this->router->POSTRedirect($this->router->getAccountModificationURL(), 'Echec de la modification du compte');

    }
    public function displayAccountModificationFailurePassword(){
        $this->router->POSTRedirect($this->router->getAccountModificationURL(), 'L\'ancien mot de passe est incorrecte');

    }
}