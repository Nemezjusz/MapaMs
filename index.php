<?php

if (!isset($_SESSION)) {
    session_start();  
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['postdata'] = $_POST;
    unset($_POST);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
    }
    
if (@$_SESSION['postdata']){
    $_POST=$_SESSION['postdata'];
    unset($_SESSION['postdata']);
}

ini_set('display_errors', 1);
error_reporting(E_ALL);


define("IN_INDEX", true);


require __DIR__ . '/vendor/autoload.php';



include("config.inc.php");


include("helpers.inc.php");


$allowed_pages = ['main', 'generated'];

if (isset($_GET['page'])
    && $_GET['page']
    && in_array($_GET['page'], $allowed_pages)
    && file_exists($_GET['page'] . '.php')
) {
    // Użytkownik podał prawidłową nazwę podstrony.
    include($_GET['page'] . '.php');
} elseif (!isset($_GET['page'])) {
    // Użytkownik nie przekazał żadnej nazwy podstrony.
    include('main.php');
} else {
    // Użytkownik podał nieprawidłową nazwę podstrony. Następuje dodanie komunikatu błędu, który wyświetli się w ramce.
    // Więcej informacji na temat wyświetlania komunikatów znajdziesz w pliku helpers.inc.php.
    TwigHelper::addMsg('Page "' . $_GET['page'] . '" not found.', 'error');
    // Renderujemy tylko główny szablon bez podstrony.
    print TwigHelper::getInstance()->render('main.html');
}
