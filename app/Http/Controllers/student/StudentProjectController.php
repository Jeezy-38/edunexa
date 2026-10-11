<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\FileUploader;
use App\Models\StudentProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class StudentProjectController extends Controller
{
    /**
     * Student's project manager page
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $projects = StudentProject::where('user_id', $user_id)->latest('id')->paginate(10);
        $enrolled_courses = Course::join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->where('enrollments.user_id', $user_id)
            ->select('courses.id', 'courses.title')
            ->get();

        $page_data = [
            'projects' => $projects,
            'enrolled_courses' => $enrolled_courses,
        ];

        return view('frontend.default.student.projects.index', $page_data);
    }

    /**
     * Store a new student project
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'project_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'course_id' => 'nullable|integer',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $thumbnail_path = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $file_name = Str::random(20) . '.' . $file->extension();
            $thumbnail_path = 'uploads/student_projects/' . $file_name;
            FileUploader::upload($file, $thumbnail_path, 600, 400);
        }

        StudentProject::create([
            'user_id' => auth()->user()->id,
            'course_id' => $request->course_id ?: null,
            'title' => $request->title,
            'description' => $request->description,
            'project_url' => $request->project_url,
            'github_url' => $request->github_url,
            'thumbnail' => $thumbnail_path,
            'tags' => $request->tags,
            'status' => 1,
        ]);

        Session::flash('success', get_phrase('Hongera! Mradi wako umeongezwa kwenye wasifu wako na Showcase ya Edunexa.'));
        return redirect()->route('my.projects');
    }

    /**
     * Delete student project
     */
    public function delete($id)
    {
        $project = StudentProject::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $project->delete();
        Session::flash('success', get_phrase('Mradi umefutwa kwa mafanikio.'));
        return redirect()->back();
    }

    /**
     * Public Student Project Showcase Gallery (/showcase)
     */
    public function showcase(Request $request)
    {
        $query = StudentProject::with(['user', 'course'])->where('status', 1)->latest('id');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%")
                    ->orWhere('tags', 'like', "%{$s}%");
            });
        }

        $projects = $query->paginate(12);
        $total_projects = StudentProject::where('status', 1)->count();

        $page_data = [
            'projects' => $projects,
            'total_projects' => $total_projects,
            'search' => $request->search ?? '',
        ];

        return view('frontend.default.showcase.index', $page_data);
    }
}
