<?php 
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BankReportExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles, WithEvents
{
    protected $bank_report;
    protected $storeName;
    protected $startDate;
    protected $endDate;

    public function __construct($storeName, $bank_report, $startDate, $endDate)
    {
        $this->bank_report = $bank_report;
        $this->storeName = $storeName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $balance = 0;
        return collect($this->bank_report)->map(function($report, $index) use (&$balance) {
            $amount = $report->payment_amount;
            $balance += $report->type == 'Kredit' ? $amount : -$amount;
            return [
                '',
                date('Y-m-d', strtotime($report->payment_date)),
                $report->transaction_id,
                $report->transaction_type,
                $report->payment_method,
                $report->bank_name ?? '-',
                $report->account_number ?? '-',
                $report->type == 'Debit' ? $amount : '',
                $report->type == 'Kredit' ? $amount : '',
                $balance
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Laporan Bank', '', '', '', '', '', '', '', '', 'Toko: ' . $this->storeName],
            ['Periode: ' . $this->startDate . ' - ' . $this->endDate],
            [],
            ['', 'Tanggal', 'ID Transaksi', 'Jenis Transaksi', 'Metode Pembayaran', 'Nama Bank', 'Nomor Rekening', 'Debit', 'Kredit', 'Saldo']
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '77DE4E']],
        ]);
        
        $sheet->getStyle('J1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
        ]);
        
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
        ]);
        
        $sheet->getStyle('A4:J4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        
        $sheet->getStyle('A5:J' . ($this->bank_report->count() + 4))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('A4:J4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(15);
                
                $event->sheet->getDelegate()->getStyle('H5:J' . ($this->bank_report->count() + 4))->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
