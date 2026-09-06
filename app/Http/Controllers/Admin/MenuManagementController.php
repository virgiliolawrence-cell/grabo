<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MenuManagementController extends Controller
{
    public function index(Request $request): View
    {
        $items = MenuItem::query()
            ->when($request->string('q')->trim()->value(), fn ($query, $q) => $query
                ->where(fn ($sub) => $sub->where('name', 'like', "%{$q}%")->orWhere('stall', 'like', "%{$q}%")))
            ->when($request->string('kategori')->trim()->value(), fn ($query, $c) => $query->where('category', $c))
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.menu.index', [
            'items' => $items,
            'categories' => MenuItem::query()->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    public function create(): View
    {
        return view('admin.menu.form', ['item' => new MenuItem(['is_available' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']);

        MenuItem::create($data);

        return redirect()->route('admin.menu.index')->with('status', "Menu {$data['name']} ditambahkan.");
    }

    public function edit(MenuItem $menu): View
    {
        return view('admin.menu.form', ['item' => $menu]);
    }

    public function update(Request $request, MenuItem $menu): RedirectResponse
    {
        $menu->update($this->validated($request, $menu));

        return redirect()->route('admin.menu.index')->with('status', "Menu {$menu->name} diperbarui.");
    }

    /** Sembunyikan atau tampilkan menu tanpa menghapusnya. */
    public function toggle(MenuItem $menu): RedirectResponse
    {
        $menu->update(['is_available' => ! $menu->is_available]);

        return back()->with('status', $menu->is_available
            ? "{$menu->name} kembali dijual."
            : "{$menu->name} disembunyikan dari halaman siswa.");
    }

    public function destroy(MenuItem $menu): RedirectResponse
    {
        $name = $menu->name;
        $menu->delete();

        return redirect()->route('admin.menu.index')->with('status', "Menu {$name} dihapus.");
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?MenuItem $menu = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'stall' => ['required', 'string', 'max:60'],
            'category' => ['required', 'string', 'max:40'],
            'type' => ['required', Rule::in(['makanan', 'snack', 'minuman'])],
            'price' => ['required', 'integer', 'min:0', 'max:1000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:9999'],
            'badge' => ['nullable', 'string', 'max:30'],
            'summary' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'string', 'max:200'],
            'is_available' => ['nullable', 'boolean'],
        ]) + ['is_available' => $request->boolean('is_available')];
    }
}
