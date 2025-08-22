<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
use App\Models\Job;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class MyJobController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAnyEmployer', Job::class);
        return view(
            'my_job.index',
            [
                'jobs' => auth()->user()->employer
                    ->jobs()
                    ->with(['employer', 'jobApplications', 'jobApplications.user'])
                    ->get()
            ]
        );
    }

    public function create()
    {
        Gate::authorize('create', Job::class);
        return view('my_job.create');
    }


    public function store(JobRequest $request)
    {
        Gate::authorize('create', Job::class);
        $request->user()->employer->jobs()->create($request->validated());

        return redirect()->route('my-jobs.index')->with('success', 'Job created successfully!');
    }

    public function edit(Job $myJob)
    {
        Gate::authorize('update', $myJob);
        return view('my_job.edit', ['job' => $myJob]);
    }


    public function update(JobRequest $request, Job $myJob)
    {
        Gate::authorize('update', $myJob);
        $myJob->update($request->validated());

        return redirect()->route('my-jobs.index')->with('success', 'Job updated successfully!');
    }


    public function destroy(string $id)
    {
        //
    }
}
