<?php

namespace App\Services;

use App\Models\Faq;
use App\Helpers\ExportHelper;

class FaqService
{
    public function getAllFaqs($data, $role)
    {
        $query = Faq::query();
        
        if (isset($data['search'])) {
            $query->where(function ($q) use ($data) {
                $q->where('question', 'like', '%' . $data['search'] . '%')
                  ->orWhere('answer', 'like', '%' . $data['search'] . '%');
            });
        }
        
        if (isset($data['role'])) {
            $query->where('role', $data['role']);
        }

        if (isset($data['is_active'])) {
            $query->where('is_active', $data['is_active']);
        }

        if (isset($data['sorted_by'])) {
            switch ($data['sorted_by']) {
                case 'question':
                    $query->orderBy('question', $data['sorted_by_order'] ?? 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function stats()
    {
        $stats = [
            'total_faqs' => Faq::count(),
            'total_active_faqs' => Faq::where('is_active', true)->count(),
            'total_inactive_faqs' => Faq::where('is_active', false)->count(),
        ];
        return $stats;
    }

    public function getFaqById($id)
    {
        return Faq::find($id);
    }

    public function createFaq($data)
    {
        $faq = new Faq();
        $faq->question = $data['question'];
        $faq->answer = $data['answer'];
        $faq->is_active = $data['is_active'] ?? true;
        $faq->role = $data['role'] ?? null;
        $faq->save();

        return $faq;
    }

    public function updateFaq($id, $data)
    {
        $faq = Faq::find($id);
        if (!$faq) {
            return null;
        }

        if (isset($data['question'])) {
            $faq->question = $data['question'];
        }
        if (isset($data['answer'])) {
            $faq->answer = $data['answer'];
        }
        if (isset($data['is_active'])) {
            $faq->is_active = $data['is_active'];
        }
        if (isset($data['role'])) {
            $faq->role = $data['role'];
        }

        $faq->save();
        return $faq;
    }

    public function deleteFaq($id)
    {
        $faq = Faq::find($id);
        if (!$faq) {
            return false;
        }

        return $faq->delete();
    }

    public function toggleFaq($id)
    {
        $faq = Faq::find($id);
        if (!$faq) {
            return null;
        }

        $faq->is_active = !$faq->is_active;
        $faq->save();
        return $faq;
    }

    public function bulkToggle($ids)
    {
        foreach ($ids as $id) {
            $this->toggleFaq($id);
        }
        return true;
    }

    public function bulkDelete($ids)
    {
        return Faq::whereIn('id', $ids)->delete();
    }

    // export sheet
    public function exportSheet($ids, $user)
    {
        if (empty($ids)) {
            // لو مفيش ids → نجيب كل المستخدمين باـ role المطلوب
            $faqs = Faq::all();
        } else {
            // لو فيه ids → نجيب فقط اللي اـ id بتاعه في القامة
            $faqs = Faq::whereIn('id', $ids)->get();
        }

        $csvData = [];

        foreach ($faqs as $faq) {
            $csvData[] = [
                'id' => $faq->id,
                'role' => $faq->role,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'is_active' => $faq->is_active,
            ];
        }

            $filename = 'faqs_export_' . now()->format('Ymd_His') . '.csv';
        $media = ExportHelper::exportToMedia($csvData, $user, 'exports', $filename);

        return $media->getFullUrl();
    }
}