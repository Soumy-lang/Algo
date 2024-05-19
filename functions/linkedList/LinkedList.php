<?php

namespace functions\linkedList;

class LinkedList
{
    private $head;

    public function __construct()
    {
        $this->head = null;
    }

    public function add($data)
    {
        $newNode = new Node($data);
        if ($this->head === null) {
            $this->head = & $newNode;
        } else {
            $current = $this->head;
            while ($current->next !== null) {
                $current = $current->next;
            }
            $current->next = $newNode;
        }
    }

    public function getAll()
    {
        $books = [];
        $current = $this->head;
        while ($current !== null) {
            if (is_object($current->data)) {
                $books[] = (array)$current->data;
            } else {
                $books[] = $current->data;
            }
            $current = $current->next;
        }
        return $books;
    }
}