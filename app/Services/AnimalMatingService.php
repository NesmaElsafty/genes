<?php

namespace App\Services;

use App\Models\AnimalMating;

class AnimalMatingService
{
    public function getAnimalMatingById($id)
    {
        return AnimalMating::with(['sir', 'dam'])->find($id);
    }

    public function createAnimalMating($data)
    {
        // update or create by sir_id, dam_id and date
        $animalMating = AnimalMating::where([
            'sir_id' => $data['sir_id'],
            'dam_id' => $data['dam_id'],
            'date' => $data['date']
        ])->first();
        
        if ($animalMating) {
            $animalMating->update([
                'mating_type' => $data['mating_type'],
            ]);
        } else {
            $animalMating = AnimalMating::create([
                'date' => $data['date'],
                'sir_id' => $data['sir_id'],
                'dam_id' => $data['dam_id'],
                'mating_type' => $data['mating_type'],
            ]);
        }
        return $animalMating;
    }

    public function updateAnimalMating($id, $data)
    {
        $animalMating = AnimalMating::find($id);
        if (!$animalMating) {
            return null;
        }
        
        $animalMating->update([
            'date' => $data['date'],
            'sir_id' => $data['sir_id'],
            'dam_id' => $data['dam_id'],
            'mating_type' => $data['mating_type'],
        ]);

        return $animalMating;
    }

    public function deleteAnimalMating($id)
    {
        $animalMating = AnimalMating::find($id);
        if (!$animalMating) {
            return false;
        }
        return $animalMating->delete();
    }

    public function getMatingsByAnimal($animalId)
    {
        return AnimalMating::with(['sir', 'dam'])
            ->where('sir_id', $animalId)
            ->orWhere('dam_id', $animalId)
            ->get();
    }

    public function getMatingsByType($matingType)
    {
        return AnimalMating::with(['sir', 'dam'])
            ->where('mating_type', $matingType)
            ->get();
    }
} 