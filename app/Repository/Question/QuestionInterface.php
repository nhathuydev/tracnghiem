<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 31/12/2017
 * Time: 11:52
 */

namespace App\Repository\Question;


use Illuminate\Http\Request;

interface QuestionInterface
{
    public function create(Array $attribute);
    public function update(Array $attribute, $id);
    public function paginate(Request $request);
    public function get($id);
    public function delete($id);
}