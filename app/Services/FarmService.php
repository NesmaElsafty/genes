<?php

namespace App\Services;

use App\Models\Farm;
use App\Models\Event;
use App\Models\Animal;
use App\Helpers\ExportHelper;

class FarmService
{
    public function getAllFarms($data)
    {
        $query = Farm::query();
        if(isset($data['sorted_by'])){
        switch ($data['sorted_by']) {
            case 'newest':
                $query = $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query = $query->orderBy('created_at', 'asc');
                break;
            case 'name':
                $query = $query->orderBy('name', 'asc');
                break;
        }

        if (isset($data['search'])) {
            $query
                ->where('name', 'like', '%' . $data['search'] . '%')
                ->orWhere('city', 'like', '%' . $data['search'] . '%')
                ->orWhere('location', 'like', '%' . $data['search'] . '%')
                ->orWhere('postal_code', 'like', '%' . $data['search'] . '%')
                ->orWhere('capacity', 'like', '%' . $data['search'] . '%')
                ->orWhereHas('users', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search'] . '%');
                })
                ->orWhereHas('animals', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search'] . '%');
                });
        }
        }

        if (isset($data['event_type_id'])) {
            $query->where('event_type_id', $data['event_type_id']);
        }

        return $query;
    }

    // stats
    public function stats()
    {
        $totalFarms = Farm::count();
        $totalAnimals = Animal::count();
        $totalEvents = Event::count();
        return ['total_farms' => $totalFarms, 'total_animals' => $totalAnimals, 'total_events' => $totalEvents];
    }

    // selectable farms
    public function selectableFarms()
    {
        return Farm::select('id', 'name')->get();
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
        $farm->user_id = null;
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

    public function getAllUserFarms($userId, $perPage = 10)
    {
        return Farm::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->paginate($perPage);
    }

    public function exportSheet($ids, $user)
    {
        $farms = Farm::all();
        if (isset($ids)) {
            $farms = Farm::whereIn('id', $ids)->get();
        } 

        $csvData = [];

        foreach ($farms as $farm) {
            $csvData[] = [
                'id' => $farm->id,
                'name' => $farm->name,
                'user_name' => $farm->users?->pluck('name'),
                'city' => $farm->city,
                'location' => $farm->location,
                'postal_code' => $farm->postal_code,
                'capacity' => $farm->capacity,
                'animal_types' => $farm->animal_types,
                'animal_breeds' => $farm->animal_breeds,
                'created_at' => $farm->created_at,
                'updated_at' => $farm->updated_at,
            ];
        }

        $filename = 'farms_export_' . now()->format('Ymd_His') . '.csv';
        $media = ExportHelper::exportToMedia($csvData, $user, 'exports', $filename);

        return $media->getFullUrl();
    }
}
