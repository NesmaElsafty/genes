<?php

namespace App\Services;

use App\Models\AnimalView;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class AnimalViewService
{
    public function getAnimalViewById($id)
    {
        return AnimalView::with(['animal'])->find($id);
    }

    public function createAnimalView($data)
    {
        // update or create by external_chars
        $animalView = AnimalView::where([
            'external_chars' => $data['external_chars'], 
            'animal_id' => $data['animal_id']
        ])->first();
        
        if ($animalView) {
            $animalView->update([
                'value' => $data['value'],
                'expects' => $data['expects'],
            ]);
        } else {
            $animalView = AnimalView::create([
                'animal_id' => $data['animal_id'],
                'external_chars' => $data['external_chars'],
                'value' => $data['value'],
                'expects' => $data['expects'],
            ]);
        }
        return $animalView;
    }

    public function updateAnimalView($id, $data)
    {
        $animalView = AnimalView::find($id);
        if (!$animalView) {
            return null;
        }
        
        $animalView->update([
            'animal_id' => $data['animal_id'],
            'external_chars' => $data['external_chars'],
            'value' => $data['value'],
            'expects' => $data['expects'],
        ]);

        return $animalView;
    }

    public function deleteAnimalView($id)
    {
        $animalView = AnimalView::find($id);
        if (!$animalView) {
            return false;
        }
        return $animalView->delete();
    }

    public function getAnimalViewsByAnimal($animalId)
    {
        return AnimalView::where('animal_id', $animalId)->get();
    }
}
