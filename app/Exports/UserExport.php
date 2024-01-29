<?php

namespace App\Exports;

use App\Traits\ExportGambar;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class UserExport implements FromView, WithEvents
{
    use ExportGambar;

    public $data;
    public $request;

    public function __construct($data, $request)
    {
        $this->data = $data;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('backend.users.export', ['data' => $this->data, 'request' => $this->request]);
    }

    public function registerEvents(): array
    {
        if ($this->request['ext'] == 'foto') {
            return [AfterSheet::class => function (AfterSheet $event) {
                $records = $this->data;
                $nomor = 2;
                foreach ($records as $row) {
                    $this->simpanGambar($event, 'G' . $nomor, $row->foto, 0, 130);
                    $event->sheet->getDelegate()->getRowDimension($nomor)->setRowHeight(100);
                    $nomor++;
                }
            }];
        } else {
            return [];
        }
    }

    public function __destruct()
    {
        if (env('FILESYSTEM_DISK') === 's3') {
            foreach ($this->data as $item) {
                if ($item->foto) {
                    $url = base_url($item->foto);
                    $name = substr($url, strrpos($url, '/') + 1);
                    File::delete(public_path('storage/export/' . $name));
                }
            }
        }
    }
}
