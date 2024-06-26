<?php

namespace functions;
use Exception;
use functions\linkedList\LinkedList;

class CreateBook
{
    private $id;
    private $name;
    private $description;
    private $author;
    private $is_available;
    private static $bookList;
    private static $jsonFile = 'books.json';


    public function __construct($name, $description, $author, $is_available)
    {
        // initialisation des propriétés de la classe avec les valeurs fournies par le user
        // generer un id unique
        // ajouter les livres du fichier json dans la liste chainée
        if (!isset(self::$bookList)) {
            self::$bookList = new LinkedList();
            $this->loadBooksFromJson();
        }

        $this->name = $name;
        $this->description = $description;
        $this->author = $author;
        $this->is_available = $is_available;
        $this->id = $this->generateId();
        $this->addBook();
    }

    // verifier l'existance du fichier, ajouter les données sous forme de tableau associatif dans la liste chainée
    private function loadBooksFromJson()
    {
        if (file_exists(self::$jsonFile)) {
            $existingData = file_get_contents(self::$jsonFile);
            $books = json_decode($existingData, true);
            if (!empty($books)) {
                foreach ($books as $book) {
                    self::$bookList->add($book);
                }
            }
        }
    }

    // generer un id unique en incrementant le dernier id
    private function generateId()
    {
        if (self::$bookList === null || empty(self::$bookList->getAll())) {
            return 1;
        }

        $books = self::$bookList->getAll();
        $lastBook = end($books);
        return $lastBook['id'] + 1;
    }

    // ajouter un livre dans la liste chainée
    private function addBook()
    {
        $newBookData = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'author' => $this->author,
            'is_available' => $this->is_available,
        ];
        self::$bookList->add($newBookData);
    }

    // ajouter le livre dans le fichier json
    public function saveToJson($filename)
    {
        $bookData = self::$bookList->getAll();

        foreach ($bookData as &$book) {
            if (is_object($book)) {
                $book = (array)$book;
            }
        }

        $jsonString = json_encode($bookData, JSON_PRETTY_PRINT);

        if ($jsonString === false) {
            throw new Exception('Erreur lors de l\'encodage JSON.');
        }

        if (file_put_contents($filename, $jsonString) === false) {
            throw new Exception('Erreur lors de l\'enregistrement du fichier JSON.');
        }
    }

    // enregistrer l'historique
    public function logToHistory($filename)
    {
        date_default_timezone_set('Europe/Paris');
        $logMessage = 'Nouveau livre intitulé "' . $this->name . '" a été ajouté le ' . date('d/m/Y H:i:s') . ' avec l\'id ' . $this->id . PHP_EOL;

        if (file_put_contents($filename, $logMessage, FILE_APPEND) === false) {
            throw new Exception('Erreur lors de l\'enregistrement de l\'historique.');
        }
    }
}
