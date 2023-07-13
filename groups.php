<?php


if ($_POST['submit'] == "join"){
    
    $author_name = $_POST['name'];

    if (mb_strlen($author_name) >= 2 && mb_strlen($author_name) <= 50 && mb_strlen($description) >= 2 && mb_strlen($description) <= 100) {

        $stmt = DB::getInstance()->prepare("INSERT INTO pins (loc_x, loc_y, `desc`, author_name) VALUES (:loc_x, :loc_y, :descriptio, :author_name)");
        $stmt->execute([
            ':loc_x' => $_COOKIE['loc_x'],
            ':loc_y' => $_COOKIE['loc_y'],
            ':descriptio' => $_POST['description'],
            ':author_name' => $_POST['name'],
        ]);

        $_POST = array();
    //     TwigHelper::addMsg('Row has been added.', 'success');
    // } else {
    //     TwigHelper::addMsg('Incorrect data.', 'error');
    }
}
elseif ($_POST['submit'] == "create"){
    
    $alph = "0123456789abcdefghijklmnopqrstuvwxyz";
    $link_id='';
    for($i=0;$i<5;$i++){
        $link_id .= $alph[rand(0, 35)];
    }
    
    
    $stmt = DB::getInstance()->prepare("INSERT INTO links (link_id, loc_x, loc_y) VALUES (:link_id, :loc_x, :loc_y)");
    $stmt->execute([
        ':link_id' => $link_id,
        ':loc_x' => $_COOKIE['loc_x'],
        ':loc_y' => $_COOKIE['loc_y'],
    ]);
    $_POST = array();

    header("Location: http://192.168.0.18/mapams/generated?link=" . $link_id);
    die();
    
    
}
