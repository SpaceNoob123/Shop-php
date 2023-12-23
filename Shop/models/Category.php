<?php

class Category
{
    public $id;
    public $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function saveToJson() {
        $categoryData = json_encode([
            'id' => $this->id,
            'name' => $this->name,
        ]);

        file_put_contents('categories.json', $categoryData, FILE_APPEND);
    }
}