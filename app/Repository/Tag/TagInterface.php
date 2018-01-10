<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 31/12/2017
 * Time: 12:31
 */

namespace App\Repository\Tag;


use Illuminate\Http\Request;

interface TagInterface
{
    public function create(Array $attribute);
    public function getOrCreate($attribute);
    public function update(Array $attribute, $id);
    public function paginate(Request $request);
    public function get($id);
    public function delete($id);
    public function count();
}