<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Comment;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => ['required', Rule::in(['material', 'assignment'])],
            'commentable_id' => ['required', 'integer'],
            'body' => ['required', 'string'],
        ]);

        $userId = Auth::id();

        $commentable = null;

        if ($validated['commentable_type'] === 'material') {
            $q = Material::query()->where('id', $validated['commentable_id']);

            if (Schema::hasColumn('courses', 'teacher_id')) {
                $q->whereHas('course', fn ($c) => $c->where('teacher_id', $userId));
            } elseif (Schema::hasColumn('courses', 'instruktur_id')) {
                $q->whereHas('course', fn ($c) => $c->where('instruktur_id', $userId));
            }

            $commentable = $q->firstOrFail();
        }

        if ($validated['commentable_type'] === 'assignment') {
            $q = Assignment::query()->where('id', $validated['commentable_id']);

            if (method_exists(Assignment::class, 'material')) {
                if (Schema::hasColumn('courses', 'teacher_id')) {
                    $q->whereHas('material.course', fn ($c) => $c->where('teacher_id', $userId));
                } elseif (Schema::hasColumn('courses', 'instruktur_id')) {
                    $q->whereHas('material.course', fn ($c) => $c->where('instruktur_id', $userId));
                }
            }

            $commentable = $q->firstOrFail();
        }

        Comment::create([
            'user_id' => $userId,
            'body' => $validated['body'],
            'commentable_id' => $commentable->id,
            'commentable_type' => get_class($commentable),
        ]);

        return back()->with('success', 'Komentar terkirim.');
    }
}
