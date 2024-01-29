<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ExportGambar
{

    public function simpanGambar($event, $position, $path, $width, $height)
    {
        if ($path != '' || $path != null) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setCoordinates($position);

            if (env('FILESYSTEM_DISK') === 's3') {
                $url = base_url($path);
                $contents = file_get_contents($url);
                $name = substr($url, strrpos($url, '/') + 1);
                Storage::disk('public')->put('export/' . $name, $contents);
                $drawing->setPath(public_path('storage/export/' . $name));
            } else {
                $drawing->setPath(public_path('storage/' . $path));
            }

            ($width == 0) ? null : $drawing->setWidth($width);
            ($height == 0) ? null : $drawing->setHeight($height);
            $drawing->setWorksheet($event->sheet->getDelegate());
        }
    }
}
