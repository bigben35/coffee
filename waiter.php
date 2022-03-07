<?php

    class Waiter 
  {
    public $id;
    public $name;

    public function __construct($data) {
      $this->id = $data["id"];
      $this->name = $data["name"];
    }

    public static function all($pdo)
   {
    $sqlQuery = "SELECT id, name FROM `Waiter`";
  
    $waiters = [];
  
    foreach($pdo->query($sqlQuery) as $res) {
      array_push(
        $waiters, 
        new Waiter($res)
      );  
  }

  return $waiters;
  }
  }