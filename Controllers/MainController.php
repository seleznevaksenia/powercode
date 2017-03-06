<?php
class MainController
{
    public function actionIndex()
    {
        $list = "";
        $list = MainModel::show('USER-1');
               require_once(ROOT . '/view/site/index.php');
        return true;
    }
    public function actionError()
    {
        require_once(ROOT . '/view/site/error.php');
        return true;
    }
    public function actionCreate()
    {
        MainModel::createFolder($_POST['id']);
        echo MainModel::show('USER-1');
        return true;
    }
    public function actionDelete()
    {
        MainModel::deleteFolder($_POST['id']);
        echo MainModel::show('USER-1');
        return true;
    }
    public function actionRename()
    {
        MainModel::renameFolder($_POST['id'],$_POST['new_name']);
        echo MainModel::show('USER-1');
        return true;
    }
}