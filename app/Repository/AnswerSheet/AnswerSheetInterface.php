<?php
/**
 * Created by PhpStorm.
 * User: mypop
 * Date: 3/21/18
 * Time: 10:27 PM
 */

namespace App\Repository\AnswerSheet;


interface AnswerSheetInterface
{
    public function createByCollectionId($collectionId);
    public function get($id);
    public function updateStatus($id, $status);
    public function generateResult($aid, Array $answers);
}