<?php

namespace  functions;

require_once('functions/CreateBook.php');
require_once('functions/DeleteBook.php');
require_once('functions/SearchBook.php');
require_once('functions/UpdateBook.php');
require_once('functions/GetBook.php');
require_once('functions/SortBook.php');
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
        echo "4. Afficher tous les livres.\n";
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
                    $is_available = readline("Le livre est-il en stock ? 1 si oui et 0 sinon : ");
                    if($is_available == 1 || $is_available == 0){
                        $book = new CreateBook($title, $description, $author, $is_available);
                        $book->saveToJson('books.json');
                        $book->logToHistory('history.txt');
                        echo "Le livre a été créé et enregistré avec succès.";
                    }else{
                        echo "Le livre est-il en stock ? Veuillez répondre par 1 ou 0.";
                    }
                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
                break;
            case 2:// modification
                try {
                    $updateBook = new UpdateBook();

                    $bookId = readline("Quel livre souhaitez-vous modifier (id) ? : ");
                    if (!is_numeric($bookId)) {
                        echo "L'ID du livre doit être un nombre.";
                        return;
                    }

                    $newName = readline("Titre du livre (laissez vide pour garder l'ancien) : ");
                    $newDescription = readline("Description (laissez vide pour garder l'ancienne) : ");
                    $newAvailability = readline("Le livre est-il en stock (1-oui/0-non) ? : ");
                    if ($newAvailability !== '1' && $newAvailability !== '0' && $newAvailability !== '') {
                        echo "Il faut renseigner la disponibilité du livre à 1-Oui ou 0-Non";
                        return;
                    }

                    $updateBook->updateBook($bookId, $newName, $newDescription, $newAvailability);

                    echo 'Le livre a été modifié avec succès.';
                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
                break;
            case 3: // suppression
                try {
                    $id = readline("Quel livre souhaitez vous supprimer (id) : ");
                    DeleteBook::deleteTheBook($id, 'books.json', 'history.txt');
                    echo 'Le livre a été supprimé avec succès.';
                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
                break;
            case 4: // tous les livres
                try {
                    $getBook = new GetBook();
                    $getBook->getAllBooks();
                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
                break;
            case 5: // details d'un livre
                $getBook = new GetBook();
                try {
                    // Afficher un livre par ID ou nom
                    $bookId = readline("Entrez le nom ou l'id su livre : ");
                    if (!empty($bookId)) {
                        if(is_numeric($bookId)) {
                            $getBook->getOneBook($bookId);
                        }else{
                            $getBook->getOneBook($bookId);
                        }
                    }
                } catch (Exception $e) {
                    echo "Une erreur est survenue : " . $e->getMessage();
                }

                break;
            case 6: // trie fusion
                echo "Valeur du trie\n";
                echo "1-Trie par nom\n";
                echo "2-Trie par description\n";
                echo "3-Trie par diponibilité\n";
                echo "Ordre du trie\n";
                echo "1-Ordre Croissant\n";
                echo "2-Ordre decroissant\n";
                try {
                    $searchValue = readline("Valeur du trie : ");
                    $searchOrder = readline("Ordre du trie : ");

                    if (!is_numeric($searchValue) || $searchValue < 1 || $searchValue > 3) {
                        echo "Choix de tri incorrect. Veuillez entrer un nombre entre 1 et 3." . PHP_EOL;
                        return;
                    }

                    if (!is_numeric($searchOrder) || $searchOrder < 1 || $searchOrder > 2) {
                        echo "Choix d'ordre incorrect. Veuillez entrer 1 ou 2." . PHP_EOL;
                        return;
                    }

                    $sortBook = new SortBook();
                    $sortBook->sortBooks($searchValue, $searchOrder);


                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
                break;
            case 7: // Recherche binaire
                try {
                    $searchBook = new SearchBook('books.json');
                    $searchColumn = readline("Votre recherche se base sur quelle colonne ? : ");
                    $validColumns = ["id", "name", "description", "author", "is_available"];
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
                            echo "Ce livre n'a pas été enregisté. ";
                        }
                    }

                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
                break;
            case 8: // quitter
                break;
            default:
                echo "Option invalide.\n";
        }
    }
}

$index = new Index();
$index->Menu();