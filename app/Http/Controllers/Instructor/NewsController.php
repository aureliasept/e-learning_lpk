<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function show(News $news)
    {
        // Only show active/published news
        if (!$news->is_active) {
            abort(404);
        }

        return view('instructor.news.show', compact('news'));
    }
}
