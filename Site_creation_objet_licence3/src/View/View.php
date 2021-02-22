<?php
error_reporting('Warning');
//Erreur a corriger 
require_once("Router.php");
require_once("Accounts/AccountBuilder.php");
require_once ("Model/MontreBuilder.php");


class View{
	
	protected $title;
	protected $content;
	protected $router;
	protected $feedback;
	protected $errors;
	protected  $faults;

	public function __construct(Router $router, $feedback){
		$this->router = $router;
		$this->title = null;
		$this->content = null;
        $this->feedback = $feedback;
        $this->errors=null;


	}

	public function render(){

		$feedback = $this->feedback;
		$title = $this->title;
		$content = $this->content;
		$menu = array(

            "Accueil" => $this->router->getHomeURL(),
            "Accueil" => $this->router->getListURL(),

			
			"inscription " => $this->router->getCreationAccountURL(),
            "Connexion " => $this->router->getConnexionURL(),


			);
		include("template.php");
	}

	public function makeHomePage(){

		$this->title = "Bienvenue";
        $this->content='beienvenus';
    
    
        $this->content .= 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat.Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus';
         
       


    }
    


	public function makeListePage($tab)
    {
        $this->title = "La liste des montres";
        foreach ($tab as $key => $value) {
            $url = $this->router->getMontreURL($key);
         
            $this->content .= "<center><article><a href =  ".$url." >" . $value->getMontreref(). "<a/></article></center></br>";
        }
    }

	public function makeMontreCreationPage(MontreBuilder $montreBuilder){
		$this->title = "Création d'une montre";

        $url = $this->router->getMontreSaveURL();
        $data = $montreBuilder->getData();
       $x=$montreBuilder->getErrors();
         switch ($x)
         {
             case 1:$x='Veuiller  entrer  une référence  ';
             break;
             case 2:$x='la référence existe déja';
             break;
             case 3:$x='veuiler  entrer un model';
             break;
             case 4:$x='Veuiller entrer le genre de la montre ';
             break;
             case 5:$x=' Veuiller entrer l annee de creation ';
             break;
             case 6:$x='Veuiller entrer le prix ';



         }

        $this->content .= '<p id="errors"  style="text-align: center; color:#ff0000;">'.$x.'</p>';
        $this->content .= '<form method="post" action =" '.$url.' ">';
        $this->content .=

           '<div id="finscription" >
            <fieldset>
                    <label for = "'. $montreBuilder::$MONTRE_REF .'"> Référence montre: </label><input type = "text"  id = "'.$montreBuilder::$MONTRE_REF.'" name = "'.$montreBuilder::$MONTRE_REF.'" value = "'.$data[$montreBuilder::$MONTRE_REF ].'"/>
                    <p>Modèles :
                        <select name="'.$montreBuilder::$MODEL_REF.'"> 
                            <option value = "Tank">Cartier Tank</option>
                            <option value = "Calibre">Calibre 101 Reverso de Jaeger-LeCoultre </option>
                            <option value = "Rolex">Rolex Oyster</option>
                            <option value = "Bulva">Bulova Accutron</option>
                            <option value = "Patek">Patek Philippe Réf 1415 HU</option>                            
                            <option value = "Omega">Omega Speedmaster</option>

                            <option value = "Breitling">Breitling Navitime</option>
                            <option value = "Opus">Opus 3 by Harry Winston de Vianney Halter</option>
                            <option value = "Zenith">Zenith El Primero</option>
                            <option value = "Swatch">Swatch </option>
                          
                            
                            
                        </select>
                    </p>
                    <p>Pour quel genre : 
                        <input type="radio" id=femme name="'.$montreBuilder::$GENRE_REF.'" value="Femme">
                            <label for="femme">Femme</label>
                        <input type="radio" id="homme" name="'.$montreBuilder::$GENRE_REF.'" value="Homme">
                            <label for="femme">Homme</label>
                              <input type="radio" id="enfant" name="'.$montreBuilder::$GENRE_REF.'" value="enfant">
                            <label for="enfant">enfant</label>
                           

                            
                    </p>	 	
                    <label for = "'.$montreBuilder::$ANNEE_REF.'"> Année : </label><input type = "text"  id = "'.$montreBuilder::$ANNEE_REF.'" name = "'.$montreBuilder::$ANNEE_REF.'" value = "'.$data[$montreBuilder::$ANNEE_REF].'"/>    		    
                    <label for = "'.$montreBuilder::$PRIX_REF.'"> Prix : </label><input type = "text"  id = "'.$montreBuilder::$PRIX_REF.'" name = "'.$montreBuilder::$PRIX_REF.'" value = "'.$data[$montreBuilder::$PRIX_REF].'"/>	
                  <LABEL for="file" name="file">image de la montre</LABEL>
                    <input type="file"  name="file "/>
                    
                    <button>Créer la montre</button>
                </fieldset>
		    </form>
</div>';
	}

