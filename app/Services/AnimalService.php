<?php

namespace App\Services;

use App\Models\Animal;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class AnimalService
{
    public function getAllAnimals($data)
    {
        $query = Animal::with(['farm', 'event', 'breed']);
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

        if ($data['search']) {
            $query = $query
                ->where('animal_id', 'like', '%' . $data['search'] . '%')
                ->orWhere('sir_id', 'like', '%' . $data['search'] . '%')
                ->orWhere('dam_id', 'like', '%' . $data['search'] . '%')
                ->orWhere('gender', 'like', '%' . $data['search'] . '%')
                ->orWhereHas('farm', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search'] . '%');
                })
                ->orWhereHas('eventType', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search'] . '%');
                })
                ->orWhereHas('animalType', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search'] . '%');
                })
                ->orWhereHas('breed', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search'] . '%');
                });
        }

        if ($data['event_type_id']) {
            $query->where('event_type_id', $data['event_type_id']);
        }

        return $query;
    }

    public function getAnimalsByFarmId($data, $farmIds)
    {
        
        $query = Animal::with(['farm', 'event', 'breed'])->whereIn('farm_id', $farmIds);

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

        if ($data['search']) {
            $query = $query
                ->where('animal_id', 'like', '%' . $data['search'] . '%')
                ->orWhere('sir_id', 'like', '%' . $data['search'] . '%')
                ->orWhere('dam_id', 'like', '%' . $data['search'] . '%')
                ->orWhere('gender', 'like', '%' . $data['search'] . '%')
                ->orWhereHas('farm', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search'] . '%');
                })
                ->orWhereHas('eventType', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search'] . '%');
                })
                ->orWhereHas('animalType', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search'] . '%');
                })
                ->orWhereHas('breed', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search'] . '%');
                });
        }

        if ($data['event_type_id']) {
            $query->where('event_type_id', $data['event_type_id']);
        }

        return $query;
    }

    public function getAnimalById($id)
    {
        return Animal::with(['farm', 'event', 'breed'])->find($id);
    }

    public function createAnimal($data)
    {
        // create all data except images and event_type_id

        $animal = new Animal(); 
        $animal->animal_id = $data['animal_id'];
        $animal->sir_id = $data['sir_id'];
        $animal->dam_id = $data['dam_id'];
        $animal->gender = $data['gender'];
        $animal->birth_date = $data['birth_date'];
        $animal->farm_id = $data['farm_id'];
        $animal->breed_id = $data['breed_id'];
        $animal->animal_type_id = $data['animal_type_id'];
        $animal->event_type_id = $data['event_type_id'];
        $animal->save();


        return $animal;
    }

    public function updateAnimal($id, $data)
    {
        $animal = Animal::find($id);
        $animal->animal_id = $data['animal_id'] ?? $animal->animal_id;
        $animal->sir_id = $data['sir_id'] ?? $animal->sir_id;
        $animal->dam_id = $data['dam_id'] ?? $animal->dam_id;
        $animal->gender = $data['gender'] ?? $animal->gender;
        $animal->birth_date = $data['birth_date'] ?? $animal->birth_date;
        $animal->farm_id = $data['farm_id'] ?? $animal->farm_id;
        $animal->breed_id = $data['breed_id'] ?? $animal->breed_id;
        $animal->animal_type_id = $data['animal_type_id'] ?? $animal->animal_type_id;
        $animal->event_type_id = $data['event_type_id'] ?? $animal->event_type_id;
        $animal->save();


        return $animal;
    }

    public function deleteAnimal($id)
    {
        $animal = Animal::find($id);
        if (!$animal) {
            return false;
        }
        return $animal->delete();
    }

    public function getAnimalsByFarm($farmId)
    {
        return Animal::with(['farm', 'event', 'breed'])->where('farm_id', $farmId);
    }

    public function getAnimalsByBreed($breedId)
    {
        return Animal::with(['farm', 'event', 'breed'])->where('breed_id', $breedId);
    }

    public function getAnimalsByGender($gender)
    {
        $animals = Animal::select('id', 'animal_id');
        if ($gender != null) {  
            $animals = $animals->where('gender', $gender);
        }
        return $animals->get();
    }
}
