<?php

class Store {
  protected $products = [];

  public function __construct($dir)
  {
    $this->getProductsByDirectory($dir);
  }

  public function getProducts() : array
  {
    return $this->products;
  }

  public function searchProducts($criteria, $key="title") : array
  {
    $foundProducts = [];
    $foundIndexes = array_keys(array_map("strtolower", array_column($this->products, $key)), strtolower($criteria), true);

    foreach($foundIndexes as $index) {
      $foundProducts[] = $this->products[$index];
    }
    
    return $foundProducts;
  }

  protected function getProductsByDirectory($dir, &$products = [])
  {
    $files = array_diff(scandir($dir), array(".", "..", ".DS_Store", ".htaccess"));
  
    foreach ($files as $file) {
      $path = realpath($dir . DIRECTORY_SEPARATOR . $file);
      if (!is_dir($path)) {
        $this->products[] = $this->getProductInfo($path);
      } else {
        $this->getProductsByDirectory($path, $products);
      }
    }
  }
  
  protected function getProductInfo($path)
  {
    if (!is_file($path)) return;
  
    $product = [];
  
    foreach(file($path) as $file) {
      $line = explode("=", $file);
      $product[$line[0]] = trim($line[1]);
    }
    return $product;
  }
}
