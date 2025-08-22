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
        $job->jobApplications()->create([
            'user_id' => $request->user()->id,
            ...$request->validate([
                'expected_salary' => 'required|min:1|max:1000000'
            ]),
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Job application submitted.');
    }


    public function destroy(string $id)
    {
        //
    }
}
