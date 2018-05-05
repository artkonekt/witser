<?php
/**
 * Contains the WorklogController class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-04
 */

namespace Konekt\Stift\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Konekt\AppShell\Http\Controllers\BaseController;
use Konekt\Stift\Contracts\Requests\CreateWorklog;
use Konekt\Stift\Contracts\Requests\UpdateWorklog;
use Konekt\Stift\Contracts\Worklog;
use Konekt\Stift\Models\WorklogProxy;

class WorklogController extends BaseController
{
    public function create()
    {
        return view('stift::worklog.create', [
            'worklog'  => app(Worklog::class)
        ]);
    }

    public function store(CreateWorklog $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        try {
            $worklog = WorklogProxy::create($data);

            flash()->success(__('Worklog has been created'));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back();
        }

        return redirect(route('stift.issue.show', $worklog->issue));
    }

    public function show(Worklog $worklog)
    {
        return view('stift::worklog.show', ['worklog' => $worklog]);
    }

    public function edit(Worklog $worklog)
    {
        return view('stift::worklog.edit', ['worklog' => $worklog]);
    }

    public function update(Worklog $worklog, UpdateWorklog $request)
    {
        try {
            $worklog->update($request->all());

            flash()->success(__('Worklog has been saved'));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back();
        }

        return redirect(route('stift.issue.show', $worklog->issue));
    }

    public function destroy(Worklog $worklog)
    {
        try {
            $issue = $worklog->issue;
            $worklog->delete();

            flash()->warning(__('Worklog has been deleted'));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
        }

        return redirect(route('stift.issue.show', $issue));
    }

}