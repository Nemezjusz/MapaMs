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

        header("Location: http://localhost/mapams/generated_links?link=" . $link_id);
        
        
    }
}

/*
 * Pobieramy wszystkie wiersze z tabeli "test". Po wykonaniu zapytania możemy skorzystać z metody "fetchAll",
 * aby pobrać wszystkie wyniki z bazy danych na raz, w formie tablicy, a następnie przypisać je do zmiennej $example1.
 * Stała "PDO::FETCH_ASSOC", podana w argumencie, powoduje, że każdy z pobranych wierszy będzie tablicą asocjacyjną,
 * gdzie kluczem jest nazwa kolumny z bazy danych. Mamy więc tutaj do czynienia z tablicą dwuwymiarową (tablica tablic),
 * gdzie pierwszy poziom to lista wierszy, a drugi to kolumny danego wiersza.
 */

 if (isset($_GET['link'])) {

    $stmt = DB::getInstance()->prepare("SELECT id, loc_x, loc_y, `desc`, author_id, author_name, group_id FROM pins WHERE id = :link_id");
    $stmt->execute([':link_id' => intval($_GET['link'])]);
    $example1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} else {
    $stmt = DB::getInstance()->prepare("SELECT id, loc_x, loc_y, `desc`, author_id, author_name, group_id FROM pins");
    $stmt->execute();
    $example1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


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



/*
 * Przykład 3: dane z bazy psują (kolorują) tabelę za pomocą niechcianego kodu JS
 *
 * W tym przypadku wykonujemy zapytanie, które pobierze osobę o imieniu Jim. Mamy pewność, że jest to bezpieczna nazwa,
 * statyczny ciąg, więc nie musimy używać bindowania tylko wprost wstawiamy to imię do zapytania.
 *
 * Dlaczego tabela jest pomarańczowa jeśli w jej kodzie nigdzie takie stylowanie nie występuje?
 * Zajrzyj do bazy danych oraz w kod źródłowy w przeglądarce. Okazuje się, że do nazwiska Beam wstrzyknięto złośliwy kod
 * JavaScript, który koloruje tabelę. Jest to typowy przykład ataku XSS.
 *
 * Zobacz na poprzedni (drugi) przykład w źródle strony w przeglądarce - wszystkie znaki specjalne zostały zmienione
 * na tak zwane encje HTML, nieinterpretowane przez przeglądarkę:
 * <td>Beam&lt;script&gt;$(&quot;#my-table3&quot;).css(&quot;background-color&quot;, &quot;orange&quot;);&lt;/script&gt;</td>
 *
 * Natomiast w tym przykładzie, w źródle strony w przeglądarce, widać czysty kod JS w bloku script:
 * <td>Beam<script>$("#my-table3").css("background-color", "orange");</script></td>
 *
 * Otwórz plik main.html i porównaj jak wyświetlana jest kolumna nazwiska w drugim i trzecim przykładzie:
 * <td>{{ row.surname }}</td>
 * <td>{{ row.surname|raw }}</td>
 *
 * Różnica polega na użyciu filtra "raw" pochodzącego z biblioteki Twig.
 * Dane trafiające do bazy mogą zawierać złośliwy kod. Po stronie programisty leży zadbanie o to, aby po ich pobraniu,
 * wyświetliły się w przeglądarce w taki sposób, aby nie zagrażać użytkownikom strony. Twig domyślnie ma włączone
 * escapowanie - tj. dane wstawione w szablonie w podwójnych nawiasach dziubkowych przechodzą przez filtr, który
 * zamienia znaki specjalne na tak zwane encje HTML, czyli ciągi znaków (kody), które przeglądarka zamienia sobie
 * na określony symbol. W ten sposób złośliwy kod nie zostanie wykonany, a jedynie wypisany na stronie.
 * Czasem jednak nie chcemy, aby filtrowanie zadziałało. Wówczas możemy wyłączyć filtrowanie globalnie w konfiguracji
 * Twiga, co nie jest zalecane, lub zrobić to lokalnie tylko w wybranym miejscu za pomocą filtra "raw".
 *
 * Śmiało edytuj kod w pliku main.html i usuń filtr |raw. Teraz blok script powinien zostać wypisany w tabeli,
 * a nie zinterpretowany przez przeglądarkę.
 *
 * Opis wszystkich dostępnych filtrów Twiga znajdziesz na stronie:
 * https://twig.symfony.com/doc/3.x/filters/index.html
*/

// $stmt = DB::getInstance()->prepare("SELECT id, `name`, surname FROM test WHERE `name` = 'Jim'");
// $stmt->execute();
// $example3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
 * Wyrenderowanie podstrony z przekazaniem do niej tablic wynikowych z trzech przykładów.
 */
print TwigHelper::getInstance()->render('main.html', [
    'example1' => $example1,
]);
