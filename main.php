<?php

if (!defined('IN_INDEX')) { exit("This file can only be included in index.php."); }


if (isset($_POST['name']) && isset($_POST['description']) && isset($_COOKIE['loc_x']) && isset($_COOKIE['loc_y'])) {
    if ($_POST['submit'] == "submit"){
        
        $description = $_POST['description'];
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
    elseif ($_POST['submit'] == "link"){
        
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
}



// use myPHPnotes\Microsoft\Auth;
// use myPHPnotes\Microsoft\Handlers\Session;
// use myPHPnotes\Microsoft\Models\User;

// if (isset($_GET['code'])){
//     $auth = new Auth(Session::get("tenant_id"), Session::get("client_id"), Session::get("client_secret"), Session::get("redirect_uri"), Session::get("scopes"));
//     $tokens = $auth->getToken($_REQUEST['code']);
//     $accessToken = $tokens->access_token;
//     $auth->setAccessToken($accessToken);
//     $user = new User;
    
//     $username = $user->data->getDisplayName();
//     $user_mail = $user->data->getUserPrincipalName() ;
    
// }
// else{
//     $user_mail = "";
//     $username = "";
// }

/*
 * Przykład 2: pobranie z bazy danych tylko osób o wskazanym imieniu i powiększenie wszystkich liter nazwiska
 *
 * Na początku $example2 jest pustą tablicą. Jeżeli otrzymaliśmy z formularza pole "search" to wykonujemy zapytanie,
 * które szuka rekordów z podanym imieniem. Na końcu, za pomocą prostej pętli while i metody "fetch" pobieramy każdy
 * rekord oddzielnie. Każdy wiersz trafia do tablicy $row. Specjalna stała PDO::FETCH_ASSOC podana jako argument
 * powoduje, że każdy wiersz w pętli zostanie zwrócony jako tablica asocjacyjna, gdzie klucze będą nazwami kolumn.
 *
 * Przed dodaniem wiersza do tablicy wynikowej $example2, korzystamy z funkcji "strtoupper", aby zamienić w nazwisku
 * wszystkie małe litery na wielkie.
*/

// $example2 = [];

// if (isset($_POST['search'])) {
//     $stmt = DB::getInstance()->prepare("SELECT id, `name`, surname FROM test WHERE `name` = :search");
//     $stmt->execute([':search' => $_POST['search']]);
//     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//         $row['surname'] = strtoupper($row['surname']);
//         $example2[] = $row;
//     }
// }

$provider = new Stevenmaguire\OAuth2\Client\Provider\Microsoft([
    'clientId'                  => 'd0bc29af-27dc-4483-b433-45bb1dd47177',
    'clientSecret'              => '14H8Q~Yauss75FDfO_14aZsuKU.JrFd2621onbjU',
    'redirectUri'               => 'http://localhost/mapams/callback.php',
]);

if (isset($_SESSION['token'])){
    //$user = unserialize($_SESSION['user']);
    
    $token = unserialize($_SESSION['token']);
    $user =  $provider->getResourceOwner($token);
    $username = $user->getFirstname();


} else {
    $username = "";
}



/*
 * Wyrenderowanie podstrony z przekazaniem do niej tablic wynikowych z trzech przykładów.
 */

 $stmt = DB::getInstance()->prepare("SELECT id, loc_x, loc_y, `desc`, author_id, author_name, group_id FROM pins");
 $stmt->execute();
 $example1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

 print TwigHelper::getInstance()->render('main.html', [
    'example1' => $example1,
    'username' => $username,
]);
