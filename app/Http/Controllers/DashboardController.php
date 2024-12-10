<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Dashboard;
class DashboardController extends Controller
{
    protected $menuService;

    public function __construct()
    {
     
    }

    public function index()
{
    $user = auth()->user();
    $username = $user->name;
    $roles = $user->roles->pluck('name')->toArray();
    $stores = Dashboard::getAllStores();
    $totalSalesAll = Dashboard::getTotalSalesAll();
    $totalPurchaseAll = Dashboard::getTotalPurchaseAll();
    $totalReturnAll = Dashboard::getTotalReturnAll();
    $totalExpenseAll = Dashboard::getTotalExpenseAll();
    
        // Data chart bulanan
    $getsaleschart = $this->getMonthlySales();

    // Data chart harian
    $getsaleschartDaily = $this->getDailySales();

    return view('dashboard', compact('username', 'roles', 'stores', 'totalSalesAll', 'totalPurchaseAll', 'totalReturnAll', 'totalExpenseAll', 'getsaleschart', 'getsaleschartDaily'));
}


    public function getMonthlySales($storeId = null)
    {
        $year = date('Y');
        $salesData = Dashboard::getSalesChart($year, $storeId);
    
        $formattedData = [];
        foreach ($salesData as $data) {
            if (!isset($formattedData[$data['store_name']])) {
                $formattedData[$data['store_name']] = array_fill(0, 12, 0);
            }
            $formattedData[$data['store_name']][$data['month'] - 1] = $data['total_sales'];
        }
    
        return $formattedData;
    }

    public function getDailySales($storeId = null)
{
    // Mengambil data untuk bulan berjalan
    $startDate = now()->startOfMonth()->format('Y-m-d');
    $endDate = now()->endOfMonth()->format('Y-m-d'); // Akhir bulan sesuai
    
    // Ambil data penjualan harian berdasarkan tanggal
    return Dashboard::getSalesChartDaily($startDate, $endDate, $storeId);
}

    
    
}
