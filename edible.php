<?php    

class Edible {
  public static function all() {
    $path = "mysql:host=".$_ENV['DB_HOST'].":".$_ENV['DB_PORT'].";dbname=".$_ENV['DB_NAME'].";charset=utf8";

    $pdo =     
      new PDO(
        $path, 
        $_ENV['DB_USERNAME'], 
        $_ENV['DB_PASSWORD']
      );
    
    $edibles = [];

    $sqlQuery = 'SELECT id, name FROM Edible';

    foreach ($pdo->query($sqlQuery) as $edible) {
      array_push(
        $edibles, 
        new self($edible)
      );
    }
    
    return $edibles;
  }
    
  private $id;
  private $name;
    
  public function __construct($data) {
    $this->id = $data["id"];
    $this->name = $data["name"];
  }

  public function getName() {
    return $this->name;
  }
}