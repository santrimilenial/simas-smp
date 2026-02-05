<?php

namespace App\Exports;

use App\Models\User;
use App\Models\AttendanceSetting;
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

class MonthlyAttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithCustomStartCell, WithColumnWidths
{
    protected $month;
    protected $year;
    protected $workingDays;
    protected $data;
    protected $totalTeachingHours = 0;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
        
        $settings = AttendanceSetting::current();
        $this->workingDays = $settings->getWorkingDaysCount($month, $year);
        
        // Fetch data
        $this->data = $this->getData();
    }

    protected function getData()
    {
        return User::where('role', 'guru')
            ->select(
                'users.id',
                'users.name',
                'users.niy',
                'users.email'
            )
            ->leftJoin('attendances', function ($join) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->whereYear('attendances.date', $this->year)
                    ->whereMonth('attendances.date', $this->month);
            })
            ->selectRaw('
                COUNT(CASE WHEN attendances.status = "present" THEN 1 END) as present_count,
                COUNT(CASE WHEN attendances.status = "late" THEN 1 END) as late_count,
                COUNT(CASE WHEN attendances.status = "absent" THEN 1 END) as absent_count,
                COUNT(CASE WHEN attendances.status = "permission" THEN 1 END) as permission_count,
                SUM(CASE WHEN attendances.teaching_hours IS NOT NULL THEN attendances.teaching_hours ELSE 0 END) as total_teaching_hours
            ')
            ->groupBy(
                'users.id',
                'users.name',
                'users.niy',
                'users.email'
            )
            ->orderBy('users.name')
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
            'Nama Guru',
            'NIY',
            'Hadir',
            'Terlambat',
            'Izin',
            'Total Jam Mengajar',
            'Hari Kerja',
            'Persentase Kehadiran (%)',
        ];
    }

    public function map($data): array
    {
        static $no = 0;
        $no++;

        $percentage = $this->workingDays > 0 
            ? ($data->present_count / $this->workingDays) * 100 
            : 0;

        $this->totalTeachingHours += $data->total_teaching_hours ?? 0;

        return [
            $no,
            $data->name,
            $data->niy,
            $data->present_count,
            $data->late_count,
            $data->permission_count,
            $data->total_teaching_hours ?? 0,
            $this->workingDays,
            number_format($percentage, 1),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 18,
            'D' => 10,
            'E' => 12,
            'F' => 8,
            'G' => 20,
            'H' => 12,
            'I' => 22,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            5 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3B82F6'],
                ],
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $monthName = \Carbon\Carbon::create($this->year, $this->month, 1)->isoFormat('MMMM YYYY');
                
                // Header info
                $sheet->setCellValue('A1', 'LAPORAN ABSENSI BULANAN');
                $sheet->setCellValue('A2', 'Periode: ' . $monthName);
                $sheet->setCellValue('A3', 'Dicetak: ' . now()->format('d/m/Y H:i'));
                
                // Style header
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getFont()->setSize(11);
                $sheet->getStyle('A3')->getFont()->setSize(10)->setItalic(true);
                
                // Get last row
                $lastRow = $this->data->count() + 5;
                
                // Add border to data
                $sheet->getStyle('A5:I' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                
                // Center align number columns
                $sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D5:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Add summary row
                $summaryRow = $lastRow + 2;
                $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN');
                $sheet->getStyle('A' . $summaryRow)->getFont()->setBold(true)->setSize(12);
                
                $sheet->setCellValue('A' . ($summaryRow + 1), 'Total Guru:');
                $sheet->setCellValue('B' . ($summaryRow + 1), $this->data->count() . ' orang');
                
                $sheet->setCellValue('A' . ($summaryRow + 2), 'Total Jam Mengajar:');
                $sheet->setCellValue('B' . ($summaryRow + 2), $this->data->sum('total_teaching_hours') . ' jam pelajaran');
                
                $sheet->setCellValue('A' . ($summaryRow + 3), 'Hari Kerja:');
                $sheet->setCellValue('B' . ($summaryRow + 3), $this->workingDays . ' hari');
                
                $totalPresent = $this->data->sum('present_count');
                $totalPossible = $this->workingDays * $this->data->count();
                $avgPresent = $totalPossible > 0 ? ($totalPresent / $totalPossible) * 100 : 0;
                
                $sheet->setCellValue('A' . ($summaryRow + 4), 'Rata-rata Kehadiran:');
                $sheet->setCellValue('B' . ($summaryRow + 4), number_format($avgPresent, 1) . '%');
                
                // Style summary
                $sheet->getStyle('A' . ($summaryRow + 1) . ':A' . ($summaryRow + 4))->getFont()->setBold(true);
                
                // Freeze header
                $sheet->freezePane('A6');
            },
        ];
    }
}
