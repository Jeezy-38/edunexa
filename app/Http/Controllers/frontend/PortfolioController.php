<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\StudentProject;
use App\Models\User;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        $certificates = Certificate::where('user_id', $user->id)
            ->with('course')
            ->latest('id')
            ->get();

        $projects = StudentProject::where('user_id', $user->id)
            ->where('status', 1)
            ->latest('id')
            ->get();

        $enrolled_courses = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->latest('id')
            ->take(6)
            ->get();

        $skills = [];
        if (!empty($user->skills)) {
            $decoded = json_decode($user->skills, true);
            if (is_array($decoded)) {
                $skills = array_column($decoded, 'value') ?: $decoded;
            } else {
                $skills = array_map('trim', explode(',', $user->skills));
            }
        }

        $page_data = [
            'user'             => $user,
            'certificates'     => $certificates,
            'projects'         => $projects,
            'enrolled_courses' => $enrolled_courses,
            'skills'           => array_filter($skills),
            'completed_count'  => count($certificates),
        ];

        return view('frontend.default.portfolio.index', $page_data);
    }
}
