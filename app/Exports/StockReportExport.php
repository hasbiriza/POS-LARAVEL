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

class StockReportExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles, WithEvents
{
    protected $report;
    protected $storeName;

    public function __construct($report, $storeName)
    {
        $this->report = $report;
        $this->storeName = $storeName;
    }

    public function collection()
    {
        return collect($this->report)->map(function($item) {
            return [
                '',
                $item->product_name . 
                    ($item->variasi_1 ? ' ' . $item->variasi_1 : '') . 
                    ($item->variasi_2 ? ' ' . $item->variasi_2 : '') . 
                    ($item->variasi_3 ? ' ' . $item->variasi_3 : ''),
                $item->sku,
                $item->barcode,
                $item->initial_stock,
                $item->purchased_stock,
                $item->sold_stock,
                $item->transfer_out_stock,
                $item->transfer_in_stock,
                $item->stock_difference,
                $item->initial_stock + $item->purchased_stock - $item->sold_stock + $item->transfer_in_stock - $item->transfer_out_stock + $item->stock_difference,
                $item->unit_price,
                $item->unit_price * ($item->initial_stock + $item->purchased_stock - $item->sold_stock + $item->transfer_in_stock - $item->transfer_out_stock + $item->stock_difference)
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Laporan Stok', '', '', '', '', '', '', '', '', '', '', '', 'Toko: ' . $this->storeName],
            [],
            ['', 'Nama Produk', 'SKU', 'Barcode', 'Stok Awal', 'Pembelian', 'Penjualan', 'Transfer Keluar', 'Transfer Masuk', 'Penyesuaian Stok', 'Stok Akhir', 'Harga Jual', 'Total Nilai']
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '77DE4E']],
        ]);
        
        $sheet->getStyle('M1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
        ]);
        
        $sheet->getStyle('A3:M3')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        
        $sheet->getStyle('A4:M' . ($this->report->count() + 3))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('A3:M3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
                
                $event->sheet->getDelegate()->getStyle('E4:M' . ($this->report->count() + 3))->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
