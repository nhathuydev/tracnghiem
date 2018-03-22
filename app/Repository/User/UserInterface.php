<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 10/01/2018
 * Time: 14:55
 */

namespace App\Repository\User;


use Illuminate\Http\Request;

interface UserInterface
{
    public function create(Array $attribute);
    public function getOrCreate($attribute);
    public function update(Array $attribute, $id);
    public function paginate(Request $request);
    public function get($id);
    public function delete($id);
    public function count();
}