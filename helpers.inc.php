<?php

if (!defined('IN_INDEX')) { exit("This file can only be included in index.php."); }

/*
 * Funkcja get_domain() nie przyjmuje żadnych argumentów i zwraca adres bieżącej domeny. Adres ten znajduje się
 * w zmiennej superglobalnej $_SERVER['HTTP_HOST']. Tablica $_SERVER zawiera wiele innych przydatnych zmiennych
 * środowiskowych, np. warto wiedzieć, że adres IP użytkownika znajduje się w $_SERVER['REMOTE_ADDR'].
 * Opis tablicy $_SERVER znajdziesz na stronie:
 * https://www.php.net/manual/en/reserved.variables.server.php
 */
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
                /*
                 * Tworzymy nowy obiekt wbudowanej w PHP klasy PDO, służącej do komunikacji z bazą danych, przekazując
                 * do jego konstruktora parametry połączenia do bazy danych. Obiekt PDO reprezentujący połączenie z bazą
                 * danych przypisujemy do zmiennej statycznej self::$dbh. Dzięki temu w dowolnym miejscu kodu możemy
                 * uzyskać połączenie do bazy danych za pomocą DB::getInstance().
                 * Za pomocą metody "setAttribute" ustawiamy na utworzonym obiekcie, aby PDO obsługiwało błędy za pomocą
                 * wyjątków (są tez inne tryby, na przykład zwracanie standardowych błędów PHP).
                 * PDO jest potężną biblioteką. Potrafi obsługiwać różne typy baz w uniwersalny sposób, dzięki temu
                 * zmiana oprogramowania serwera bazy danych nie musi oznaczać zmiany kodu/zapytań w PHP.
                 * Opis klasy PDO i jej metod znajdziesz na stronie: https://www.php.net/manual/en/class.pdo
                */
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

use Microsoft\Graph\Graph;
use Microsoft\Graph\Http;
use Microsoft\Graph\Model;
use GuzzleHttp\Client;

class GraphHelper {
    private static Client $tokenClient;
    private static string $clientId = '';
    private static string $tenantId = '';
    private static string $graphUserScopes = '';
    private static Graph $userClient;
    private static string $userToken;

    public static function initializeGraphForUserAuth(): void {
        GraphHelper::$tokenClient = new Client();
        GraphHelper::$clientId = $_ENV['CLIENT_ID'];
        GraphHelper::$tenantId = $_ENV['TENANT_ID'];
        GraphHelper::$graphUserScopes = $_ENV['GRAPH_USER_SCOPES'];
        GraphHelper::$userClient = new Graph();
    }

    public static function getUserToken(): string {
        // If we already have a user token, just return it
        // Tokens are valid for one hour, after that it needs to be refreshed
        if (isset(GraphHelper::$userToken)) {
            return GraphHelper::$userToken;
        }
    
        // https://learn.microsoft.com/azure/active-directory/develop/v2-oauth2-device-code
        $deviceCodeRequestUrl = 'https://login.microsoftonline.com/'.GraphHelper::$tenantId.'/oauth2/v2.0/devicecode';
        $tokenRequestUrl = 'https://login.microsoftonline.com/'.GraphHelper::$tenantId.'/oauth2/v2.0/token';
    
        // First POST to /devicecode
        $deviceCodeResponse = json_decode(GraphHelper::$tokenClient->post($deviceCodeRequestUrl, [
            'form_params' => [
                'client_id' => GraphHelper::$clientId,
                'scope' => GraphHelper::$graphUserScopes
            ]
        ])->getBody()->getContents());
        
        var_dump($deviceCodeResponse);
        // Display the user prompt
        print($deviceCodeResponse->user_code.PHP_EOL);
        header("Location: https://microsoft.com/devicelogin" . $deviceCodeResponse->user_code.PHP_EOL );    
        
    
        // Response also indicates how often to poll for completion
        // And gives a device code to send in the polling requests
        $interval = (int)$deviceCodeResponse->interval;
        $device_code = $deviceCodeResponse->device_code;
    
        // Do polling - if attempt times out the token endpoint
        // returns an error
        while (true) {
            sleep($interval);
    
            // POST to the /token endpoint
            $tokenResponse = GraphHelper::$tokenClient->post($tokenRequestUrl, [
                'form_params' => [
                    'client_id' => GraphHelper::$clientId,
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:device_code',
                    'device_code' => $device_code
                ],
                // These options are needed to enable getting
                // the response body from a 4xx response
                'http_errors' => false,
                'curl' => [
                    CURLOPT_FAILONERROR => false
                ]
            ]);
    
            if ($tokenResponse->getStatusCode() == 200) {
                // Return the access_token
                $responseBody = json_decode($tokenResponse->getBody()->getContents());
                GraphHelper::$userToken = $responseBody->access_token;
                return $responseBody->access_token;
            } else if ($tokenResponse->getStatusCode() == 400) {
                // Check the error in the response body
                $responseBody = json_decode($tokenResponse->getBody()->getContents());
                if (isset($responseBody->error)) {
                    $error = $responseBody->error;
                    // authorization_pending means we should keep polling
                    if (strcmp($error, 'authorization_pending') != 0) {
                        throw new Exception('Token endpoint returned '.$error, 100);
                    }
                }
            }
        }
    }

    public static function getUser(): Model\User {
        $token = GraphHelper::getUserToken();
        GraphHelper::$userClient->setAccessToken($token);
    
        return GraphHelper::$userClient->createRequest('GET', '/me?$select=displayName,mail,userPrincipalName')
                                       ->setReturnType(Model\User::class)
                                       ->execute();
    }

}


// Konfigurujemy Twiga tak, aby w każdym szablonie była dostępna stała CONFIG, funkcja get_domain(),
// oraz metoda TwigHelper::getMsg (pod nazwą get_msg), bez konieczności ręcznego przekazywania ich podczas renderowania.
TwigHelper::getInstance()->addGlobal('CONFIG', CONFIG);
TwigHelper::getInstance()->addFunction(new \Twig\TwigFunction('get_domain', 'get_domain'));
TwigHelper::getInstance()->addFunction(new \Twig\TwigFunction('get_msg', 'TwigHelper::getMsg'));
