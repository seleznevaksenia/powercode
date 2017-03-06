<?php

class MainModel
{   static public $folder ="";
    public static function show($id) {
        $dir = ROOT . "/upload/$id";
        $folder = self::getFolderId($dir);
        return "<ul><li><div data-folid ='".$folder['id']."'data-perid ='".$folder['parent_id']."' id='mainfold' >".$id.showfolder($dir)."</div></li></ul>";

    }
    public static function getPath($id) {
        $db = Db::getConnection();
        $result = $db->prepare('SELECT * FROM folder WHERE id = :id');
        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch();
        $folder['path'] = $row['path'];
        $folder['name'] = $row['name'];
        $folder['status'] = $row['status'];
        $folder['parent_id'] = $row['parent_id'];
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
        $name = date("j-m-y_G-i-s");
        $path_name = self::getPath($parent_id);
        $path=$path_name['path']."/".$name;
        mkdir($path, 0700,true);
        $db = Db::getConnection();

        $sql = "UPDATE folder SET status = 1  WHERE id = :id";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $parent_id, PDO::PARAM_STR);
        $result->execute();


        $sql = 'INSERT INTO folder (parent_id,user_id,name,path)'
            . 'VALUES (:parent_id, :user_id, :name, :path)';
        $result = $db->prepare($sql);
        $result->bindParam(':parent_id', $parent_id, PDO::PARAM_STR);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':path', $path, PDO::PARAM_STR);
        $result->execute();
    }
    public static function deleteFolder($id) {
        $child = self::getPath($id);
        /*foreach (glob("*.jpg") as $filename) {
            unlink($filename);
        }*/
        $db = Db::getConnection();
        $sql = 'DELETE FROM folder WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->execute();
        if ($child['status'] == 1) {
            $massive = self::findchild($id);
            foreach ($massive as $item) {
                self::deleteFolder($item['id']);
            }
            rmdir($child['path']);
        }
        else{

            rmdir($child['path']);

            if(self::countChild($child['parent_id'])== 1) {
                $db = Db::getConnection();
                $sql = "UPDATE folder SET status = 0  WHERE id = :id";
                $result = $db->prepare($sql);
                $result->bindParam(':id', $child['parent_id'], PDO::PARAM_STR);
                $result->execute();
            }

        }

        return true;
    }

    public static function renameFolder($id,$name) {
        // $user_id = $_SESSION['id'];
        $sql = self::getPath($id);
        $old = $sql['path'];
        $parent = $sql['parent_id'];
        $sql2 = self::getPath($parent);
        $new1 = $sql2['path'].'/'.$name;

        rename($old, $new1);
        if(self::countChild($id)==0) {

            $db = Db::getConnection();
            $sql = "UPDATE folder SET name = :name,path = :path  WHERE id = :id";
            $result = $db->prepare($sql);
            $result->bindParam(':name', $name, PDO::PARAM_STR);
            $result->bindParam(':path', $new1, PDO::PARAM_STR);
            $result->bindParam(':id', $id, PDO::PARAM_STR);
            $result->execute();
        }

    }




    public static function findchild($id)
    {
        $db = Db::getConnection();
        $result = $db->prepare('SELECT id FROM folder WHERE parent_id = :parent_id');
        $result->bindParam(':parent_id', $id, PDO::PARAM_INT);
        $result->execute();
        $i = 0;
        while ($row = $result->fetch()) {
            $folder[$i]['id'] = $row['id'];
            $i++;
        }

        return $folder;
    }

    public static function countChild($parent_id)
    {
        $db = Db::getConnection();
        $result = $db->prepare('SELECT COUNT(*) FROM folder WHERE parent_id = :parent_id');
        $result->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
        $result->execute();
        $row = $result->fetch();
        return $row['COUNT(*)'];
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
                    $folder_path = MainModel::getFolderId($path);
                    MainModel::$folder .= "<li><div data-folid ='".$folder_path['id']."' data-perid ='".$folder_path['parent_id']."' id='fold' >".htmlspecialchars($name)."</div>";
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