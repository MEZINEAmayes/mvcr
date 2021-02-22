<?php

require_once("AccountBuilder.php");
interface AccountStorage{


    public function read($id);

    public function readAll();

    public function createAccount(Account $account);

    public function modifyAccount($id, Account $account);

    public function deleteAccount($id);
    public function getAccountsTab();

    public function checkAuth($login, $password);
}