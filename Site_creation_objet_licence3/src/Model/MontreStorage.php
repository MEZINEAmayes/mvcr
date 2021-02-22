<?php

interface MontreStorage{

      public function read($id);

     public function readAll();
     public function create(Montre $montre);
     
     public function modifyM($id, Montre $montre);

    public function deleteM($id);
 
    public function getMontresTab();

  public function readMesMontres(Account $account);
}
?>