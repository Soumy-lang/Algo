<?php

namespace functions;

class SearchBook
{
    private $books;

    public function __construct($file) {
        if (file_exists($file)) {
            $this->books = json_decode(file_get_contents($file), true);
        } else {
            throw new Exception("File not found: " . $file);
        }
    }

    private function quickSort(&$array, $column) {
        if (count($array) < 2) {
            return $array;
        }
        $left = $right = array();
        reset($array);
        $pivot_key = key($array);
        $pivot = array_shift($array);
        foreach ($array as $k => $v) {
            if ($v[$column] < $pivot[$column])
                $left[$k] = $v;
            else
                $right[$k] = $v;
        }
        return array_merge($this->quickSort($left, $column), array($pivot_key => $pivot), $this->quickSort($right, $column));
    }

    private function binarySearch($array, $column, $value) {
        $low = 0;
        $high = count($array) - 1;

        while ($low <= $high) {
            $mid = floor(($low + $high) / 2);
            if ($array[$mid][$column] < $value) {
                $low = $mid + 1;
            } elseif ($array[$mid][$column] > $value) {
                $high = $mid - 1;
            } else {
                return $array[$mid];
            }
        }

        return null;
    }

    public function search($column, $value) {
        if (empty($this->books)) {
            return null;
        }

        $sortedBooks = $this->quickSort($this->books, $column);
        return $this->binarySearch($sortedBooks, $column, $value);
    }
}