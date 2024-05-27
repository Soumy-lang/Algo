<?php

namespace functions;

use Exception;

class SortBook
{
    private $jsonLoader;

    public function __construct($jsonFile = 'books.json')
    {
        $this->jsonLoader = new JsonBookLoader($jsonFile);
    }

    public function sortBooks($column, $order)
    {
        // les options préce dement choisies correspondent aux colonnes et l'ordre de trie
        $columnMap = [
            1 => 'name',
            2 => 'description',
            3 => 'is_available'
        ];

        $orderMap = [
            1 => 'asc',
            2 => 'desc'
        ];

        $column = $columnMap[$column];
        $order = $orderMap[$order];

        $books = $this->jsonLoader->loadBooksFromJson();
        if (empty($books)) {
            echo "Aucun livre trouvé pour trier.\n";
            return;
        }

        // appel de la methode du trie fusion et celle qui affiche les resultats trié
        $sortedBooks = $this->mergeSort($books, $column, $order);
        $this->displayBooks($sortedBooks);
    }

    // trie fusion (diviser le tableau en deux sous tableaux et de meme aux sous tableaux)
    private function mergeSort($books, $column, $order)
    {
        if (count($books) <= 1) {
            return $books;
        }

        // On prend la valeur qui est au milieu du tableau
        // toutes les valeurs qui sont inferieurs à la valeur du milieu, sont stockées dans le tableau left
        // toutes les valeurs qui sont supperieurs à la valeur du milieu, sont stockées dans le tableau right

        $middle = intdiv(count($books), 2);
        $left = array_slice($books, 0, $middle);
        $right = array_slice($books, $middle);

        $left = $this->mergeSort($left, $column, $order);
        $right = $this->mergeSort($right, $column, $order);

        return $this->merge($left, $right, $column, $order);            // merger les tableaux triés selon une colonne specifique, par un ordre specifique
    }

    private function merge($left, $right, $column, $order)
    {
        $result = [];        // le tableau dans lequel on va stocké les valeurs triées

        while (count($left) > 0 && count($right) > 0) {
            // recuperer les valeurs de la colonne su laquelle le trie se fait (dans les deux sous tableaux)
            $leftValue = $left[0][$column];
            $rightValue = $right[0][$column];

            // selon l'ordre croissant ou decroissant, on ajoute la valeur au tableau result
            if ($order === 'asc') {
                if ($leftValue <= $rightValue) {
                    $result[] = array_shift($left);
                } else {
                    $result[] = array_shift($right);
                }
            } else {
                if ($leftValue >= $rightValue) {
                    $result[] = array_shift($left);
                } else {
                    $result[] = array_shift($right);
                }
            }
        }

        return array_merge($result, $left, $right);
    }

    private function displayBooks($books)
    {
        foreach ($books as $book) {
            echo "Titre : " . $book['name'] . "\n";
            echo "Description: " . $book['description'] . "\n";
            echo "En stock: " . ($book['is_available'] ? 'Oui' : 'Non') . "\n";
            echo "------------------------\n";
        }
    }
}
