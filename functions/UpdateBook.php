<?php

namespace functions;

use Exception;
require_once('JsonBookLoader.php');

class UpdateBook
{

    private $jsonLoader;

    public function __construct($jsonFile = 'books.json')
    {
        $this->jsonLoader = new JsonBookLoader($jsonFile);
    }

    public function updateBook($bookId, $newName, $newDescription, $newAvailability)
    {
        // on recuperer tous les livres du json
        $books = $this->jsonLoader->loadBooksFromJson();

        // parcourir le tableau de tous les livres
        // si on trouve l'id recherché, on modifie les valeurs des colonnes
        // si la nouvelle valeur est vide, on garde l'ancienne, sinon on la remplace
        $found = false;
        foreach ($books as &$book) {
            if ($book['id'] == $bookId) {
                if (!empty($newName) && $newName !== $book['name']) {
                    $this->logToHistory('name', $book['name'], $newName, $bookId);
                    $book['name'] = $newName;
                }
                if (!empty($newDescription) && $newDescription !== $book['description']) {
                    $this->logToHistory('description', $book['description'], $newDescription, $bookId);
                    $book['description'] = $newDescription;
                }
                if (!empty($newAvailability) && $newAvailability !== $book['is_available']) {
                    $this->logToHistory('is_available', $book['is_available'], $newAvailability, $bookId);
                    $book['is_available'] = $newAvailability;
                }
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new Exception('Livre avec l\'ID ' . $bookId . ' non trouvé.');        // l'id n'a pas été trouvé
        }

        $this->saveBooksToJson($books);
    }


    private function saveBooksToJson($books)
    {
        $jsonString = json_encode($books, JSON_PRETTY_PRINT);
        if ($jsonString === false) {
            throw new Exception('Erreur lors de l\'encodage JSON.');
        }

        if (file_put_contents($this->jsonLoader->jsonFile, $jsonString) === false) {
            throw new Exception('Erreur lors de l\'enregistrement du fichier JSON.');
        }
    }

    // enregistrer l'historique en precisant la colonne qui a été modifiée
    public function logToHistory($columnName, $oldValue, $newValue, $bookId)
    {
        date_default_timezone_set('Europe/Paris');
        $logMessage = 'Vous avez modifié le livre d\'id "' . $bookId . '", en modifiant la colonne "' . $columnName . '" de "' . $oldValue . '" à "' . $newValue . '" à la date du ' . date('d/m/Y H:i:s') . PHP_EOL;

        if (file_put_contents('history.txt', $logMessage, FILE_APPEND) === false) {
            throw new Exception('Erreur lors de l\'enregistrement de l\'historique.');
        }
    }

}
