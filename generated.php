<?php

if (!defined('IN_INDEX')) { exit("This file can only be included in index.php."); }

if (isset($_GET['link'])) {

    $stmt = DB::getInstance()->prepare("SELECT link_id, loc_x, loc_y FROM links WHERE link_id = :link_id");
    $stmt->execute([':link_id' => ($_GET['link'])]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} else {
    $rows = 0;
}

print TwigHelper::getInstance()->render('generated.html', [
    'rows' => $rows,
]);