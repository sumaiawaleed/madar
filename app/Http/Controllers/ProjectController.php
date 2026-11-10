<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = $request->user()->projects()->with('client')->orderBy('due_on')->get();

        return view('projects.index', [
            'columns' => collect(ProjectStatus::cases())->map(fn (ProjectStatus $status): array => [
                'status' => $status,
                'projects' => $projects->where('status', $status)->values(),
            ]),
        ]);
    }

    public function create(Request $request): View
    {
        $project = new Project(['status' => ProjectStatus::New, 'progress' => 0, 'starts_on' => today()]);

        if ($request->filled('proposal')) {
            $proposal = $request->user()->proposals()->findOrFail($request->integer('proposal'));
            $project->fill([
                'title' => $proposal->title,
                'client_id' => $proposal->client_id,
                'proposal_id' => $proposal->id,
                'budget' => $proposal->subtotal(),
            ]);
        }

        return view('projects.form', [
            'project' => $project,
            'clients' => $request->user()->clients()->orderBy('name')->get(),
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $request->user()->projects()->create($this->attributes($request));

        return redirect()->route('projects.index')->with('status', 'تم حفظ المشروع');
    }

    public function edit(Request $request, Project $project): View
    {
        Gate::authorize('update', $project);

        return view('projects.form', [
            'project' => $project,
            'clients' => $request->user()->clients()->orderBy('name')->get(),
        ]);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        $project->update($this->attributes($request));

        return redirect()->route('projects.index')->with('status', 'تم حفظ المشروع');
    }

    public function destroy(Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')->with('status', 'تم الحذف');
    }

    /**
     * @return array<string, mixed>
     */
    private function attributes(ProjectRequest $request): array
    {
        return [
            ...$request->validated(),
            'budget' => $request->validated('budget') ?? 0,
            'progress' => $request->validated('progress') ?? 0,
        ];
    }
}