    // modification de l'article montre
    public function makeMontreModificationPage($id, MontreBuilder $montreBuilder){
        $this->title = "Modification de la montre";
        $url = $this->router->getMontreUpdateURL($id);
        $data = $montreBuilder->getData();
        $this->content = '<h3 style="color:#ffff;">' .$montreBuilder->getErrors().'</h3>';
        $this->content .= '<form method="post" action =" '.$url.' ">';
        $this->content .=
            '<fieldset>
                    <label for = "'.$montreBuilder::$MONTRE_REF.'"> Nom du Montre: </label><input type = "text"  id = "'.$montreBuilder::$MONTRE_REF.'" name = "'.$montreBuilder::$MONTRE_REF.'" value = "'.$data[$montreBuilder::$MONTRE_REF].'"/>
                    <p class="old-value">L\'ancienne valeur est: <em>'.$data[$montreBuilder::$MODEL_REF].'</em></p>
                    <p>Modèles :
                        <select name="'.$montreBuilder::$MODEL_REF.'"> 
                                   <option value = "Tank">Cartier Tank</option>
                            <option value = "Calibre">Calibre 101 Reverso de Jaeger-LeCoultre </option>
                            <option value = "Rolex">Rolex Oyster</option>
                            <option value = "Bulva">Bulova Accutron</option>
                            <option value = "Patek">Patek Philippe Réf 1415 HU</option>                            
                            <option value = "Omega">Omega Speedmaster</option>
                            <option value = "Breitling">Breitling Navitime</option>
                            <option value = "Opus">Opus 3 by Harry Winston de Vianney Halter</option>
                            <option value = "Zenith">Zenith El Primero</option>
                            <option value = "Swatch">Swatch </option>


                        </select>
                    </p>
                    <p class="old-value">L\'ancienne valeur est: <em>'.$data[$montreBuilder::$GENRE_REF].'</em></p>
                    <p>Pour quel genre : 
                        <input type="radio" id=femme name="'.$montreBuilder::$GENRE_REF.'" value="Femme">
                            <label for="femme">Femme</label>
                        <input type="radio" id="homme" name="'.$montreBuilder::$GENRE_REF.'" value="Homme">
                            <label for="femme">Homme</label>

                            <input type="radio" id="enfant" name="'.$montreBuilder::$GENRE_REF.'" value="enfant">
                            <label for="enfant">enfant</label>
                    </p>	 	
                    <label for = "'.$montreBuilder::$ANNEE_REF.'"> Année : </label><input type = "text"  id = "'.$montreBuilder::$ANNEE_REF.'" name = "'.$montreBuilder::$ANNEE_REF.'" value = "'.$data[$montreBuilder::$ANNEE_REF].'"/>    		    
                    <label for = "'.$montreBuilder::$PRIX_REF.'"> Prix : </label><input type = "number"  id = "'.$montreBuilder::$PRIX_REF.'" name = "'.$montreBuilder::$PRIX_REF.'" value = "'.$data[$montreBuilder::$PRIX_REF].'"/>	
                    <button>Modifier la montre</button>
                </fieldset>
		    </form>';
    }
         // la page d'inscription
    public function createAccountPage(AccountBuilder $accountBuilder){
        $data = $accountBuilder->getData();

        $faults=$accountBuilder->getError();
        $this->title = "Inscription";
        $url = $this->router->getAccountCreatedURL();
     foreach ($faults as $fault){
       $this->content.=   '<p style="color: red; text-align: center;"  >' .$fault.'</p>';
       }
       
       
       
       $this->content .= '<form  method="post" action =" '.$url.' ">';
        $this->content .=

            '<fieldset >
                 <div  id="finscription"
                         
                                <label  id = "inlog" for = "name"> Nom :      </label><input id = "inlog" type = "text"  id = "name" name = "name" value = "' .$data[$accountBuilder::$NAME_REF].'" placeholder="votre nom"/>   <br/>                    	 	
                    <label id = "inlog" for = "login"> Login :  </label><input  id = "inlog" type = "text"  id = "inlog" name = "login" value = "'.$data[$accountBuilder::$LOGIN_REF].'" placeholder="votre  login"/>   <br/>   		    
                    <label  id = "inlog" for = "password"> mot de passe :     </label><input id="inlog" type = "password"  id = "password" name = "password" value = "'.$data[$accountBuilder::$PASSWORD_REF].'" placeholder="votre mots de passe"/>	 <br/> 
                    <label id="inlog" for = "password"> confirmer le mot de passe: </label><input id="inlog" type = "password"  id = "password" name = "passwordConf" value = "'.$data[$accountBuilder::$PASSWORDCONF_REF].'" placeholder="confimer votre mot de passe"/> <br/> 
                                        		
                    
                    <button>Créer le compte</button>
                 </div>
                </fieldset>
               
		    </form>';
    }

