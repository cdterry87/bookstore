<?php
require_once("./classes/Store.php");

$store = new Store("catalog");
$products = $store->getProducts();

$search = "";
$searchError = false;
if (isset($_POST["search"])) {
  $search = trim($_POST["search"]);
  if ($search) {
    $type = trim($_POST["type"]) ?? "title";
    $foundProduct = $store->searchProduct($search, $type);
  } else {
    $searchError = true;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Store</title>
  <style>
    body{font-family:sans-serif}a{text-decoration:none;color:inherit}.button,.input{border:solid 1px #000;padding:10px 20px}.v-margin{margin:30px 0}.container{text-align:center}.list{padding:0;list-style:none}.list li{margin:15px 0}.card{padding: 30px;border: solid 1px #000;width:280px;margin: 0 auto;}.error{color: red;}
  </style>
</head>
<body>
  <div class="container">
    <h1>Book Store</h1>
    <h2>Find what you're looking for by searching below!</h2>
    <section class="search v-margin">
      <form method="POST">
        <?php if ($searchError): ?>
          <div class="error v-margin">
            Please enter valid search criteria.
          </div>
        <?php endif; ?>
        <div>
          <input class="input" type="text" name="search">
          <button class="button" type="submit">Search</button>
        </div>
        <div class="v-margin">
          <p>Search by:</p>
          <label><input type="radio" name="type" id="title" value="title" checked> Title</label>
          <label><input type="radio" name="type" id="author" value="author"> Author</label>
          <label><input type="radio" name="type" id="publisher" value="publisher"> Publisher</label>
          <label><input type="radio" name="type" id="isbn" value="isbn"> ISBN</label>
        </div>
      </form>
    </section>
    <section class="content">
      <?php if($search): ?>
        <?php if($foundProduct): ?>
          <div class="card">
            <h3><?= $foundProduct["title"] ?></h3>
            <p>Written by <?= $foundProduct["author"] ?></p>
            <p>Published by <?= $foundProduct["publisher"] ?></p>
            <p>ISBN: <?= $foundProduct["isbn"] ?></p>
          </div>
        <?php else: ?>
          <p>Product Not Found. Please try again.</p>
        <?php endif; ?>
        <div class="v-margin">
          <a class="button" href="<?= strtok($_SERVER["REQUEST_URI"], "?") ?>">Clear Search Results</a>
        </div>
      <?php else: ?>
        <h3>Search Suggestions</h3>
        <ul class="list">
        <?php foreach($products as $product): ?>
          <li><?= $product["title"] ?></li>
        <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>
  </div>
</body>
</html>