<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use App\Models\Unit;
use App\Models\User;
use App\Enums\Role; // Assuming you have this Enum and use it for roles

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => User::where('role', Role::STUDENT)->count(),
            'lecturers' => User::where('role', Role::LECTURER)->count(),
            'courses' => Course::count(),
            'units' => Unit::count(),
        ];

        // --- GENDER DISTRIBUTION DATA (for the chart) ---
        $totalStudents = $stats['students'];

        $maleStudents = User::where('role', Role::STUDENT)->where('gender', 'Male')->count();
        $femaleStudents = User::where('role', Role::STUDENT)->where('gender', 'Female')->count();
        $otherGendersCounts = User::where('role', Role::STUDENT)
                                  ->whereNotIn('gender', ['Male', 'Female'])
                                  ->groupBy('gender')
                                  ->selectRaw('gender, count(*) as count')
                                  ->pluck('count', 'gender')
                                  ->all();

        $unspecifiedCount = $otherGendersCounts[null] ?? 0;
        if (isset($otherGendersCounts[null])) {
            unset($otherGendersCounts[null]);
        }

        $genderData = [
            'Male' => $maleStudents,
            'Female' => $femaleStudents,
            'Unspecified' => $unspecifiedCount,
        ];
        foreach ($otherGendersCounts as $genderLabel => $count) {
            $genderData[$genderLabel] = $count;
        }

        $chartLabels = ['Male', 'Female'];
        $chartData = [$genderData['Male'], $genderData['Female']];
        $chartColors = [
            'rgba(59, 130, 246, 0.8)', // Blue for Male
            'rgba(236, 72, 153, 0.8)', // Pink for Female
        ];
        $chartBorderColors = [
            'rgba(59, 130, 246, 1)',
            'rgba(236, 72, 153, 1)',
        ];

        if ($genderData['Unspecified'] > 0) {
            $chartLabels[] = 'Unspecified';
            $chartData[] = $genderData['Unspecified'];
            $chartColors[] = 'rgba(107, 114, 128, 0.8)';
            $chartBorderColors[] = 'rgba(107, 114, 128, 1)';
        }

        $colorIndex = 0;
        $dynamicColors = [
            'rgba(74, 222, 128, 0.8)',
            'rgba(251, 191, 36, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(244, 63, 94, 0.8)',
            'rgba(20, 184, 166, 0.8)',
        ];

        foreach ($otherGendersCounts as $genderLabel => $count) {
            $chartLabels[] = $genderLabel;
            $chartData[] = $count;
            $chartColors[] = $dynamicColors[$colorIndex % count($dynamicColors)];
            $chartBorderColors[] = str_replace('0.8', '1', $dynamicColors[$colorIndex % count($dynamicColors)]);
            $colorIndex++;
        }

        $genderPercentages = [];
        foreach ($chartLabels as $index => $label) {
            $value = $chartData[$index];
            $baseLabel = explode(' (', $label)[0];
            $genderPercentages[$baseLabel] = $totalStudents > 0 ? round(($value / $totalStudents) * 100, 1) : 0;
        }
        // --- END GENDER DISTRIBUTION DATA ---


        // --- RECENT ACTIVITY DATA (Now specific to lecturers and added courses) ---
        // Get the 5 most recently registered lecturers
        $latestLecturerRegistrations = User::where('role', Role::LECTURER)
                                        ->latest() // Orders by 'created_at' DESC
                                        ->take(5) // Limit to 5
                                        ->get();

        // Get the 5 most recently added courses (ordered by 'created_at')
        $recentlyAddedCourses = Course::latest() // This defaults to 'created_at' DESC
                                     ->take(5) // Limit to 5
                                     ->get();
        // --- END RECENT ACTIVITY DATA ---


        return view('admin.dashboard', compact(
            'stats',
            'chartLabels',
            'chartData',
            'chartColors',
            'chartBorderColors',
            'genderPercentages',
            'totalStudents',
            'latestLecturerRegistrations', 
            'recentlyAddedCourses'
        ));
    }
}