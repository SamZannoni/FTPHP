<?php

// Active les erreurs PHP pour debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fichier source à copier
$sourceFile = __DIR__ . '/files-to-copy.php';

// Répertoire de départ
$parentDir = './';

// Fichier de log pour les répertoires déjà traités
$logFile = __DIR__ . '/directories_log.txt';

// Répertoires à exclure
$excludedDirs = ['.git', 'node_modules', 'vendor', '__MACOSX'];

function copyFileToNewDirs($sourceFile, $parentDir, $logFile, $excludedDirs) {
    // Vérifie que le fichier source existe
    if (!file_exists($sourceFile)) {
        echo "Fichier source introuvable : $sourceFile\n";
        return;
    }

    // Lit les répertoires déjà scannés
    $scannedDirs = file_exists($logFile) ? file($logFile, FILE_IGNORE_NEW_LINES) : [];

    // Scan du dossier actuel
    $files = scandir($parentDir);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $currentPath = rtrim($parentDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;

        if (is_dir($currentPath)) {
            $dirName = basename($currentPath);

            // Ignore les dossiers exclus
            if (in_array($dirName, $excludedDirs)) {
                continue;
            }

            // Si ce dossier n’a pas encore été traité
            if (!in_array($currentPath, $scannedDirs)) {
                echo "📁 Nouveau répertoire trouvé : $currentPath\n";

                $destinationPath = $currentPath . DIRECTORY_SEPARATOR . 'index.php';

                // Copie du fichier
                if (copy($sourceFile, $destinationPath)) {
                    echo "✅ Fichier copié dans : $destinationPath\n";
                } else {
                    echo "❌ Échec de la copie dans : $destinationPath\n";
                    print_r(error_get_last()); // Affiche l’erreur système
                }

                // Ajoute le dossier au log
                file_put_contents($logFile, $currentPath . PHP_EOL, FILE_APPEND);
            }

            // Appel récursif pour les sous-dossiers
            copyFileToNewDirs($sourceFile, $currentPath, $logFile, $excludedDirs);
        }
    }
}

// Appel de la fonction principale
copyFileToNewDirs($sourceFile, $parentDir, $logFile, $excludedDirs);

?>
