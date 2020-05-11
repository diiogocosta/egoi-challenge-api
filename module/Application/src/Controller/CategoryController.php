<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Http\Response;
use Application\FakeORM\EntityManager;
use Application\Entity\Category;

class CategoryController extends AbstractRestfulController
{
  public function getList(){
    $repository = new EntityManager();
    $flat = filter_var($this->request->getQuery()['flat'], FILTER_VALIDATE_BOOLEAN);
    $dependentId = filter_var($this->request->getQuery()['dependentId'], FILTER_VALIDATE_INT);
    $search = $this->request->getQuery()['search'];

    return new JsonModel($repository->findAll($flat, $dependentId, $search));
  }

  public function get($id) {
    $repository = new EntityManager();
    return new JsonModel($repository->find($id));
  }

  public function create($data) {
    $entity = new EntityManager(new Category($data));
    $category = $entity->create();
    return new JsonModel($category);
  }

  public function update($id, $data) {
    $entity = new EntityManager();
    $entity->update($id, $data);

    return new JsonModel();
  }

  public function delete($id) {
    $entity = new EntityManager(new Category());
    $entity->delete($id);
    return new JsonModel();
  }
}
