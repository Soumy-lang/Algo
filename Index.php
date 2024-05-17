<?php

namespace  functions;

require_once('functions/CreateBook.php');
require_once('functions/DeleteBook.php');



class Index
{
    public function Menu()
    {
        echo "Bienvenue dans le gestionnaire de bibliothèque en ligne !\n";
        echo "Que souhaitez-vous faire ?\n";
        echo "1. Enregistrer un livre\n";
        echo "2. Modifier un livre\n";
        echo "3. Supprimer un livre\n";
        echo "4. Afficher la liste des livres.\n";
        echo "5. Afficher les détails d'un livre.\n";
        echo "6. Trier les livres\n";
        echo "7. Rechercher un livre\n";
        echo "8. Quitter\n";

        $choice = readline("Entrez le numéro de l'option : ");
        $this->UserChoice($choice);
    }

    public function UserChoice($choice)
    {
        switch ($choice) {
            case 1:
                try {
                    $book = new CreateBook(3, 'Barbie girl', 'Fiction pour enfants', 'Moi meme');
                    $book->saveToJson('books.json');
                    $book->logToHistory('history.txt');
                    echo "Le livre a été créé et enregistré avec succès.";
                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
                break;
            case 2:
                echo "Modification";
                // $this->updateBook();
                break;
            case 3:
                // echo "Suppression";
                try {
                    DeleteBook::deleteFromJson(3, 'books.json', 'history.txt');
                    echo 'Le livre a été supprimé avec succès.';
                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
                break;
            case 4:
                echo "Afficher les livre";
                // $this->getBooks();
                break;
            case 5:
                echo "Afficher un livre";
                // $this->showbook();
                break;
            case 6:
                echo "trier les livres";
                // $this->sortbooks();
                break;
            case 7:
                echo "Rechercher un livre";
                // $this->searchbook();
                break;
            case 8:
                // quitter
                break;

            default:
                echo "Option invalide.\n";
        }
    }

}

$index = new Index();
$index->Menu();
