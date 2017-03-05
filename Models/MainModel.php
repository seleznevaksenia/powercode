<?php

class MainModel
{   static public $folder ="";
    public static function show($id) {
        $dir = ROOT . "/upload/$id";
        $folder = self::getFolderId($dir);
        return "<ul><li><div data-folid ='".$folder['id']."' data-perid ='".$folder['parent_id']."' id='mainfold' >".$id.showfolder($dir)."</div></li></ul>";

    }
    public static function getPath($id) {
        $db = Db::getConnection();
        $result = $db->prepare('SELECT path,name FROM folder WHERE id = :id');
        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch();
        $folder['path'] = $row['path'];
        $folder['name'] = $row['name'];
        return $folder;
    }
    public static function getFolderId($path) {
        $db = Db::getConnection();
        $result = $db->prepare('SELECT id,parent_id FROM folder WHERE path = :path');
        $result->bindParam(':path', $path, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch();
        $folder['id'] = $row['id'];
        $folder['parent_id'] = $row['parent_id'];

        //die();
        return $folder;
    }
    public static function createFolder($parent_id) {
       // $user_id = $_SESSION['id'];
        $user_id = 1;
        $path_name = self::getPath($parent_id);
        $path=$path_name['path']."/".$path_name['name']."-15";
        $name = $path_name['name']."-15";
        mkdir($path, 0700);
        $db = Db::getConnection();
        $sql = 'INSERT INTO folder (parent_id,user_id,name,path)'
            . 'VALUES (:parent_id, :user_id, :name, :path)';
        $result = $db->prepare($sql);
        $result->bindParam(':parent_id', $parent_id, PDO::PARAM_STR);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':path', $path, PDO::PARAM_STR);
        $result->execute();
    }

}
function showfolder($dir){
    $list = scandir($dir);
    if (is_array($list)) {
        $list = array_diff($list, array('.', '..'));
        if ($list) {
            MainModel::$folder .= '<ul>';
            foreach ($list as $name) {
                $path = $dir . '/' . $name;
                if (is_dir($path)){
                    $folder = MainModel::getFolderId($path);
                    MainModel::$folder .= "<li><div data-folid ='".$folder['id']."' data-perid ='".$folder['parent_id']."' id='fold' >".htmlspecialchars($name)."</div>";
                    showfolder($path);
                    MainModel::$folder .= '</li>';
                }
                else break;
            }

            MainModel::$folder .= '</ul>';

        }
    }
    return MainModel::$folder;
}