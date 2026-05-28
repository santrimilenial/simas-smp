<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $condition;
    protected $category;
    protected $location;
    protected $search;

    public function __construct($condition = null, $category = null, $location = null, $search = null)
    {
        $this->condition = $condition;
        $this->category = $category;
        $this->location = $location;
        $this->search = $search;
    }

    public function collection()
    {
        $query = Item::query();

        if ($this->condition) {
            $query->where('condition', $this->condition);
        }
        if ($this->category) {
            $query->where('category', $this->category);
        }
        if ($this->location) {
            $query->where('location', $this->location);
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode',
            'Nama Barang',
            'Kategori',
            'Lokasi',
            'Kondisi',
            'Jumlah',
            'Harga Satuan (Rp)',
            'Total Nilai (Rp)',
            'Tanggal Pembelian',
            'Deskripsi',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->code,
            $item->name,
            $item->category ?? '-',
            $item->location ?? '-',
            ucfirst($item->condition),
            $item->quantity,
            $item->price ? number_format($item->price, 0, ',', '.') : '-',
            $item->price ? number_format($item->price * $item->quantity, 0, ',', '.') : '-',
            $item->purchase_date?->format('d/m/Y') ?? '-',
            $item->description ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2563EB'],
                ],
            ],
        ];
    }
}
