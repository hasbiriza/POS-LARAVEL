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

class PurchaseReportExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles, WithEvents
{
    protected $transactions;
    protected $startDate;
    protected $endDate;
    protected $storeName;

    public function __construct($transactions, $startDate, $endDate, $storeName)
    {
        $this->transactions = $transactions;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->storeName = $storeName;
    }

    public function collection()
    {
        return collect($this->transactions)->map(function($transaction) {
            $transaction->total_purchase = floatval(str_replace(',', '', $transaction->total_purchase));
            $transaction->total_return = floatval(str_replace(',', '', $transaction->total_return));
            $transaction->net_total = floatval(str_replace(',', '', $transaction->net_total));
            return $transaction;
        });
    }

    public function headings(): array
    {
        return [
            ['Laporan Pembelian', '', '', '', 'Toko: ' . $this->storeName],
            ['Periode: ' . $this->startDate . ' - ' . $this->endDate],
            [],
            ['No. Invoice', 'Tanggal Transaksi', 'Total Pembelian', 'Total Retur', 'Total Bersih']
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, 
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, 
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '77DE4E']],
        ]);
        
        $sheet->getStyle('E1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
        ]);
        
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
        ]);
        
        $sheet->getStyle('A4:E4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        
        $sheet->getStyle('A5:E' . ($this->transactions->count() + 4))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('A4:E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                
                $event->sheet->getDelegate()->getStyle('C5:E' . ($this->transactions->count() + 4))->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
