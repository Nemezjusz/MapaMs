<?php

if (!defined('IN_INDEX')) { exit("This file can only be included in index.php."); }


function get_domain(): string {
    $domena = preg_replace('/[^a-zA-Z0-9\.]/', '', $_SERVER['HTTP_HOST']);
    return $domena;
}

class DB {
    private static $dbh = null; 

    public static function getInstance(): PDO {

        if (!self::$dbh) {
           
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
        return self::$dbh;
    }
}


class TwigHelper {
    private static $twig = null; 
    private static $msg = []; 

    public static function getInstance(): \Twig\Environment {
        if (!self::$twig) {
          
            $twig_loader = new \Twig\Loader\FilesystemLoader('templates');
            self::$twig = new \Twig\Environment($twig_loader);
        }
        return self::$twig;
    }
    public static function addMsg(string $text, string $type): void {
        self::$msg[] = [
            'text' => $text,
            'type' => $type
        ];
    }

    public static function getMsg(): array {
        return self::$msg;
    }
}


TwigHelper::getInstance()->addGlobal('CONFIG', CONFIG);
TwigHelper::getInstance()->addFunction(new \Twig\TwigFunction('get_domain', 'get_domain'));
TwigHelper::getInstance()->addFunction(new \Twig\TwigFunction('get_msg', 'TwigHelper::getMsg'));
