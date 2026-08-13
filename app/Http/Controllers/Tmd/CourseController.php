<?php

namespace App\Http\Controllers\Tmd;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|max:50|unique:courses,course_code',
            'title' => 'required|string|max:255',
            'specialty_track' => 'required|string|max:100',
            'format_type' => 'required|string|max:100',
            'duration_hours' => 'required|integer|min:0',
            'credentials' => 'nullable|string',
            'live_runs_completed' => 'nullable|integer|min:0',
            'live_runs_total' => 'nullable|integer|min:0',
            'reference_folders' => 'nullable|string|max:255',
        ]);

        Course::create([
            'course_code' => $request->course_code,
            'title' => $request->title,
            'specialty_track' => $request->specialty_track,
            'format_type' => $request->format_type,
            'duration_hours' => $request->duration_hours,
            'credentials' => $request->credentials ? array_map('trim', explode(',', $request->credentials)) : [],
            'live_runs_completed' => $request->live_runs_completed ?? 0,
            'live_runs_total' => $request->live_runs_total ?? 0,
            'reference_folders' => $request->reference_folders,
        ]);

        return redirect()->route('tmd.participants.index', ['tab' => 'hub'])
            ->with('success', 'Course added successfully.');
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_code' => 'required|string|max:50|unique:courses,course_code,' . $course->id,
            'title' => 'required|string|max:255',
            'specialty_track' => 'required|string|max:100',
            'format_type' => 'required|string|max:100',
            'duration_hours' => 'required|integer|min:0',
            'credentials' => 'nullable|string',
            'live_runs_completed' => 'nullable|integer|min:0',
            'live_runs_total' => 'nullable|integer|min:0',
            'reference_folders' => 'nullable|string|max:255',
        ]);

        $course->update([
            'course_code' => $request->course_code,
            'title' => $request->title,
            'specialty_track' => $request->specialty_track,
            'format_type' => $request->format_type,
            'duration_hours' => $request->duration_hours,
            'credentials' => $request->credentials ? array_map('trim', explode(',', $request->credentials)) : [],
            'live_runs_completed' => $request->live_runs_completed ?? 0,
            'live_runs_total' => $request->live_runs_total ?? 0,
            'reference_folders' => $request->reference_folders,
        ]);

        return redirect()->route('tmd.participants.index', ['tab' => 'hub'])
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('tmd.participants.index', ['tab' => 'hub'])
            ->with('success', 'Course deleted.');
    }
}
