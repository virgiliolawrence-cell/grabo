<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\ItemPesanan;
use App\Models\Pesanan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelolaPembelianController extends Controller
{
    /**
     * Halaman Manajemen Pembelian: semua pesanan murid, terbaru di atas.
     */
    public function daftar(Request $permintaan): View
    {
        $status = $permintaan->string('status')->value();
        $statusAktif = isset(Pesanan::STATUS[$status]) ? $status : null;
        $cari = $permintaan->string('cari')->trim()->value();

        $daftarPesanan = Pesanan::query()
            ->with('item')
            ->when($statusAktif, fn ($kueri) => $kueri->where('status', $statusAktif))
            ->when($cari !== '', fn ($kueri) => $kueri->where(
                fn ($kueri) => $kueri->where('kode', 'like', "%{$cari}%")->orWhere('nama', 'like', "%{$cari}%")
            ))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('penjual.pembelian', [
            'daftarPesanan' => $daftarPesanan,
            'jumlahPerStatus' => Pesanan::query()->toBase()->selectRaw('status, count(*) as jumlah')->groupBy('status')->pluck('jumlah', 'status'),
            'jumlahSemua' => Pesanan::query()->count(),
            'statusAktif' => $statusAktif,
        ]);
    }

    /**
     * Rincian satu pesanan beserta daftar centangnya.
     */
    public function tampilkan(Pesanan $pesanan): View
    {
        return view('penjual.pembelian-rincian', [
            'pesanan' => $pesanan->load(['item', 'pengguna']),
        ]);
    }

    /**
     * Ubah status pesanan. Pesanan yang selesai otomatis tercentang semua.
     */
    public function ubahStatus(Request $permintaan, Pesanan $pesanan): RedirectResponse
    {
        $isian = $permintaan->validate([
            'status' => ['required', Rule::in(array_keys(Pesanan::STATUS))],
        ]);

        $pesanan->update(['status' => $isian['status']]);

        if (in_array($isian['status'], ['siap', 'selesai'], true)) {
            $pesanan->item()->update(['sudah_disiapkan' => true]);
        }

        return $this->kembaliKePesanan($pesanan, "Pesanan {$pesanan->kode} sekarang berstatus {$pesanan->labelStatus()}.");
    }

    /**
     * Centang / batalkan centang satu menu di dalam pesanan.
     *
     * Status ikut maju: centang pertama → diproses, semua tercentang → siap diambil.
     */
    public function centangItem(Pesanan $pesanan, ItemPesanan $item): RedirectResponse
    {
        abort_unless($item->pesanan_id === $pesanan->id, 404);
        abort_unless($pesanan->masihBerjalan(), 422, 'Pesanan ini sudah ditutup.');

        $item->update(['sudah_disiapkan' => ! $item->sudah_disiapkan]);

        $semuaItem = $pesanan->item()->get();
        $jumlahSiap = $semuaItem->where('sudah_disiapkan', true)->count();

        $statusBaru = match (true) {
            $jumlahSiap === $semuaItem->count() => 'siap',
            $jumlahSiap > 0 => 'diproses',
            default => $pesanan->status === 'siap' ? 'diproses' : $pesanan->status,
        };

        $pesanan->update(['status' => $statusBaru]);

        return $this->kembaliKePesanan($pesanan, $item->sudah_disiapkan
            ? "{$item->nama} untuk {$pesanan->nama} sudah disiapkan."
            : "Centang {$item->nama} dibatalkan.");
    }

    /**
     * Kembali ke halaman sebelumnya, tepat di kartu/baris pesanan yang diubah.
     */
    private function kembaliKePesanan(Pesanan $pesanan, string $pesan): RedirectResponse
    {
        return redirect()->to(strtok(url()->previous(), '#').'#pesanan-'.$pesanan->kode)->with('status', $pesan);
    }
}
