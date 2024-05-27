<?php

namespace functions;

use Exception;

class SearchBook
{
    private $jsonLoader;

    public function __construct($jsonFile = 'books.json')
    {
        $this->jsonLoader = new JsonBookLoader($jsonFile);
    }

    public function search($column, $value)
    {
        $books = $this->jsonLoader->loadBooksFromJson();           // recuperer tous les livres du fichier json en les mettant au format d'un tableau associatif
        if (empty($books)) {
            return false;
        }

        $this->quickSort($books, $column);                        // trier le tableau par un trie rapide
        $result = $this->binarySearch($books, $column, $value);         // faire la recherche sur le tableau trié
        $this->logToHistory($result);                                   // enregistrer l'historique

        return $result;
    }

    // trie rapide (diviser le tableau en deux sous tableau et appliquer recursivement la meme opperation sur les sous tableaux)
    private function quickSort(&$array, $column)
    {
        if (count($array) <= 1) {
            return;                     // arretrer l'algo si le tableau a moins de 2 valeurs
        }

        $pivot = $array[0][$column];    // la 1ere valeur du tableau sera le pivot
        $left = $right = [];

        foreach ($array as $value) {        // parcourir le tableau pour separer les valeurs superieurs au pivot, et celles inferieures
            if ($value[$column] < $pivot) {
                $left[] = $value;           // affecter les valeurs inferieurs dans le tableau left
            } elseif ($value[$column] > $pivot) {
                $right[] = $value;          // affecter les valeurs superieurs dans le tableau right
            }
        }

        // appeler la methode pour chaque tableau
        $this->quickSort($left, $column);
        $this->quickSort($right, $column);

        $array = array_merge($left, [$array[0]], $right);       // merger les elements triés dans le tableau $array
    }

    // Recherche binaire (diviser le tableau en 2 sous tableaux et chercher dans le tableau qui correspond à la plage de la valeur recherchée)
    private function binarySearch($array, $column, $value)
    {
        // 1er indice et dernier indice du tableau
        $left = 0;
        $right = count($array) - 1;

        while ($left <= $right) {
            $mid = floor(($left + $right) / 2);         // recuperer l'indice du milieu du tableau
            $currentValue = $array[$mid][$column];

            if ($currentValue == $value) {                  // comparer la valeur recherchée à la valeur du milieu du tableau
                return $array[$mid];                        // si c'est la valeur recherché, l'algo s'arrete et renvoie la valeur
            }

            if ($currentValue < $value) {                   // si la valeur du milieu est inferieur à la valeur recherchée, on passe au 2eme indice du tableau et on reprend la meme recherche
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }

        return false;
    }

    public function logToHistory($result)
    {
        date_default_timezone_set('Europe/Paris');
        $logMessage = 'Vous avez effectué une recherer pour trouvé le livre intitulé "' . $result['name'] . '" à la date du ' . date('d/m/Y H:i:s') . PHP_EOL;

        if (file_put_contents('history.txt', $logMessage, FILE_APPEND) === false) {
            throw new Exception('Erreur lors de l\'enregistrement de l\'historique.');
        }
    }
}
