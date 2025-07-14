<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;

class ExportHelper
{
    public static function exportToCsv(array $csvData, string $filename = null): string
    {
        if (empty($csvData)) {
            throw new \Exception('No data to export.');
        }

        $filename = $filename ?? 'export_' . now()->format('Ymd_His') . '.csv';
        $tempPath = storage_path('app/tmp/' . $filename);

        // Ensure tmp directory exists
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0777, true);
        }

        $handle = fopen($tempPath, 'w+');
        fputcsv($handle, array_keys($csvData[0]));
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        return $tempPath;
    }
    public static function exportToMedia(array $csvData, $model, string $collection = 'exports', string $filename = null)
    {
        $filePath = self::exportToCsv($csvData, $filename);
        $media = $model->addMedia($filePath)
            ->usingName(basename($filePath))
            ->toMediaCollection($collection);
        // No need to unlink($filePath) as Spatie handles it
        return $media;
    }

    public static function exportToPdf(array $data, string $view, string $filename = null): string
    {
        if (empty($data)) {
            throw new \Exception('No data to export.');
        }

        $filename = $filename ?? 'export_' . now()->format('Ymd_His') . '.pdf';
        $tempPath = storage_path('app/tmp/' . $filename);

        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0777, true);
        }

        $pdf = Pdf::loadView($view, ['data' => $data]);
        $pdf->save($tempPath);

        return $tempPath;
    }

    public static function exportPdfToMedia(array $data, string $view, $model, string $collection = 'exports', string $filename = null)
    {
        $filePath = self::exportToPdf($data, $view, $filename);
        $media = $model->addMedia($filePath)
            ->usingName(basename($filePath))
            ->toMediaCollection($collection);
        return $media;
    }
} 