<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);


define("IN_INDEX", true);


require __DIR__ . '/vendor/autoload.php';


include("config.inc.php");


include("helpers.inc.php");

/*
 * Wybierając daną pozycję w górnym menu, użytkownikowi powinniśmy pokazać (zaincludować) treść wybranego pliku
 * (np. main.php, guestbook.php). Zobacz co się stanie kiedy w menu wybierzesz inną podstronę, np. Guestbook.
 * Interesujące jest to, co widać w pasku adresu przeglądarki, a znajduje się tam ciąg index.php?page=guestbook.
 *
 * Już to wiesz, ale przypomnijmy, że wybrane zmienne możemy przekazywać od użytkownika do strony za pomocą parametrów
 * metody GET. Każde "zwykłe" wczytanie strony jest zapytaniem HTTP typu GET, możesz to sprawdzić otwierając konsolę,
 * a w niej narzędzie sieci. Zapytania GET mogą nieść dane od użytkownika w adresie strony. Do adresu dopisuje się
 * zmienne oddzielając je znakiem &, wyjątek stanowi tylko pierwsza zmienna, przed którą stawia się znak zapytania.
 *
 * Zauważ, że w pliku templates/base.html, do adresu podstrony Guestbook w navbarze celowo w zmiennej przekazaliśmy
 * o jaką stronę nam chodzi. Tak więc wczytujemy index.php, ale mówimy mu w zmiennej "page", że chodzi nam
 * o "guestbook".
 * Wiemy już, że użytkownik przekaże nam w zmiennej "page" (lub nie) jaką podstronę chce otrzymać (a dokładnie plik
 * o podanej przez niego nazwie i rozszerzeniu .php, np. guestbook.php). Nie byłoby to zbyt bezpieczne, gdyby użytkownik
 * mógł nam przekazać dowolną nazwę strony, a my wczytalibyśmy ją bez sprawdzenia. Zastanów się co by się stało gdyby
 * użytkownik przekazał index.php?page=index. Stworzyłoby to nieskończone wczytanie rekurencyjne, które skutecznie
 * "posadziłoby" stronę (możesz to później przetestować zmieniając kod) :)))
 *
 * Z tego powodu ręcznie definiujemy tablicę dozwolonych nazw ($allowed_pages). Następnie musimy sprawdzić
 * czy użytkownik przekazał nam zmienną "page" i czy jej wartość znajduje się w tablicy. Dostęp do zmiennych GET można
 * uzyskać za pomocą tablicy superglobalnej $_GET.
 *
 * W warunku "if" pojawiły się funkcje "isset", "in_array" oraz "file_exists".
 *
 * Za pomocą funkcji "isset" sprawdzamy, czy zmienna $_GET['page'] (a dokładnie element tablicy) istnieje. Zawsze
 * sprawdzaj czy zmienna istnieje jeżeli nie masz pewności, że została ona zadeklarowana w innym pliku lub jeśli
 * pochodzi z zewnątrz (od użytkownika).
 *
 * Za pomocą funkcji "in_array" sprawdzamy czy w tablicy istnieje dany element, a dokładnie czy podana przez użytkownika
 * strona znajduje się na liście stron dozwolonych,
 *
 * Następnie sprawdzamy czy taki plik z rozszerzeniem php istnieje za pomocą "file_exists" i jeśli tak to go wczytujemy.
 *
 * Domyślnie wczytujemy main.php w przypadku kiedy użytkownik nie przekaże nazwy podstrony.
 *
 * Jeśli użytkownik przekazał nieprawidłową nazwę podstrony to wyświetlamy komunikat błędu bez wczytywania żadnej
 * podstrony.
 *
 * Opis wszystkich tablic superglobalnych w PHP znajdziesz na stronie:
 * https://www.php.net/manual/en/language.variables.superglobals.php
 */

$allowed_pages = ['main'];

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
