<?php

namespace Application\Entity;
use \DateTime;

class Category {
  private $id;
  private $categoryId;
  private $name;
  private $created;
  private $modified;
  private $categories;

  function __construct($data = null){
    if (isset($data)) {
      $this->categoryId = $data['categoryId'] == 'null' ? null : $data['categoryId'] ?? null;
      $this->name = $data['name'];
      $this->created = date_create()->getTimestamp() * 1000;
    }
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setCategoryId($categoryId) {
    $this->categoryId = $categoryId;
  }

  public function setName($name) {
    $this->categoryId = $name;
  }

  public function setModified() {
    $this->modified = new DateTime();
  }

  public function toArray() {
    return get_object_vars($this);
  }

  
}