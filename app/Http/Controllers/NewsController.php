<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Tampilkan daftar berita.
     */
    public function index()
    {
        $news = News::query()
            ->with('author')
            ->latest()
            ->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    /**
     * Form tambah berita.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Simpan berita baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['title', 'content']);
        $data['author_id'] = Auth::id();
        $data['is_active'] = (bool) $request->input('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        News::create($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Form edit berita.
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update berita.
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title'   => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['title', 'content']);
        $data['is_active'] = (bool) $request->input('is_active', true);

        if ($request->hasFile('image')) {
            $oldPath = $news->image ?? $news->image_path;
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Hapus berita.
     */
    public function destroy(News $news)
    {
        $path = $news->image ?? $news->image_path;
        if ($path) {
            Storage::disk('public')->delete($path);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
