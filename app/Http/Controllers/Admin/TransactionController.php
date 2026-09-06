<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /** Label metode bayar, supaya penyaring dan tabel memakai kata yang sama. */
    private const METODE = [
        'tunai' => 'Tunai di stan',
        'kartu-pelajar' => 'Kartu pelajar',
        'qris' => 'QRIS',
        'transfer' => 'Transfer / VA',
    ];

    /**
     * Laporan transaksi dengan penyaring tanggal, status, dan metode bayar.
     */
    public function index(Request $request): View
    {
        $dari = $request->date('dari') ?? now()->subDays(13)->startOfDay();
        $sampai = $request->date('sampai') ?? now()->endOfDay();

        $query = Order::query()
            ->with('items')
            ->whereBetween('created_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()])
            ->when($request->string('status')->trim()->value(), fn ($q, $s) => $q->where('status', $s))
            ->when($request->string('metode')->trim()->value(), fn ($q, $m) => $q->where('payment_method', $m));

        // Ringkasan dihitung dari query yang sama supaya ikut menyaring.
        $ringkasan = (clone $query)
            ->selectRaw('COUNT(*) as jumlah, SUM(total) as omzet, SUM(discount_amount) as diskon')
            ->first();

        $selesai = (clone $query)->where('status', 'selesai');

        return view('admin.transactions.index', [
            'orders' => $query->latest()->paginate(15)->withQueryString(),
            'metodeBayar' => self::METODE,
            'dari' => $dari->toDateString(),
            'sampai' => $sampai->toDateString(),
            'ringkasan' => [
                'jumlah' => (int) ($ringkasan->jumlah ?? 0),
                'omzet' => (int) ($ringkasan->omzet ?? 0),
                'diskon' => (int) ($ringkasan->diskon ?? 0),
                'omzet_selesai' => (int) $selesai->sum('total'),
            ],
            'perMetode' => (clone $query)
                ->selectRaw('payment_method, COUNT(*) as jumlah, SUM(total) as nilai')
                ->groupBy('payment_method')
                ->orderByDesc('nilai')
                ->get(),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.transactions.show', [
            'order' => $order->load('items', 'discount'),
            'metodeBayar' => self::METODE,
        ]);
    }

    /** Ubah status pesanan dari daftar laporan. */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['menunggu', 'disiapkan', 'selesai', 'batal'])],
        ]);

        $order->update($data);

        return back()->with('status', "Pesanan {$order->code} ditandai {$data['status']}.");
    }
}
