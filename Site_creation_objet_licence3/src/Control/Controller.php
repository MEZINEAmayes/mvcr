<?php

require_once("View/View.php");
require_once("Model/Montre.php");
require_once("Model/MontreStorageMySQL.php");
require_once("Accounts/AccountStorageSQL.php");
require_once("Accounts/AccountBuilder.php");
require_once ("Model/MontreBuilder.php");
require_once ("index.php");





class Controller{
	protected $view;
	protected $montreStorage;
	protected $accountStorage;

	public function __construct(View $view, MontreStorage $montreStorage, AccountStorage $accountStorage){
		$this->view = $view;
		$this->montreStorage = $montreStorage;
		$this->accountStorage = $accountStorage;
	}

   

  

    public function homepage(){
        $this->view->makeHomePage();
       
    }
	public function showInformation($id){
        if (array_key_exists($id,$this->montreStorage->readAll())) {
            $montre = $this->montreStorage->read($id);
            $this->view->makeMontrePage($montre);
        }
         else {
	        $this->view->makeUnknowMontrePage();
        }
    }

    public function showInformationPrivate($id){
        if (array_key_exists($id,$this->montreStorage->readAll())) {
            $montre = $this->montreStorage->read($id);
            $this->view->makeMontrePagePrivate($montre,$id);
        }
        else {
            $this->view->makeUnknowMontrePage();
        }
    }
    public function showList(){
        $this->view->makeListePage($this->montreStorage->readAll());

    }

    public function showMesMontres(){
	    $privateIds=array();
        $this->view->makeMesMontresPage($this->montreStorage->readMesMontres($_SESSION['user']));
        if (sizeof($this->montreStorage->readMesMontres($_SESSION['user']))>0){
             foreach ($this->montreStorage->readMesMontres($_SESSION['user']) as $ids=> $montre){
                 $privateIds[$ids]=$ids;
             }
        }
        return $privateIds;

    }

    public function newMontre(){
        if(key_exists('currentNewMontre', $_SESSION)){
            $this->view->makeMontreCreationPage($_SESSION['currentNewMontre']);

        } else {
            $this->view->makeMontreCreationPage(new MontreBuilder(null));
        }
    }

    public function saveNewMontre(array $data){
        $newMontreBuilder =new MontreBuilder ($data);
        $Data=$newMontreBuilder->getData();
      
        if($newMontreBuilder->isValid()){
            $id = $this->montreStorage->create($newMontreBuilder->createMontre($_SESSION['user']));
            $this->view->displayMontreCreationSuccess($id);
        } else { 
            $_SESSION['currentNewMontre'] = $newMontreBuilder;

            if(key_exists($Data[MontreBuilder::$MONTRE_REF], $Data)){

                $this->view->displayMontreExisteFailure($newMontreBuilder);
            }else{
                $this->view->displayMontreCreationFailure($newMontreBuilder);

            }

        


        }
    }

    public function updateMontre($id) {
        /* On récupère en BD le montre à modifier */
        $montre = $this->montreStorage->read($id);
        if ($montre === null) {
            $this->view->makeUnknownMontrePage();
        } else {
            /* Extraction des données modifiables */
            $montreBuilder = MontreBuilder::buildFromMontre($montre);
            $this->view->makeMontreModificationPage($id, $montreBuilder);
        }
    }

    public function saveModifiedMontre($id, array $data){
        $montre = $this->montreStorage->read($id);
        if ($montre === null) {
            $this->view->makeUnknowMontrePage();
        }
        else {
            $newMontreBuilder = new MontreBuilder($data);
            if($newMontreBuilder->isValid()){
                $newMontreBuilder->updateMontre($montre);
                $id = $this->montreStorage->modifyM($id , $montre);
                $this->view->displayMontreModificationSuccess($id);
            } else {
                $_SESSION['MontreToModify'] = $newMontreBuilder;
                $this->view->displayMontreModificationFailure($id);
            }
        }
    }




    public function AskDeleteMontre($id){
        $montre = $this->montreStorage->read($id);
        if ($montre === null) {
            $this->view->makeUnknowMontrePage();
        }
        else {
            $this->view->makeMontreDeletionPage($id);
        }
    }

    public function DeleteMontre($id){
        $montre = $this->montreStorage->read($id);
        if ($montre === null) {
            $this->view->makeUnknowMontrePage();
        }
        else {
            $this->montreStorage->deleteM($id);
            $this->view->diplsayMontreDelete();
        }


    }

    public function checkAutent(array $data){
        $login = $data['login'];
        $password = $data['password'];
        if(($this->accountStorage->checkAuth($login, $password)) === true ){
            $this->view->displayConnectionSuccess();
        } else {
            $this->view->displayConnectionFailure();
        }
    }

    public function newConnection(){
	$this->view->makeLoginFormPage();
	}

    public function disconnect(){
        if(isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            $this->view->displayDeconnectionSuccess();
        }
    }




    public function newAccount(){
        if(key_exists('accountBuilder', $_SESSION)){
            $this->view->createAccountPage($_SESSION['accountBuilder']);


        } else {
            $this->view->createAccountPage(new AccountBuilder(null));

        }

    }

