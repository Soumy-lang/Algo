<?php

namespace functions;

class DeleteBook
{
    public static function deleteFromJson($id, $jsonFilename, $historyFilename)
    {
        $jsonFilePath = __DIR__ . "/../$jsonFilename";
        $historyFilePath = __DIR__ . "/../$historyFilename";

        if (!file_exists($jsonFilePath)) {
            throw new \Exception('Le fichier JSON n\'existe pas.');
        }

        $currentJson = file_get_contents($jsonFilePath);
        if ($currentJson === false) {
            throw new \Exception('Erreur lors de la lecture du fichier JSON.');
        }

        $currentData = json_decode($currentJson, true);
        if ($currentData === null) {
            throw new \Exception('Erreur lors du décodage du fichier JSON.');
        }

        $found = false;
        foreach ($currentData as $key => $book) {
            if ($book['id'] == $id) {
                unset($currentData[$key]);
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new \Exception('Livre avec l\'ID ' . $id . ' non trouvé.');
        }

        $currentData = array_values($currentData); // Réindexe le tableau

        $jsonString = json_encode($currentData, JSON_PRETTY_PRINT);
        if ($jsonString === false) {
            throw new \Exception('Erreur lors de l\'encodage JSON.');
        }

        if (file_put_contents($jsonFilePath, $jsonString) === false) {
            throw new \Exception('Erreur lors de l\'enregistrement du fichier JSON.');
        }

        $logMessage = 'Livre d\'id ' . $id . ' a été supprimé';
        date_default_timezone_set('Europe/Paris');
        $logEntry = $logMessage . ' à ' . date('Y-m-d H:i:s') . PHP_EOL;
        if (file_put_contents($historyFilePath, $logEntry, FILE_APPEND) === false) {
            throw new \Exception('Erreur lors de l\'enregistrement de l\'historique.');
        }
    }

}