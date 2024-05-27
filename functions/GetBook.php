<?php

namespace functions;
require_once('JsonBookLoader.php');

class GetBook
{
    private $jsonFile;
    private $historyFile;
    private $jsonLoader;

    /**
     * @param $jsonFile
     * @param $historyFile
     */
    public function __construct($jsonFile = 'books.json', $historyFile = 'history.txt')
    {
        $this->jsonLoader = new JsonBookLoader($jsonFile);
        $this->historyFile = $historyFile;
    }

    // recuperer les livres depuis le fichier json, les afficher dans la console et enregistrer l'historique
    public function getAllBooks()
    {
        $books = $this->jsonLoader->loadBooksFromJson();
        $this->displayBooks($books);
        $this->logToHistory();
    }

    // recuperer un seul livre via son nom ou son id depuis le fichier json, l'afficher dans la console et enregistrer l'historique
    public function getOneBook($identifier)
    {
        $books = $this->jsonLoader->loadBooksFromJson();
        foreach ($books as $book) {
            if ($book['id'] == $identifier || $book['name'] == $identifier) {
                $this->displaySingleBook($book);
                $this->logToHistory($book);
                return;
            }
        }
        echo "Le livre d'identifiant $identifier n'a pas été enregisté ! ";
    }

    private function displayBooks($books)
    {
        if (empty($books)) {
            echo "Aucun livre trouvé.\n";
            return;
        }

        foreach ($books as $book) {
            echo "Titre : " . $book['name'] . "\n";
            echo "Description : " . $book['description'] . "\n";
            if($book['is_available'] == "1"){
                echo "Disponible en stock  : Oui\n";
            }else{
                echo "Disponible en stock  : Non\n";
            }
            echo "------------------------\n";
        }
    }

    private function displaySingleBook($book)
    {
        echo "Identifiant : " . $book['id'] . "\n";
        echo "Titre : " . $book['name'] . "\n";
        echo "Description : " . $book['description'] . "\n";
        if($book['is_available'] == "1"){
            echo "Disponible en stock  : Oui\n";
        }else{
            echo "Disponible en stock  : Non\n";
        }
        echo "------------------------\n";
    }

    private function logToHistory($book = null)
    {
        date_default_timezone_set('Europe/Paris');
        $logMessage = 'Vous avez listé tous les livres disponibles le ' . date('d/m/Y H:i:s') . PHP_EOL;

        if (is_null($book)) {
            $messageToLog = $logMessage;
        } else {
            $singleMessage = 'Vous avez visualisé le livre: ' . $book['name'] . ' (ID: ' . $book['id'] . ') à la date du ' . date('d/m/Y H:i:s') . PHP_EOL;
            $messageToLog = $singleMessage;
        }

        if (file_put_contents($this->historyFile, $messageToLog, FILE_APPEND) === false) {
            throw new Exception('Erreur lors de l\'enregistrement de l\'historique.');
        }
    }

}