<?php

namespace  functions;
class Index
{
    public function Menu()
    {
        echo "Bienvenue dans le gestionnaire de bibliothèque en ligne !\n";
        echo "Que souhaitez-vous faire ?\n";
        echo "1. Enregistrer un livre\n";
        echo "2. Modifier un livre\n";
        echo "3. Supprimer un livre\n";
        echo "4. Afficher la liste des livres .\n";
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
                echo "Creation";
                // $this->createBook();
                break;
            case 2:
                echo "Modification";
                // $this->updateBook();
                break;
            case 3:
                echo "Suppression";
                // $this->deletebook();
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
