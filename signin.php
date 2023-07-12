<?php
require "vendor/autoload.php";
use myPHPnotes\Microsoft\Auth;
$tenant = "common";
$client_id = "d0bc29af-27dc-4483-b433-45bb1dd47177";
$client_secret = "14H8Q~Yauss75FDfO_14aZsuKU.JrFd2621onbjU";
$callback = "http://localhost/mapams";
$scopes = ["User.Read"];
$microsoft = new Auth($tenant, $client_id, $client_secret,$callback, $scopes);
header("location: " . $microsoft->getAuthUrl());