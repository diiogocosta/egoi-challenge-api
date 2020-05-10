<?php

namespace Application\FakeORM;

class Helpers {
  private static function recursiveDependents($currentArray, $dependentId) {
    $dependents = array_filter($currentArray, function($value) use($dependentId){
      return $value['categoryId'] == $dependentId;
    });

    if (!empty($dependents)) {
      foreach ($dependents as $value) {
        $dependents = array_merge($dependents, self::recursiveDependents($currentArray, $value['id']));
      }
    }
    return $dependents;
  }

  public static function recursiveStrategy($currentArray, $parentId = null) {
    $node = [];
    foreach ($currentArray as $data) 
    {
      if ($data['categoryId'] == $parentId) 
      {
        $categories = self::recursiveStrategy($currentArray, $data['id']);
        if (!empty($categories)) 
        {
          $data['categories'] = $categories;
        }
        $node[] = $data;
      }
  
    }
    return $node;
  }

  public static function getDependentIds($array, $id) {
    $dependentIds = array_map(function($value){
      return $value['id'];
    }, Helpers::recursiveDependents($array, $id)); 
    
    $dependentIds[] = $id;

    return $dependentIds;
  }

  public static function sort(&$array, $field  = 'id') {
    usort($array, function($a,$b) use($field) { 
      return $a[$field] - $b[$field];
    });
  }

}
?>