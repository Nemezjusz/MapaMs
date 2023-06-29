<?php

if (!defined('IN_INDEX')) { exit("This file can only be included in index.php."); }

if (isset($_GET['link'])) {

    $stmt = DB::getInstance()->prepare("SELECT id, loc_x, loc_y, `desc`, author_id, author_name, group_id FROM pins WHERE id = :link_id");
    $stmt->execute([':link_id' => intval($_GET['link'])]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
}

print TwigHelper::getInstance()->render('generated_link.html', [
    'rows' => $rows,
]);