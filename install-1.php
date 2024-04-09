<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, $charactersLength - 1);
        $randomString .= $characters[$randomIndex];
    }
    return $randomString;
}

function downloadComposer() {
    $composerURL = 'https://getcomposer.org/composer.phar';
    $composerFilename = 'composer.phar';
    $downloadPath = __DIR__ . '/' . $composerFilename;
    $content = file_get_contents($composerURL);
    if ($content === false) {
        echo "Failed to download Composer.\n";
        exit(1);
    }
    file_put_contents($downloadPath, $content);
    return $downloadPath;
}

function installDependencies() {
    echo "Installing Composer dependencies...\n";
    $output = shell_exec('php composer.phar install');
    echo $output;
}

function removeComposer() {
    echo "Removing Composer...\n";
    unlink('composer.phar');
}

if (is_dir("./vendor"))
    rmdir("./vendor");

echo "Downloading Composer...\n";
$composerPath = downloadComposer();

echo "Composer downloaded successfully.\n";
echo "Installing Composer dependencies...\n";

installDependencies();

removeComposer();

echo "Composer removed successfully.\n";

echo "enter database password: ";
$dbpw = fgets(STDIN);
$dbpw = trim($dbpw);

echo "changing the docker compose file!\n";
$dcompose = file_get_contents("docker-compose.yml");
$dcompose = str_replace('--DBPW--',$dbpw,$dcompose);
file_put_contents("docker-compose.yml", $dcompose);

echo "changing config.php file!\n";
$conf = file_get_contents("system/config.php");
$conf = str_replace('--DBPW--',$dbpw,$conf);
$conf = str_replace('--SALT--',generateRandomString(64),$conf);
file_put_contents("system/config.php",$conf);


mkdir("database");