<?php

namespace Application\FakeORM;

use Application\FakeORM\Helpers;

class EntityManager {

  private $dataArray;
  private $entity;

  function __construct($entity = null) {
    $this->dataArray = $this->getArrayFromFile() ?? [];
    if (isset($entity)) {
      $this->entity = $entity;
      $this->entity->setId(end($this->dataArray)['id']+1);
    }
  }

  private function getArrayFromFile() {
    return json_decode(file_get_contents(__DIR__ . '/../../data/db.json'), true);
  }

  public function findAll($flat = false, $dependentId = null) {
    $result = $this->dataArray;
    if (is_int($dependentId)) {
      $result = array_values(array_filter($result, function($item) use($result, $dependentId){
        return !in_array($item['id'], Helpers::getDependentIds($result, $dependentId));
      }));
    }

    return $flat ? $result : Helpers::recursiveStrategy($result, null);
  }

  public function find($id) {
    $dependentIds = Helpers::getDependentIds($this->dataArray, $id);
    
    $found = array_filter($this->dataArray, function($value) use($dependentIds) {
      return in_array($value['id'], $dependentIds);
    });
    
    Helpers::sort($found);

    $result = current(Helpers::recursiveStrategy($found, current($found)['categoryId'] ?? null));
    return $result ? $result : [];
  }

  public function create() {
    $result = $this->entity->toArray();
    array_push($this->dataArray, $result);
    file_put_contents(__DIR__ . '/../../data/db.json', json_encode($this->dataArray));
    return $result;
  }

  public function delete($id) {
    $dependentIds = Helpers::getDependentIds($this->dataArray, $id);
    $newArray = array_values(array_filter($this->dataArray, function($value) use($dependentIds){
      return !in_array($value['id'], $dependentIds);
    }));

    file_put_contents(__DIR__ . '/../../data/db.json', json_encode($newArray));
    return true;
  }

  public function update($id, $data) {
    $newCategories = array_map(function($value) use($id, $data){
      if ($value['id'] == $id) {
        $data['modified'] = date_create()->getTimestamp() * 1000; 
        $data['categoryId'] = $data['categoryId'] == 'null' ? null : $data['categoryId'] ?? null;
        return array_merge($value, $data);
      } else {
        return $value;
      }
    }, $this->dataArray);


    file_put_contents(__DIR__ . '/../../data/db.json', json_encode($newCategories));

    return true;
  }


}