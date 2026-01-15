<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::query()->latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function show($id)
    {
        $news = News::with('author')->findOrFail($id);
        return view('admin.news.show', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:draft,publish',
        ]);

        $slugBase = Str::slug($request->title);
        $slug = $slugBase;
        $i = 2;
        while (News::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $i;
            $i++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
        }

        $status = $request->string('status');
        $isPublish = $status->toString() === 'publish';

        News::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'image' => $imagePath,
            'author_id' => Auth::id(),
            'status' => $status,
            'published_at' => $isPublish ? now() : null,
            'is_active' => $isPublish,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil disimpan.');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:draft,publish',
        ]);

        $slugBase = Str::slug($request->title);
        $slug = $slugBase;
        $i = 2;
        while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
            $slug = $slugBase . '-' . $i;
            $i++;
        }

        $data = [
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'status' => $request->status,
        ];

        $isPublish = $request->status === 'publish';
        $data['is_active'] = $isPublish;
        if ($isPublish && !$news->published_at) {
            $data['published_at'] = now();
        }
        if (!$isPublish) {
            $data['published_at'] = null;
        }

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}