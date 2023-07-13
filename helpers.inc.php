<?php

if (!defined('IN_INDEX')) { exit("This file can only be included in index.php."); }


function get_domain(): string {
    $domena = preg_replace('/[^a-zA-Z0-9\.]/', '', $_SERVER['HTTP_HOST']);
    return $domena;
}

class DB {
    private static $dbh = null; // tutaj będziemy przechowywać obiekt PDO

    public static function getInstance(): PDO {

        // Jeśli w zmiennej statycznej self::$dbh nie mamy jeszcze utworzonego połączenia do bazy danych to je tworzymy.
        if (!self::$dbh) {
            /*
             * Instrukcja try-catch występuje w wielu językach programowania. Służy ona do przechwycenia wyjątku (błędu)
             * rzuconego przez kod znajdujący się w środku bloku "try" oraz odpowiednią reakcję na rzucony wyjątek
             * w bloku "catch".
             */
            try {

                self::$dbh = new PDO(
                    'mysql:host=' . CONFIG['db_host'] . ';dbname=' . CONFIG['db_name'] . ';charset=utf8mb4',
                    CONFIG['db_user'],
                    CONFIG['db_password']
                );
                self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                exit("Cannot connect to the database: " . $e->getMessage());
            }
        }
        // Zwracamy obiekt PDO, reprezentujący połączenie z bazą danych.
        return self::$dbh;
    }
}


class TwigHelper {
    private static $twig = null; // tutaj będziemy przechowywać obiekt Twiga
    private static $msg = []; // tutaj będziemy przechowywać komunikaty do wyświetlenia w szablonie base.html

    public static function getInstance(): \Twig\Environment {
        // Jeśli w zmiennej statycznej self::$twig nie mamy jeszcze utworzonego obiektu Twiga to go tworzymy.
        if (!self::$twig) {
            /*
             * Inicjalizujemy obiekt biblioteki Twig zgodnie z dokumentacją ze strony:
             * https://twig.symfony.com/doc/3.x/api.html
             * Obiekt przypisujemy do zmiennej statycznej self::$twig. Dzięki temu w dowolnym miejscu kodu możemy
             * uzyskać do niego dostęp za pomocą TwigSingleton::getInstance().
             */
            $twig_loader = new \Twig\Loader\FilesystemLoader('templates');
            self::$twig = new \Twig\Environment($twig_loader);
        }
        // Zwracamy obiekt Twiga
        return self::$twig;
    }

    /*
     * Klasa TwigHelper dostarczy nam jeszcze jednej przydatnej metody. W tablicy self::$msg trzymane będą
     * komunikaty, które będzie wyświetlać szablon base.html nad treścią podstrony. Możesz dodawać dowolne komunikaty
     * za pomocą TwigHelper::getInstance()->addMsg($text, $type). Przykłady użycia znajdziesz w pliku main.php.
     * Jako typ możesz przekazać ciąg tekstowy: success, info, error. Typ decyduje o kolorze tła komunikatu.
     */
    public static function addMsg(string $text, string $type): void {
        self::$msg[] = [
            'text' => $text,
            'type' => $type
        ];
    }

    // Metoda zwraca całą tablicę z komunikatami - będzie jej używać szablon base.html.
    public static function getMsg(): array {
        return self::$msg;
    }
}




// Konfigurujemy Twiga tak, aby w każdym szablonie była dostępna stała CONFIG, funkcja get_domain(),
// oraz metoda TwigHelper::getMsg (pod nazwą get_msg), bez konieczności ręcznego przekazywania ich podczas renderowania.
TwigHelper::getInstance()->addGlobal('CONFIG', CONFIG);
TwigHelper::getInstance()->addFunction(new \Twig\TwigFunction('get_domain', 'get_domain'));
TwigHelper::getInstance()->addFunction(new \Twig\TwigFunction('get_msg', 'TwigHelper::getMsg'));
