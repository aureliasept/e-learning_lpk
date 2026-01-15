<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Comment;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $user = Auth::user();
        $profile = $user?->student;

        $isReguler = (bool) ($profile->is_reguler ?? false);
        $isKaryawan = (bool) ($profile->is_karyawan ?? false);

        if (! $isReguler && ! $isKaryawan && $profile) {
            $isReguler = ($profile->type ?? null) === 'reguler';
            $isKaryawan = ($profile->type ?? null) === 'karyawan';
        }

        $categories = [];
        if ($isReguler) {
            $categories[] = 'reguler';
        }
        if ($isKaryawan) {
            $categories[] = 'karyawan';
        }

        if (count($categories) === 0) {
            abort(403);
        }

        $commentable = null;

        if ($validated['commentable_type'] === 'material') {
            $commentable = Material::query()
                ->where('id', $validated['commentable_id'])
                ->whereHas('course', fn ($q) => $q->whereIn('category', $categories))
                ->firstOrFail();
        }

        if ($validated['commentable_type'] === 'assignment') {
            $commentable = Assignment::query()
                ->where('id', $validated['commentable_id'])
                ->whereHas('material.course', fn ($q) => $q->whereIn('category', $categories))
                ->firstOrFail();
        }

        Comment::create([
            'user_id' => $user->id,
            'body' => $validated['body'],
            'commentable_id' => $commentable->id,
            'commentable_type' => get_class($commentable),
        ]);

        return back()->with('success', 'Komentar terkirim.');
    }
}
