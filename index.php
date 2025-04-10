<?php

// Chemin vers le fichier source que vous souhaitez copier
$sourceFile = __DIR__ . '/files-to-copy.php';  // Utilisation de __DIR__ pour obtenir un chemin absolu

// Chemin vers le répertoire parent où vous souhaitez scanner et ajouter le fichier
$parentDir = './';  // Répertoire parent où vous voulez commencer la recherche

// Fichier de log pour garder la trace des répertoires déjà scannés
$logFile = __DIR__ . '/directories_log.txt';  // Ce fichier contient la liste des répertoires déjà scannés

// Fonction pour copier le fichier dans tous les répertoires et sous-répertoires détectés
function copyFileToNewDirs($sourceFile, $parentDir, $logFile) {
    // Vérifier si le fichier source existe
    if (!file_exists($sourceFile)) {
        echo "Le fichier source n'existe pas : $sourceFile\n";
        return;
    }

    // Lire le fichier de log pour obtenir les répertoires déjà scannés
    $scannedDirs = file_exists($logFile) ? file($logFile, FILE_IGNORE_NEW_LINES) : [];

    // Ouvrir le répertoire parent
    $files = scandir($parentDir);

    // Parcourir chaque fichier/répertoire dans le répertoire parent
    foreach ($files as $file) {
        // Ignorer les répertoires '.' et '..'
        if ($file == '.' || $file == '..') {
            continue;
        }

        // Construire le chemin complet du fichier ou répertoire
        $currentPath = rtrim($parentDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;

        // Vérifier si c'est un répertoire
        if (is_dir($currentPath)) {
            // Vérifier si le répertoire n'a pas déjà été scanné
            if (!in_array($currentPath, $scannedDirs)) {
                // echo "Nouveau répertoire trouvé : $currentPath\n";

                // Définir le chemin de destination avec le nom "files.php"
                $destinationPath = rtrim($currentPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'index.php';

                // Copier le fichier dans le répertoire en tant que "files.php"
                if (copy($sourceFile, $destinationPath)) {
                    // echo "Le fichier a été copié dans : $destinationPath\n";
                } else {
                    // echo "Échec de la copie du fichier dans : $destinationPath\n";
                }

                // Ajouter ce répertoire au fichier de log
                file_put_contents($logFile, $currentPath . PHP_EOL, FILE_APPEND);
            }

            // Appeler récursivement la fonction pour les sous-répertoires
            copyFileToNewDirs($sourceFile, $currentPath, $logFile);
        }
    }
}

// Appeler la fonction pour copier le fichier dans les nouveaux répertoires
copyFileToNewDirs($sourceFile, $parentDir, $logFile);

?>




<?php require('header.php'); ?>

<div class="container">
  Ce serveur autohebergé a été conçu a l'occasion de la série "As long as you don’t steal we share", pour le workshop reto-gaming-pirate du 17-18 avril 2025 au 8-9 mai 2025.
  

</div>

<?php require('footer.php'); ?>
