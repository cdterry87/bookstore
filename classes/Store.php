<?php

class Store {
  protected $products = [];

  public function __construct($dir)
  {
    $this->getProductsByDirectory($dir);
  }

  public function getProducts()
  {
    return $this->products;
  }

  public function searchProduct($criteria, $key="title")
  {
    $productKey = array_search(strtolower($criteria), array_map("strtolower", array_column($this->products, $key)));
    if (false !== $productKey) {
      return $this->products[$productKey];
    }
    return false;
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
