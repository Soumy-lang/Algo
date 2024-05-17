<?php

namespace functions;
use Exception;

class CreateBook
{
    private $id;
    private $name;
    private $description;
    private $author;

    public function __construct($id, $name, $description, $author)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->author = $author;
    }

    public function saveToJson($filename)
    {
        $bookData = [];

        if (file_exists($filename)) {
            $existingData = file_get_contents($filename);
            $bookData = json_decode($existingData, true);
        }

        $newBookData = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'author' => $this->author
        ];
        $bookData[] = $newBookData;

        $jsonString = json_encode($bookData);

        if ($jsonString === false) {
            throw new Exception('Erreur lors de l\'encodage JSON.');
        }

        if (file_put_contents($filename, $jsonString) === false) {
            throw new Exception('Erreur lors de l\'enregistrement du fichier JSON.');
        }
    }


    public function logToHistory($filename)
    {
        date_default_timezone_set('Europe/Paris');
        $logMessage = 'Nouveau livre ajouté le ' . date('Y-m-d H:i:s') . ' avec l\'id ' . $this->id . PHP_EOL;

        if (file_put_contents($filename, $logMessage, FILE_APPEND) === false) {
            throw new Exception('Erreur lors de l\'enregistrement de l\'historique.');
        }
    }
}