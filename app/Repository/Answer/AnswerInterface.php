<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 31/12/2017
 * Time: 11:01
 */
namespace App\Repository\Answer;
interface AnswerInterface
{
    public function create(Array $attribute);
    public function getOrCreate($attribute);
    public function update(Array $attribute, $id);
    public function paginate($size, $keyword);
    public function get($id);
    public function count();
}