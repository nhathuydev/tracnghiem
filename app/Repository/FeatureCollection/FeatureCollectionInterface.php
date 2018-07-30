<?php
/**
 * Created by PhpStorm.
 * User: mypop
 * Date: 4/23/18
 * Time: 12:51 AM
 */

namespace App\Repository\FeatureCollection;


interface FeatureCollectionInterface
{
    public function add($payload);
    public function remove($id);
}