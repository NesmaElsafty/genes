<?php

namespace App\Services;

use App\Models\Term;
use App\Helpers\ExportHelper;

class TermService
{
    public function getAllTerms($data, $role)
    {
        $query = Term::query();

        if (isset($data['search'])) {
            $query->where(function ($q) use ($data) {
                $q->where('title', 'like', '%' . $data['search'] . '%')->orWhere('description', 'like', '%' . $data['search'] . '%');
            });
        }

        if (isset($data['type'])) {
            $query->where('type', $data['type']);
        }

        if (isset($data['role'])) {
            $query->where('role', $data['role']);
        }

        if (isset($data['is_active'])) {
            $query->where('is_active', $data['is_active']);
        }

        if (isset($data['sorted_by'])) {
            switch ($data['sorted_by']) {
                case 'title':
                    $query->orderBy('title', $data['sorted_by_order'] ?? 'asc');
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
            'total_terms' => Term::count(),
            'total_active_terms' => Term::where('is_active', true)->count(),
            'total_inactive_terms' => Term::where('is_active', false)->count(),
            'total_terms_type' => Term::where('type', 'terms')->count(),
            'total_privacy_policy' => Term::where('type', 'privacy_policy')->count(),
        ];
        return $stats;
    }

    public function getTermById($id)
    {
        return Term::find($id);
    }

    public function createTerm($data)
    {
        $term = new Term();
        $term->type = $data['type'];
        $term->role = $data['role'] ?? null;
        $term->title = $data['title'];
        $term->description = $data['description'];
        $term->is_active = $data['is_active'] ?? true;
        $term->save();

        return $term;
    }

    public function updateTerm($id, $data)
    {
        $term = Term::find($id);
        if (!$term) {
            return null;
        }

        if (isset($data['type'])) {
            $term->type = $data['type'];
        }
        if (isset($data['role'])) {
            $term->role = $data['role'];
        }
        if (isset($data['title'])) {
            $term->title = $data['title'];
        }
        if (isset($data['description'])) {
            $term->description = $data['description'];
        }
        if (isset($data['is_active'])) {
            $term->is_active = $data['is_active'];
        }

        $term->save();
        return $term;
    }

    public function deleteTerm($id)
    {
        $term = Term::find($id);
        if (!$term) {
            return false;
        }

        return $term->delete();
    }

    public function toggleTerm($id)
    {
        $term = Term::find($id);
        if (!$term) {
            return null;
        }

        $term->is_active = !$term->is_active;
        $term->save();
        return $term;
    }

    public function bulkToggle($ids)
    {
        foreach ($ids as $id) {
            $this->toggleTerm($id);
        }
        return true;
    }

    public function bulkDelete($ids)
    {
        return Term::whereIn('id', $ids)->delete();
    }

    // export sheet
    public function exportSheet($id)
    {
        $term = Term::find($id);

        $data = [
                'type' => $term->type,
                'title' => $term->title,
                'description' => $term->description,
                'created_at' => $term->created_at,
        ];

        $filename = 'terms_export_' . now()->format('Ymd_His') . '.pdf';
        $media = ExportHelper::exportPdfToMedia($data, 'exports.pdf_table', $term, 'exports', $filename);

        return $media->getFullUrl();
    }
}
