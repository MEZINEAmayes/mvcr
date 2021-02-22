<?php
require_once("View/View.php");
require_once("Control/Controller.php");
require_once("Model/MontreBuilder.php");
require_once("View/PrivateView.php");

class Router{
    private $view;
    private $controller;
    private $montreStorage;
    private $accountStorage;

    private $ids;

    public function __construct(MontreStorage $montreStorage, AccountStorage $accountStorage)
    {


        $this->montreStorage = $montreStorage;
        $this->accountStorage = $accountStorage;

    }

    public function main(){
        session_start();


        /* On lance la session pour pouvoir garder des variables
         * d'une page à une autre.
         */

        if(isset($_SESSION['user'])){


            if(key_exists('feedback',$_SESSION)) {
                $this->view = new PrivateView($this, $_SESSION['feedback'], $_SESSION['user']);
            } else {
                $this->view = new PrivateView($this, null, $_SESSION['user']);
            }
        } else if (!(isset($_SESSION['user']))){

            if(key_exists('feedback',$_SESSION)) {
                $this->view = new View($this, $_SESSION['feedback']);
            } else {
                $this->view = new View($this, null);
            }
        }

        $this->controller = new Controller($this->view, $this->montreStorage, $this->accountStorage);
        unset($_SESSION['feedback']);
       

        if (key_exists('mont', $_GET)) {
            $this->ids=$this->controller->showMesMontres();
            if (key_exists($_GET['mont'],$this->ids)){
                $montreId = $_GET['mont'] ;
            }
            else{
                $montreId=null;
            }

        }

        if (key_exists('id', $_GET)) {
            $this->controller->showInformation($_GET['id']);
        }
        else if (key_exists('liste', $_GET)) {
            $this->controller->showList();

        }

        else if (key_exists('action', $_GET)) {
            $action = $_GET['action'];

            switch ($action) {
                case $action === 'nouveau':
                    $this->controller->newMontre();
                break;

                  case $action === 'sauvegardeNouveau':
                    $this->controller->saveNewMontre($_POST);
                break;
            

                case $action === 'sauvegardeModification':
                    $this->controller->saveModifiedMontre($_POST);
                break;

                case $action === 'connection' :
                    $this->controller->newConnection();
                break;

                case $action === 'newConnection':
                    $this->controller->checkAutent($_POST);
                break;


                case  $action === 'disconnect' :
                    $this->controller->disconnect();
                break;

                case $action ==='askdelete':
                    $this->controller->AskDeleteMontre($montreId);
                break;

                case  $action ==='delete':
                    $this->controller->DeleteMontre($montreId);
                break;

                case  $action ==='modify':
                    $this->controller->updateMontre($montreId);
                break;

                case  $action ==='savemodifs':
                    $this->controller->saveModifiedMontre($montreId,$_POST);
                break;

                case  $action ==='createAccount':
                    $this->controller->newAccount();
                break;


                case  $action ==='saveAccount':
                    $this->controller->saveNewAccount($_POST);
                break;


                case $action ==='mesMontres' :
                    $this->controller->showMesMontres();
                break;

                case $action ==='monProfil':
                    $this->controller->showMonProfil();
                break;

                case $action ==='accountModify' :
                    $this->controller->updateAccount();
                break;


                case  $action ==='accountUpdate':
                    $this->controller->saveUpdatedProfil($_POST);
                break;

                case $action ==='apropos' :
                    $this->controller->showApropos();
                break;


                case $action==='?':
                    $this->view->makeUnknowMontrePage();
                break;
                

                
                default:
                $this->controller->homepage();
                    break;
            }

       
        }
        else if (key_exists('idM', $_GET) ) {
            $this->ids=$this->controller->showMesMontres();
            if (key_exists($_GET['idM'],$this->ids)){
                $this->controller->showInformationPrivate($_GET['idM']);
            }
            else{
                $this->controller->unknownPage();
            }
        }
        else {
            $this->controller->homepage();
        }

        $this->view->render();
        //session_unset();
    }


    public function getHomeURL(){
        return "?";

    }

    public function getListURL(){
        return "?liste";
    }

    public function getMontreCreationURL(){
        return "?action=nouveau";
    }

    public function getMontreSaveURL(){
        return "?action=sauvegardeNouveau";
    }

    public function getMontreURL($id){
        return "?id=".$id;
    }
    public function getMontrePrivateURL($id){
        return "?idM=".$id;
    }

    public function getConnexionURL(){
        return "?action=connection";
    }

    public function getCheckAuthURL(){
        return "?action=newConnection";
    }

    public function getDeconnexionURL(){
        return "?action=disconnect";
    }
    public function getMesMontresURL(){
        return "?action=mesMontres";
    }

  

    // a modifier plus tard 

 public function POSTRedirect($url, $feedback){
        $_SESSION['feedback'] = $feedback;
        header("Location: " . $url, true, 303);
        die;
    }

    function getMontreModificationURL($id){
        return ".?mont=$id&action=modify";
    }

    public function getMontreUpdateURL($id){
        return ".?mont=$id&amp;action=savemodifs";
    }

    function getMontreAskDeletionURL($id){
        return ".?mont=$id&amp;action=askdelete";
    }

    function getMontreDeletionURL($id){
        return ".?mont=$id&amp;action=delete";
    }

    function getCreationAccountURL(){
        return ".?action=createAccount";
    }

    function getAccountCreatedURL(){
        return ".?action=saveAccount";
    }

    function getMonProfilURL(){
        return ".?action=monProfil";
    }

   function getAccountModificationURL(){
        return ".?action=accountModify";
    }

    function getPageAproposURL(){
        return ".?action=apropos";
    }
  function  getAccountUpdateURL(){
        return ".?action=accountUpdate";
    }



}
?>