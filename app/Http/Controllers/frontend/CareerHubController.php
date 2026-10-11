<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\JobOpportunity;
use Illuminate\Http\Request;

class CareerHubController extends Controller
{
    public function index(Request $request)
    {
        $query = JobOpportunity::where('status', 1)->latest('is_featured')->latest('id');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('company_name', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%")
                    ->orWhere('requirements', 'like', "%{$s}%");
            });
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('job_type', $request->type);
        }

        if ($request->filled('location') && $request->location !== 'all') {
            $query->where('location', 'like', "%" . $request->location . "%");
        }

        $jobs = $query->paginate(9);
        $total_openings = JobOpportunity::where('status', 1)->count();

        $page_data = [
            'jobs' => $jobs,
            'total_openings' => $total_openings,
            'selected_type' => $request->type ?? 'all',
            'selected_location' => $request->location ?? 'all',
            'search' => $request->search ?? '',
        ];

        return view('frontend.default.careers.index', $page_data);
    }

    public function details($id)
    {
        $job = JobOpportunity::where('status', 1)->findOrFail($id);
        $related_course = null;
        if ($job->related_course_id) {
            $related_course = Course::find($job->related_course_id);
        }

        $similar_jobs = JobOpportunity::where('status', 1)
            ->where('id', '!=', $job->id)
            ->where('job_type', $job->job_type)
            ->take(3)
            ->get();

        $page_data = [
            'job' => $job,
            'related_course' => $related_course,
            'similar_jobs' => $similar_jobs,
        ];

        return view('frontend.default.careers.details', $page_data);
    }
}
