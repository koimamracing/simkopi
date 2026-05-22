<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();
        if ($request->search) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->type) {
            $query->where('kategori', $request->type);
        }

        if ($request->status == 'Habis') {
            $query->where('stok', 0);
        } elseif ($request->status == 'Hampir Habis') {
            $query->whereBetween('stok', [1, 5]);
        } elseif ($request->status == 'Normal') {
            $query->where('stok', '>', 5);
        }

        $produk = $query->latest()->paginate(10);

        return view('menu.produk', compact('produk'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('produk', 'public');
        }

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto' => $foto,
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $foto = $produk->foto;
        if ($request->hasFile('foto')) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $foto = $request->file('foto')
                ->store('produk', 'public');
        }

        $produk->update([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto' => $foto
        ]);

        return redirect()->route('produk.index')
    ->with('success', 'Produk berhasil diupdate');
        
    }
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        return view('menu/editproduk', compact('produk'));
        
    }
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }
        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus');
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);

        return view('detailproduk', compact('produk'));
    }
}
