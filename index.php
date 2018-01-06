<html>
<head>
  <title>Today's Menu</title>
  <style>
  div.dishName {
    font-weight:bold;
  }
  div.dishDescription {
    font-style: italic;
  }
  ul.dishList {
    list-style-type: none;
  }
  </style>
</head>
<body>
  <?php
  // Require composer
  require __DIR__ . '/vendor/autoload.php';
  // Load envs
  $dotenv = new Dotenv\Dotenv(__DIR__);
  $dotenv->load();

  // Define a class to handle formatting the output for each dish
  class Dish {
    function __construct($id, $name, $description){
      $this->id = $id;
      $this->name = $name;
      $this->description = $description;
    }
    function html(){
      return "<div class='dishName'>$this->name</div><div class='dishDescription'>$this->description</div>";
    }
  }

  // Open up a new db connection
  $conn = new mysqli($_ENV['MYSQL_HOST'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS'], $_ENV['MYSQL_DB']);
  if ($conn->connect_error) die('Connection error: ' . $conn->connect_error);

  // Get lunch & dinner dishes
  $lunch = $conn->query("SELECT dishes.name AS dish_name, dishes.description AS dish_description, dishes.id AS dish_id FROM dishes dishes INNER JOIN meals AS meals ON dishes.id=meals.dish_id WHERE meals.name='lunch'");

  $dinner = $conn->query("SELECT dishes.name AS dish_name, dishes.description AS dish_description, dishes.id AS dish_id FROM dishes dishes INNER JOIN meals AS meals ON dishes.id=meals.dish_id WHERE meals.name='dinner'");

  // Output the lunch menu
  echo("<h2>Lunch</h2><ul class='dishList'>");
  while($this_dish = $lunch->fetch_assoc()){
    $dish = new Dish($this_dish['dish_id'],$this_dish['dish_name'],$this_dish['dish_description']);
    echo('<li>'.$dish->html().'</li>');
  }
  echo('</ul>');

  // Output the dinner menu
  echo("<h2>Dinner</h2><ul class='dishList'>");
  while($this_dish = $dinner->fetch_assoc()){
    $dish = new Dish($this_dish['dish_id'],$this_dish['dish_name'],$this_dish['dish_description']);
    echo('<li>'.$dish->html().'</li>');
  }
  echo('</ul>');

  // Close the db connection
  mysqli_close($conn)
  ?>

</body>
</html>
