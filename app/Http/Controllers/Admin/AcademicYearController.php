<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua tahun yang tersedia untuk tabs
        $allYears = AcademicYear::selectRaw('YEAR(start_date) as year')
            ->whereNotNull('start_date')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
        
        // Jika tidak ada data dengan start_date, parsing dari nama
        if (empty($allYears)) {
            $allYears = AcademicYear::all()
                ->map(fn($item) => $item->year)
                ->filter()
                ->unique()
                ->sortDesc()
                ->values()
                ->toArray();
        }
        
        // Tahun yang dipilih (default: tahun terbaru atau tahun sekarang)
        $selectedYear = $request->get('year', $allYears[0] ?? date('Y'));
        
        // Filter angkatan berdasarkan tahun yang dipilih
        $years = AcademicYear::whereYear('start_date', $selectedYear)
            ->orWhere(function($query) use ($selectedYear) {
                $query->whereNull('start_date')
                    ->where('name', 'like', "%{$selectedYear}%");
            })
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();
        
        // Statistik untuk tahun yang dipilih
        $stats = [
            'total_angkatan' => $years->count(),
            'angkatan_aktif' => $years->where('is_active', true)->count(),
            'total_siswa' => $years->sum(fn($y) => $y->students()->count()),
        ];

        return view('admin.academic_years.index', compact('years', 'allYears', 'selectedYear', 'stats'));
    }

    public function create()
    {
        return view('admin.academic_years.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi termasuk start_date dan end_date
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $isActive = $request->boolean('is_active');

            if ($isActive) {
                AcademicYear::where('is_active', true)->update(['is_active' => false]);
            }

            // Simpan semua data termasuk tanggal
            AcademicYear::create([
                'name' => $validated['name'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'is_active' => $isActive,
            ]);
        });

        return redirect()->route('admin.academic_years.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit(AcademicYear $academic_year)
    {
        return view('admin.academic_years.edit', ['year' => $academic_year]);
    }

    public function update(Request $request, AcademicYear $academic_year): RedirectResponse
    {
        // Validasi termasuk start_date dan end_date
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request, $academic_year) {
            $isActive = $request->boolean('is_active');

            if ($isActive) {
                // Nonaktifkan tahun lain, KECUALI diri sendiri
                AcademicYear::where('is_active', true)
                    ->where('id', '!=', $academic_year->id)
                    ->update(['is_active' => false]);
            }

            // Update semua data termasuk tanggal
            $academic_year->update([
                'name' => $validated['name'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'is_active' => $isActive,
            ]);
        });

        return redirect()->route('admin.academic_years.index')->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academic_year): RedirectResponse
    {
        $academic_year->delete();

        return redirect()->route('admin.academic_years.index')->with('success', 'Periode berhasil dihapus.');
    }

    public function setActive(AcademicYear $academic_year): RedirectResponse
    {
        DB::transaction(function () use ($academic_year) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
            $academic_year->update(['is_active' => true]);
        });

        return redirect()->route('admin.academic_years.index')->with('success', 'Periode aktif berhasil diubah.');
    }
}