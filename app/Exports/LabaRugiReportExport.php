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

class LabaRugiReportExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles, WithEvents
{
    protected $data;

    public function __construct($penjualan_barang, $return_penjualan, $total_pendapatan_bersih, $pembelian, $retur_pembelian, $stock_opname, $expense_category)
    {
        $this->data = [
            'penjualan_barang' => $penjualan_barang,
            'return_penjualan' => $return_penjualan,
            'total_pendapatan_bersih' => $total_pendapatan_bersih,
            'pembelian' => $pembelian,
            'retur_pembelian' => $retur_pembelian,
            'stock_opname' => $stock_opname,
            'expense_category' => $expense_category
        ];
    }

    public function collection()
    {
        $rows = [];

        $rows[] = ['Keterangan', 'Jumlah', ''];
        $rows[] = ['Pendapatan', '', ''];
        $rows[] = ['Penjualan Barang', $this->data['penjualan_barang']['total_penjualan'], ''];
        $rows[] = ['Return Penjualan', -$this->data['return_penjualan'], ''];
        $rows[] = ['Total Pendapatan Bersih', $this->data['total_pendapatan_bersih'], ''];
        $rows[] = ['Harga Pokok Penjualan (HPP)', '', ''];
        $rows[] = ['Pembelian', $this->data['pembelian'], ''];
        $rows[] = ['Retur Pembelian', -$this->data['retur_pembelian'], ''];
        $rows[] = ['Stock Opname', -$this->data['stock_opname'], ''];

        $hpp = $this->data['pembelian'] - $this->data['retur_pembelian'] - ($this->data['stock_opname'] < 0 ? abs($this->data['stock_opname']) : -$this->data['stock_opname']);
        $rows[] = ['Total Harga Pokok Penjualan (HPP)', $hpp, ''];
        $rows[] = ['Laba Kotor', $this->data['total_pendapatan_bersih'] - $hpp, ''];
        $rows[] = ['Biaya Pengeluaran', '', ''];

        $totalExpense = 0;
        foreach ($this->data['expense_category']->where('source_table', 'expense_items')->groupBy('category_name') as $categoryName => $expenses) {
            $categoryTotal = $expenses->sum('total_amount');
            $totalExpense += $categoryTotal;
            $rows[] = [$categoryName, $categoryTotal, ''];
        }

        $rows[] = ['Biaya pembelian', $this->data['expense_category']->where('source_table', 'purchase_expenses')->sum('total_amount'), ''];
        $rows[] = ['Biaya Transfer Stok', $this->data['expense_category']->where('source_table', 'stock_transfer_expenses')->sum('total_amount'), ''];

        $totalExpense_all = $totalExpense + $this->data['expense_category']->where('source_table', 'purchase_expenses')->sum('total_amount') + $this->data['expense_category']->where('source_table', 'stock_transfer_expenses')->sum('total_amount');
        $rows[] = ['Total Biaya Pengeluaran', $totalExpense_all, ''];

        $laba_rugi_operasional = $this->data['total_pendapatan_bersih'] - $hpp - $totalExpense_all;
        $rows[] = ['Laba (Rugi) Operasional', $laba_rugi_operasional, ''];
        $rows[] = ['Laba (Rugi) Bersih Sebelum Pajak', $laba_rugi_operasional, ''];
        $rows[] = ['Pajak Penghasilan', $this->data['penjualan_barang']['total_tax'], ''];
        $rows[] = ['Laba (Rugi) Bersih Setelah Pajak', $laba_rugi_operasional - $this->data['penjualan_barang']['total_tax'], ''];

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            ['Laporan Laba Rugi'],
            []
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles($sheet)
    {
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'BAE3FF']],
        ]);
        
        $sheet->getStyle('A3:C3')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'BAE3FF']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        
        $sheet->getStyle('A4:C' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $sheet->getStyle('A4')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F0F0']]]);
        $sheet->getStyle('A8')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F0F0']]]);
        $sheet->getStyle('A14')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F0F0']]]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A3:C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
            },
        ];
    }
}
