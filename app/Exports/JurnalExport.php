<?php

namespace App\Exports;

use App\Models\TeachingLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JurnalExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithCustomStartCell
{
    protected $startDate;
    protected $endDate;
    protected $guruId;
    protected $filterClass;
    protected $academicYearInfo;

    public function __construct($startDate, $endDate, $guruId = null, $filterClass = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->guruId = $guruId;
        $this->filterClass = $filterClass;
        
        // Get academic year info from logs
        $logs = $this->getLogsQuery()->get();
        $this->academicYearInfo = $logs->filter(fn($log) => $log->academicYear)
            ->pluck('academicYear.full_name')
            ->unique()
            ->implode(', ');
    }

    protected function getLogsQuery()
    {
        return TeachingLog::with(['user', 'academicYear'])
            ->when($this->guruId, fn($q) => $q->where('user_id', $this->guruId))
            ->when($this->filterClass, fn($q) => $q->byClass($this->filterClass))
            ->dateRange($this->startDate, $this->endDate)
            ->recent();
    }

    public function collection()
    {
        return $this->getLogsQuery()->get();
    }

    public function startCell(): string
    {
        return 'A4'; // Start data from row 4 (after header info)
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nama Guru',
            'NIY',
            'Mata Pelajaran',
            'Kelas',
            'Pertemuan',
            'Tujuan Pembelajaran',
            'Jam',
            'Materi',
            'Catatan',
        ];
    }

    public function map($log): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $log->log_date->format('d/m/Y'),
            $log->user->name,
            $log->user->niy,
            $log->subject,
            $log->class,
            $log->meeting_number,
            $log->tp,
            $log->time_slot,
            $log->material,
            $log->notes ?? '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Add header info at top
                $sheet->setCellValue('A1', 'LAPORAN JURNAL MENGAJAR');
                $sheet->setCellValue('A2', 'Periode: ' . \Carbon\Carbon::parse($this->startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($this->endDate)->format('d M Y'));
                
                if ($this->academicYearInfo) {
                    $sheet->setCellValue('A3', 'Tahun Ajaran: ' . $this->academicYearInfo);
                }
                
                // Style header
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getFont()->setSize(11);
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
                
                // Merge cells for title
                $sheet->mergeCells('A1:K1');
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            4 => ['font' => ['bold' => true]], // Row 4 is now the heading row
        ];
    }
}