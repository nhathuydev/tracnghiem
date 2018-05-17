<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 05/01/2018
 * Time: 15:45
 */

namespace App\Repository\Collection;


interface CollectionInterface
{
    public function create(Array $attribute);
    public function update(Array $attribute, $id);
    public function paginate($size, $keyword);
    public function get($id);
    public function publish($ids, $publish = true);
    public function attachQuestion($collection_id, $question_ids, $attach = true);
    public function createQuestion($collection_id, Array $attribute);
    public function count();
    public function search($keyword);
    public function generateForUser($collection_id);
    public function bookmark($collection_id);
}