    public function saveNewAccount(array $data){

        $newAccountBuilder = new AccountBuilder($data);
        $tabAccounts=$this->accountStorage->readAll();


        if($newAccountBuilder->isValidCreation()){

           foreach ($tabAccounts as $acc) {
                   if ($acc->getLogin() === $data[AccountBuilder::$LOGIN_REF]) {
                    $_SESSION['accountBuilder'] = $newAccountBuilder;
                    $this->view->displayAccountCreationFailureExistingLogin();
                    }
                else if ($acc->getName()===$data[AccountBuilder::$NAME_REF]){
                    $_SESSION['accountBuilder'] = $newAccountBuilder;
                    $this->view->displayAccountCreationFailureExistingName();
                    }
                else if ($acc->getLogin() === $data[AccountBuilder::$LOGIN_REF] && $acc->getName()===$data[AccountBuilder::$NAME_REF]){
                    $_SESSION['accountBuilder'] = $newAccountBuilder;
                    $this->view->displayAccountCreationFailureExisting();
                }
               else if ($acc->getLogin() === $data[AccountBuilder::$LOGIN_REF] && $acc->getName()===$data[AccountBuilder::$NAME_REF]){
                    $_SESSION['accountBuilder'] = $newAccountBuilder;
                    $this->view->displayAccountCreationFailureExisting();
                } else if($data[AccountBuilder::$PASSWORD_REF]!=$data[AccountBuilder::$PASSWORDCONF_REF]){

                    $this->view->displayAccountCreationFailure();
                   
                   
                    }
               else {
                    $id = $this->accountStorage->createAccount($newAccountBuilder->createAccount());
                    $this->view->displayAccountCreationSuccess($id);
              }
            }
        }
        else {
            $_SESSION['accountBuilder'] = $newAccountBuilder;
            $this->view->displayAccountCreationFailure();
        }

    }


    public function showMonProfil(){
        $this->view->makeMonProfilPage($_SESSION['user']);
    }

    public function showApropos(){
	    $this->view->makePageApropos();
    }

    public function updateAccount() {
        /*
        
        On récupère le compte à modifier */
        $account = $_SESSION['user'];
        /* On vérifie s'il existe au cas où un administrateur supprime le compte*/
        if ($account === null) {
            $this->view->makeUnexpectedErrorPage();
        }
        else{
            /* recuperer  des donnes a modifié */
            $accountBuilder = AccountBuilder::buildFromAccount($account);
            /* genérer  le  formulaire modif */
            $this->view->makeAccountModificationPage($accountBuilder);
        }
    }

    public function saveUpdatedProfil(array $data)
    {
        /* On récupère en BD le compte à modifier */
        $account = $_SESSION['user'];
        /* si existance  du compte */
        if ($account === null) {
            /* le compte n'est pas en bd */
            $this->view->makeUnexpectedErrorPage();
        } else {
            $newAccountBuilder = new AccountBuilder($data);
            $tabAccounts=$this->accountStorage->readAll();

            if ($newAccountBuilder->isValidUpdate()) {
                foreach ($tabAccounts as $acc) {
                    if ($acc->getName()===$data[AccountBuilder::$NAME_REF]){
                        $_SESSION['accountBuilder'] = $newAccountBuilder;
                        $this->view->displayAccountCreationFailureExistingName();

                    }
                    else {
                        if($data[AccountBuilder::$PASSWORDCONF_REF]==="" || $data[accountBuilder::$PASSWORD_REF]===$data[AccountBuilder::$PASSWORDCONF_REF]){
                            $this->view->displayAccountModificationFailure();
                        }
                        if (password_verify($data[AccountBuilder::$PASSWORD_REF], $account->getPassword())){
                            $newAccountBuilder->updateAccount($account);
                            $this->accountStorage->modifyAccount($account->getLogin(),$account);
                            $this->view->displayAccountModificationSuccess();
                        }
                        else {
                            $_SESSION['AccountToModify'] = $newAccountBuilder;
                            $this->view->displayAccountModificationFailurePassword();
                        }

                    }
                }
            }
            else {
                /* modification echoué */
                $_SESSION['AccountToModify'] = $newAccountBuilder;
                $this->view->displayAccountModificationFailure();
            }
        }
    }
    public function unknownPage(){
        $this->view->makeUnexpectedErrorPage();
    }
    public function UploadImg(){
	    /*
        $statusMsg = '';

        $bd = new PDO('mysql:host=mysql.info.unicaen.fr; mysql:port=3306; dbname=21914630_bd', '21914630', '');
      echo('hello  world !!!');
      var_dump($bd);
        $targetDir = "uploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

        if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
            //  formats
            $allowTypes = array('jpg','png','jpeg','gif','pdf');
            if(in_array($fileType, $allowTypes)){
                // Upload file to server
                if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                    // Insert image file name into database
                    $insert = $bd->query("INSERT into images (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
                    if($insert){
                        $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
                    }else{
                        $statusMsg = "File upload failed, please try again.";
                    }
                }else{
                    $statusMsg = "Sorry, there was an error uploading your file.";
                }
            }else{
                $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
            }
        }else{
            $statusMsg = 'Please select a file to upload.';
        }

// Display status message
        echo $statusMsg;
    */
    }







}

?>
