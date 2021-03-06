<?php
/**
 * Contains the ProjectController class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-07-27
 *
 */


namespace Konekt\Stift\Http\Controllers;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Konekt\AppShell\Http\Controllers\BaseController;
use Konekt\Customer\Models\CustomerProxy;
use Konekt\Stift\Contracts\Project;
use Konekt\Stift\Contracts\Requests\CreateProject;
use Konekt\Stift\Contracts\Requests\UpdateProject;
use Konekt\Stift\Models\PredefinedPeriodProxy;
use Konekt\Stift\Models\ProjectProxy;
use Konekt\Stift\Reports\ProjectWorkingHours;
use Konekt\User\Models\UserProxy;

class ProjectController extends BaseController
{
    public function index(Request $request)
    {
        $query = ProjectProxy::forUser(Auth::user());

        if ($request->get('active')) {
            $active = 1;
            $query->actives();
        } elseif (null === $request->get('active')) {
            $active = null;
        } else {
            $query->inactives();
            $active = 0;
        }

        return view('stift::project.index', [
            'projects' => $query->get(),
            'actives'  => [
                1 => __('Active projects'),
                0 => __('Inactive projects')
            ],
            'active' => $active
        ]);
    }

    public function create()
    {
        return view('stift::project.create', [
            'project'   => app(Project::class),
            'customers' => CustomerProxy::all(),
            'users'     => UserProxy::all()
        ]);
    }

    public function store(CreateProject $request)
    {
        try {
            $project = ProjectProxy::create($request->all());
            $project->users()->sync($request->get('users'));

            flash()->success(__('Project has been created'));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back();
        }

        return redirect(route('stift.project.index'));
    }

    public function show(Project $project)
    {
        if (!$project->visibleFor(Auth::user())) {
            abort(403);
        }

        return view('stift::project.show', [
            'project'                    => $project,
            'durationCurrentMonth'       => ProjectWorkingHours::create(PredefinedPeriodProxy::CURRENT_MONTH(), $project)->getDuration(),
            'workingHoursInLast12Months' => $this->getProjectHoursLastXMonths($project, 12)
        ]);
    }

    public function edit(Project $project)
    {
        if (!$project->visibleFor(Auth::user())) {
            abort(403);
        }

        return view('stift::project.edit', [
            'project'   => $project,
            'customers' => CustomerProxy::all(),
            'users'     => UserProxy::all()
        ]);
    }

    public function update(Project $project, UpdateProject $request)
    {
        if (!$project->visibleFor(Auth::user())) {
            abort(403);
        }

        try {
            $project->update($request->all());
            //dd($request->get('users'));
            $project->users()->sync($request->get('users'));

            flash()->success(__('Project :name has been updated', ['name' => $project->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back();
        }

        return redirect(route('stift.project.index'));
    }

    public function destroy(Project $project)
    {
        if (!$project->visibleFor(Auth::user())) {
            abort(403);
        }

        try {
            $name = $project->name;
            $project->delete();

            flash()->warning(__('The :name project has been deleted', ['name' => $name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
        }

        return redirect(route('stift.project.index'));
    }

    private function getProjectHoursLastXMonths(Project $project, int $numMonths): Collection
    {
        $daily  = new DateInterval('P1D');
        $result = collect();

        for ($i = $numMonths; $i > 0; $i--) {
            $period = new DatePeriod(
                Carbon::now()->subMonths($i)->startOfMonth(),
                $daily,
                Carbon::now()->subMonths($i)->endOfMonth()
            );

            $report = new ProjectWorkingHours($project, $period);
            $result->push([
                'year'       => $report->getPeriod()->getStartDate()->format('Y'),
                'month'      => $report->getPeriod()->getStartDate()->format('n'),
                'month_name' => $report->getPeriod()->getStartDate()->format('M'),
                'hours'      => $report->getWorkingHours()
            ]);
        }

        return $result;
    }
}
