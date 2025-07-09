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
        $farm = new Farm();
        $farm->name = $data['name'];
        $farm->city = $data['city'];
        $farm->location = $data['location'];
        $farm->postal_code = $data['postal_code'];
        $farm->capacity = $data['capacity'];
        $farm->user_id = auth()->user()->id;
        $farm->animal_types = json_encode($data['animal_types']);
        $farm->animal_breeds = json_encode($data['animal_breeds']);
        $farm->save();
        return $farm;
    }

    public function updateFarm($id, $data)
    {
        $farm = Farm::find($id);
        if (!$farm) {
            return null;
        }
        $farm->name = $data['name'];
        $farm->city = $data['city'];
        $farm->location = $data['location'];
        $farm->postal_code = $data['postal_code'];
        $farm->capacity = $data['capacity'];
        $farm->user_id = auth()->user()->id;
        $farm->animal_types = json_encode($data['animal_types']);
        $farm->animal_breeds = json_encode($data['animal_breeds']);
        $farm->save();
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