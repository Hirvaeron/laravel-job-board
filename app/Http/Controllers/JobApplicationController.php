<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{

    public function create(Job $job)
    {
        Gate::authorize("apply", $job);
        return view('job_application.create', ['job' => $job]);
    }


    public function store(Job $job, Request $request)
    {
        Gate::authorize("apply", $job);

        $validatedData = $request->validate([
            'expected_salary' => 'required|min:1|max:1000000',
            'cv' => 'required|file|mimes:pdf|max:2048'
        ]);

        $file = $request->file('cv');
        $path = $file->store('cvs', 'local');

        $job->jobApplications()->create([
            'user_id' => $request->user()->id,
            'expected_salary' => $validatedData['expected_salary'],
            'cv_path' => $path
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Job application submitted.');
    }


    public function destroy(string $id)
    {
        //
    }
}
