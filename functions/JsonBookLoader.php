<?php

namespace functions;

class JsonBookLoader
{
    public $jsonFile;

    public function __construct($jsonFile = 'books.json')
    {
        $this->jsonFile = $jsonFile;
    }

    public function loadBooksFromJson()
    {
        if (!file_exists($this->jsonFile)) {
            throw new Exception('Le fichier JSON n\'existe pas.');
        }

        $currentJson = file_get_contents($this->jsonFile);
        if ($currentJson === false) {
            throw new Exception('Erreur lors de la lecture du fichier JSON.');
        }

        $books = json_decode($currentJson, true);
        if ($books === null) {
            throw new Exception('Erreur lors du décodage du fichier JSON.');
        }

        return $books;
    }
}