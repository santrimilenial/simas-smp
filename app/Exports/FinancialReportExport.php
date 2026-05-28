<?php

namespace App\Exports;

use App\Models\FinancialRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class FinancialReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithCustomStartCell, WithColumnWidths
{
    protected $month;
    protected $year;
    protected $data;
    protected $totalIncome;
    protected $totalExpense;
    protected $carryOver;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
        $this->data = $this->getData();
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $prevIncome = FinancialRecord::where('record_date', '<', $startOfMonth)->income()->sum('amount');
        $prevExpense = FinancialRecord::where('record_date', '<', $startOfMonth)->expense()->sum('amount');
        $this->carryOver = $prevIncome - $prevExpense;
        $this->totalIncome = FinancialRecord::forMonth($month, $year)->income()->sum('amount');
        $this->totalExpense = FinancialRecord::forMonth($month, $year)->expense()->sum('amount');
    }

    protected function getData()
    {
        return FinancialRecord::with('creator')
            ->forMonth($this->month, $this->year)
            ->orderBy('record_date')
            ->orderBy('created_at')
            ->get();
    }

    public function collection()
    {
        return $this->data;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Jenis',
            'Kategori',
            'Deskripsi',
            'Jumlah (Rp)',
            'Catatan',
            'Dicatat Oleh',
        ];
    }

    public function map($record): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $record->record_date->format('d/m/Y'),
            $record->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
            $record->category,
            $record->description,
            $record->amount,
            $record->notes ?? '-',
            $record->creator->name ?? '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 14,
            'C' => 14,
            'D' => 18,
            'E' => 35,
            'F' => 18,
            'G' => 25,
            'H' => 20,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => '1F2937']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'BBF7D0'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $monthName = Carbon::create($this->year, $this->month, 1)->isoFormat('MMMM YYYY');

                // Header info
                $sheet->setCellValue('A1', 'LAPORAN KEUANGAN BULANAN');
                $sheet->setCellValue('A2', 'Periode: ' . $monthName);
                $sheet->setCellValue('A3', 'Dicetak: ' . now()->format('d/m/Y H:i'));

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getFont()->setSize(11);
                $sheet->getStyle('A3')->getFont()->setSize(10)->setItalic(true);

                $lastRow = $this->data->count() + 5;

                // Borders
                $sheet->getStyle('A5:H' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Center align columns A, B, C
                $sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B5:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Number format for amount column F (rows below header)
                $dataStart = 6;
                if ($this->data->count() > 0) {
                    $sheet->getStyle("F{$dataStart}:F{$lastRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                    $sheet->getStyle("F{$dataStart}:F{$lastRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }

                // Summary section
                $summaryRow = $lastRow + 2;
                $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN');
                $sheet->getStyle('A' . $summaryRow)->getFont()->setBold(true)->setSize(12);

                $sheet->setCellValue('D' . ($summaryRow + 1), 'Saldo Awal:');
                $sheet->setCellValue('F' . ($summaryRow + 1), $this->carryOver);
                $sheet->getStyle('D' . ($summaryRow + 1))->getFont()->setBold(true);
                $sheet->getStyle('F' . ($summaryRow + 1))->getFont()->setBold(true)->getColor()->setRGB($this->carryOver >= 0 ? 'B45309' : 'DC2626');
                $sheet->getStyle('F' . ($summaryRow + 1))->getNumberFormat()->setFormatCode('#,##0');

                $sheet->setCellValue('D' . ($summaryRow + 2), 'Total Pemasukan:');
                $sheet->setCellValue('F' . ($summaryRow + 2), $this->totalIncome);
                $sheet->getStyle('D' . ($summaryRow + 2))->getFont()->setBold(true);
                $sheet->getStyle('F' . ($summaryRow + 2))->getFont()->setBold(true)->getColor()->setRGB('16A34A');
                $sheet->getStyle('F' . ($summaryRow + 2))->getNumberFormat()->setFormatCode('#,##0');

                $sheet->setCellValue('D' . ($summaryRow + 3), 'Total Pengeluaran:');
                $sheet->setCellValue('F' . ($summaryRow + 3), $this->totalExpense);
                $sheet->getStyle('D' . ($summaryRow + 3))->getFont()->setBold(true);
                $sheet->getStyle('F' . ($summaryRow + 3))->getFont()->setBold(true)->getColor()->setRGB('DC2626');
                $sheet->getStyle('F' . ($summaryRow + 3))->getNumberFormat()->setFormatCode('#,##0');

                $balance = $this->carryOver + $this->totalIncome - $this->totalExpense;
                $sheet->setCellValue('D' . ($summaryRow + 4), 'Saldo Akhir:');
                $sheet->setCellValue('F' . ($summaryRow + 4), $balance);
                $sheet->getStyle('D' . ($summaryRow + 4))->getFont()->setBold(true);
                $sheet->getStyle('F' . ($summaryRow + 4))->getFont()->setBold(true)->getColor()->setRGB($balance >= 0 ? '16A34A' : 'DC2626');
                $sheet->getStyle('F' . ($summaryRow + 4))->getNumberFormat()->setFormatCode('#,##0');

                // Border for summary
                $sheet->getStyle('D' . ($summaryRow + 1) . ':F' . ($summaryRow + 4))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->setCellValue('D' . ($summaryRow + 6), 'Total Transaksi: ' . $this->data->count());
                $sheet->getStyle('D' . ($summaryRow + 6))->getFont()->setItalic(true);

                // Freeze pane
                $sheet->freezePane('A6');
            },
        ];
    }
}
