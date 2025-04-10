<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$sourceFile = __DIR__ . '/files-to-copy.php';
$parentDir = './';
$logFile = __DIR__ . '/directories_log.txt';
$excludedDirs = ['.git', 'node_modules', 'vendor'];

function copyFileToNewDirs($sourceFile, $parentDir, $logFile, $excludedDirs) {
    if (!file_exists($sourceFile)) {
        echo "Fichier source introuvable : $sourceFile\n";
        return;
    }

    $scannedDirs = file_exists($logFile) ? file($logFile, FILE_IGNORE_NEW_LINES) : [];
    $files = scandir($parentDir);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $currentPath = rtrim($parentDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;

        if (is_dir($currentPath)) {
            $dirName = basename($currentPath);

            // Protection stricte : ignorer tout ce qui est dans ou sous .git
            if (in_array($dirName, $excludedDirs) || strpos($currentPath, DIRECTORY_SEPARATOR . '.git') !== false) {
                continue;
            }

            if (!in_array($currentPath, $scannedDirs)) {
                echo "📁 Nouveau répertoire trouvé : $currentPath\n";
                $destinationPath = $currentPath . DIRECTORY_SEPARATOR . 'index.php';

                if (copy($sourceFile, $destinationPath)) {
                    echo "✅ Copié dans : $destinationPath\n";
                } else {
                    echo "❌ Échec dans : $destinationPath\n";
                    print_r(error_get_last());
                }

                file_put_contents($logFile, $currentPath . PHP_EOL, FILE_APPEND);
            }

            // Recursion
            copyFileToNewDirs($sourceFile, $currentPath, $logFile, $excludedDirs);
        }
    }
}

copyFileToNewDirs($sourceFile, $parentDir, $logFile, $excludedDirs);
