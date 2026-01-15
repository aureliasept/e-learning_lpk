<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::query()
            ->where('status', 'publish')
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->paginate(9);

        return view('student.news.index', compact('news'));
    }

    public function show(News $news)
    {
        if (($news->status ?? 'draft') !== 'publish') {
            abort(404);
        }

        return view('student.news.show', compact('news'));
    }
}
