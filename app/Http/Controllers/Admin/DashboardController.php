<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Ringkasan: tiga kartu angka + transaksi terbaru + menu terlaris.
     */
    public function index(): View
    {
        $today = now()->startOfDay();

        return view('admin.dashboard', [
            'stats' => [
                [
                    'label' => 'Pendapatan hari ini',
                    'value' => 'Rp ' . number_format(
                        Order::where('status', 'selesai')->where('created_at', '>=', $today)->sum('total'),
                        0, ',', '.'
                    ),
                    'note' => 'Dari pesanan berstatus selesai',
                ],
                [
                    'label' => 'Pesanan hari ini',
                    'value' => (string) Order::where('created_at', '>=', $today)->count(),
                    'note' => Order::whereIn('status', ['menunggu', 'disiapkan'])->count() . ' masih diproses',
                ],
                [
                    'label' => 'Siswa terdaftar',
                    'value' => (string) Student::where('is_active', true)->count(),
                    'note' => MenuItem::available()->count() . ' menu sedang tersedia',
                ],
            ],

            'recentOrders' => Order::with('items')->latest()->take(8)->get(),

            // Menu terlaris dihitung dari item pesanan, bukan kolom 'sold' statis.
            'topItems' => OrderItem::query()
                ->selectRaw('name, stall, SUM(qty) as total_qty, SUM(price * qty) as total_value')
                ->groupBy('name', 'stall')
                ->orderByDesc('total_qty')
                ->take(5)
                ->get(),
        ]);
    }
}
