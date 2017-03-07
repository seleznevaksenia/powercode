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
    public function actionSystem()
    {
        $list = "";
        $list = MainModel::show('');
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
    public function actionLoad()
    {

        if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
            $name = $_FILES["image"]['name'];
            if (isset($_POST['folder'])) {
             $sql = MainModel::getPath($_POST['folder']);
             $path = $sql['path'];
                // Если загружалось, переместим его в нужную папке, дадим новое имя
                move_uploaded_file($_FILES["image"]["tmp_name"], $path."/".$name);
            }
        }
        self::actionIndex();
        return true;
    }

    public function actionShowimage()
    {
        $sql = MainModel::getPath($_POST['folder']);
        $path = $sql['path'];
        if(MainModel::imagearray($path))
            echo   MainModel::printimage(MainModel::imagearray($path));
        return true;
    }

}