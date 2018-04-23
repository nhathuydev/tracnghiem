<?php
/**
 * Created by PhpStorm.
 * User: mypop
 * Date: 4/23/18
 * Time: 12:52 AM
 */

namespace App\Repository\FeatureCollection;


use App\Models\FeatureCollection;

class FeatureCollectionRepository implements FeatureCollectionInterface
{
    private $fc;

    public function __construct(FeatureCollection $featureCollection)
    {
        $this->fc = $featureCollection;
    }

    public function add($payload)
    {
        return $this->fc->create($payload);
    }

    public function remove($id)
    {
        return $this->fc->destroy($id);
    }

    public function getByType($type)
    {
        return $this->fc->where('type', $type)
                        ->with(['collection'])
                        ->paginate(100);
    }
}