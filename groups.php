<?php


if ($_POST['submit'] == "join"){
    
    $groups_code = $_POST['code'];

    //user -----> groups
    $stmt = DB::getInstance()->prepare("INSERT INTO groups (user_id) VALUES (:user_id) WHERE group_code = :code");
    $stmt->execute([
        ':user_id' => 1, //!!!!!!!
        ':code' => $group_code,
    ]);

        $_POST = array();

    
} elseif ($_POST['submit'] == "create"){
    // new group ---> groups
    
    $groups_name = $_POST['name'];
    $alph = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $group_code='';
    
    for($i=0;$i<4;$i++){
        $group_code .= $alph[rand(0, 35)];
    }

    $stmt = DB::getInstance()->prepare("INSERT INTO groups (group_name, group_code) VALUES (:group_name, :group_code)");
    $stmt->execute([
        ':group_name' => $group_code,
        ':group_code' => $groups_name,
        
    ]);
    $_POST = array();

    header("Location: http://192.168.0.18/mapams");
    die();
    
    
}