    public function makeMontreDeletionPage($id){
        $this->title = "Suppression de la  Montre ".$id;
        $this->content = "<p>souhaiter vous supprimer cette article :  ".$id." ?</p></br>";
        $this->content .= '<form action="'.$this->router->getMontreDeletionURL($id).'" method="POST">'."</br>";
        $this->content .= "<button>Confirmer</button> </form>\n";

    }

	public function makeMontrePage(Montre $montre){
       //  $montre->getDateAjout();
      
        $this->title = "Montre " . $montre->getMontreref();
        $this->content = "La montre " . $montre->getMontreref() . " crée par " . $montre->getPays();
        $this->content .= "<p>Il a été créée ". $montre->getDateAjout()." et modifiée ".$montre ->getModifDate()."</p>\n";
    }

    public function makePageApropos(){
	    $this->title="À propos";
	    $this->content='
	    
	    <h2>DM de 21914630 & 21913200</h2>
	     
	    
     <h1> Critères realisés </h1> 
     <ul> 
        <li> Architecture MVCR </li></br>
        <li> Respect les nomres de sécuritées de données , mot de passe et autres </li></br>
        <li> Site responsive avec CSS sans framework </li></br>
        <h1>Resumer sur notre site</h1>
        <p> Le visiteur peut voir la listes des articles  sans etre connecté</p>
        <p> L\'utilisateur dois creer un compte pour pouvoire creer des articles et les manipuler (ajout, suppression  modification)  articles et profile </p>
        <p> chaque user connercté a sa liste privée des articles</p>

     </ul>

	    ';
    }

    public function makeLoginFormPage(){
    $this->title = "Page de connexion";
    $url = $this->router->getCheckAuthURL();
    $this->content = '<form method="post" action=" '.$url. '">';
    $this->content .=
        '<div id="finscription">
        <fieldset>
				<label id="inlog"  for = "login"> Login : </label><input id="inlog"   type = "text"  id = "login" name = "login" value ="" placeholder="votre login"/>
				<label id="inlog" for = "password"> Mot de passe : </label><input id="inlog"  type = "password"  id = "password" name = "password" value = "" placeholder="votre mot de passe"/>
				<input id="inlog"  type = "submit"  />
		</fieldset>
		</form>
		</div>';
}

	public function makeUnknowMontrePage(){
	    $this->title = "Erreur";
	    $this->content = "La page demandée n'existe pas";
    }

    public function makeUnexpectedErrorPage() {
        $this->title = "Erreur";
        $this->content = "Une erreur inattendue s'est produite.";
    }


    public function displayMontreCreationSuccess($id){
	    $this->router->POSTRedirect($this->router->getMontrePrivateURL($id), 'Le montre a bien été créée');
    }


    public function displayMontreCreationFailure(MontreBuilder $montre){
      $this->router->POSTRedirect($this->router->getMontreCreationURL(),"Echec de creation de montre  :\n".$montre->getErrors() );

    }

    public function displayMontreExisteFailure(MontreBuilder $montre){
        $this->router->POSTRedirect($this->router->getMontreCreationURL(),$montre->getErrors() );
  
      }

    public function displayMontreModificationSuccess($id){
        $this->router->POSTRedirect($this->router->getMontreURL($id), 'Modification réussie');
    }

    public function displayMontreModificationFailure($id){
        $this->router->POSTRedirect($this->router->getMontreURL($id), 'Echec de la modification du la montre');

    }
    public function diplsayMontreDelete(){
        return $this->router->POSTredirect($this->router->getMesMontresURL(),"Suppression de la montre réussie");
    }

    public function displayAccountCreationSuccess(){
        $this->router->POSTRedirect($this->router->getListURL(), 'Le compte a bien été créé');
    }

    public function displayAccountCreationFailure(){
        $this->router->POSTRedirect($this->router->getCreationAccountURL(), 'Echec de la création du compte');
    }

    public function displayAccountCreationFailureExisting(){
        $this->router->POSTRedirect($this->router->getCreationAccountURL(), 'Login et Nom déjà utilisés');
    }

    public function displayAccountCreationFailureExistingName(){
        $this->router->POSTRedirect($this->router->getCreationAccountURL(), ' Nom déjà existant');
    }

    public function displayAccountCreationFailureExistingLogin(){
        $this->router->POSTRedirect($this->router->getCreationAccountURL(), ' Login déjà existant');
    }

    public function displayConnectionSuccess(){
	    $this->router->POSTRedirect($this->router->getHomeURL(), 'Connexion réussie');
    }

    public function displayConnectionFailure(){
        $this->router->POSTRedirect($this->router->getConnexionURL(), 'Echec de connexion');
    }

    public function displayDeconnectionSuccess(){
        $this->router->POSTredirect($this->router->getHomeURL(),'Vous êtes maintenant déconnecté !');
    }
    public function displayMotsPassDiff(){
	    return 'les deux mots de passes son differents';

    }


 

    protected static function fmtDate(DateTime $date) {
        return "le " . $date->format("Y-m-d") . " à " . $date->format("H:i:s");
    }

}
?>
