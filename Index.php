<?php

namespace  functions;

require_once('functions/CreateBook.php');
require_once('functions/DeleteBook.php');
require_once('functions/SearchBook.php');
require_once ('functions/linkedList/LinkedList.php');
require_once ('functions/linkedList/Node.php');


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
        echo "7. Chercher un livre\n";
        echo "8. Quitter\n";

        $choice = readline("Entrez le numéro de l'option : ");
        $this->UserChoice($choice);
    }

    public function UserChoice($choice)
    {
        switch ($choice) {
            case 1: // nouveau
                try {
                    $title = readline("Titre du livre : ");
                    $description = readline("Description du livre : ");
                    $author = readline("Auteur du livre : ");

                    $book = new CreateBook($title, $description, $author);
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
            case 3: // suppression
                try {
                    $id = readline("Quel livre souhaitez vous supprimer : ");
                    DeleteBook::deleteFromJson($id, 'books.json', 'history.txt');
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
                // echo "Rechercher un livre";
                try {
                    $searchBook = new SearchBook('books.json');
                    $searchColumn = readline("Votre recherche se base sur quelle colonne ? : ");
                    $validColumns = ["id", "name", "description", "author"];
                    if (!in_array($searchColumn, $validColumns)) {
                        echo "Désolé ! Cette colonne n'existe pas";
                    }else{
                        $searchValue = readline("C'est quoi la valeur ? : ");
                        $column = $searchColumn;
                        $value = $searchValue;
                        $result = $searchBook->search($column, $value);

                        if ($result) {
                            echo "Livre trouvé : " . json_encode($result, JSON_PRETTY_PRINT);
                        } else {
                            echo "Ce livre n'est pas dans notre registre. ";
                        }
                    }

                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
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
