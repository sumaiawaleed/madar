<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ProjectStageController extends Controller
{
    /**
     * Move a project to another column on the board.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        $validated = $request->validate(['status' => ['required', Rule::enum(ProjectStatus::class)]]);

        $project->update($validated);

        return redirect()->route('projects.index');
    }
}
