<?php

namespace App\Services;

use App\Models\Farm;

class FarmService
{
    public function getAllFarms($perPage = 10)
    {
        return Farm::paginate($perPage);
    }

    public function getFarmById($id)
    {
        return Farm::find($id);
    }

    public function createFarm($data)
    {
        return Farm::create($data);
    }

    public function updateFarm($id, $data)
    {
        $farm = Farm::find($id);
        if (!$farm) {
            return null;
        }
        $farm->update($data);
        return $farm;
    }

    public function deleteFarm($id)
    {
        $farm = Farm::find($id);
        if (!$farm) {
            return false;
        }
        return $farm->delete();
    }
} 