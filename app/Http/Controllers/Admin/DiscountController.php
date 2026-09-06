<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    public function index(): View
    {
        return view('admin.discounts.index', [
            'discounts' => Discount::orderByDesc('is_active')->orderBy('code')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.discounts.form', ['discount' => new Discount(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Discount::create($this->validated($request));

        return redirect()->route('admin.discounts.index')->with('status', 'Kode promo ditambahkan.');
    }

    public function edit(Discount $discount): View
    {
        return view('admin.discounts.form', ['discount' => $discount]);
    }

    public function update(Request $request, Discount $discount): RedirectResponse
    {
        $discount->update($this->validated($request, $discount));

        return redirect()->route('admin.discounts.index')->with('status', "Kode {$discount->code} diperbarui.");
    }

    public function toggle(Discount $discount): RedirectResponse
    {
        $discount->update(['is_active' => ! $discount->is_active]);

        return back()->with('status', $discount->is_active
            ? "Kode {$discount->code} diaktifkan."
            : "Kode {$discount->code} dinonaktifkan.");
    }

    public function destroy(Discount $discount): RedirectResponse
    {
        $code = $discount->code;
        $discount->delete();

        return redirect()->route('admin.discounts.index')->with('status', "Kode {$code} dihapus.");
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?Discount $discount = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:30', Rule::unique('discounts')->ignore($discount?->id)],
            'label' => ['required', 'string', 'max:60'],
            'amount' => ['required', 'integer', 'min:0', 'max:1000000'],
            'min_spend' => ['required', 'integer', 'min:0', 'max:1000000'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
