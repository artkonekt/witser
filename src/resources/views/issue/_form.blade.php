<div class="form-group{{ $errors->has('subject') ? ' has-danger' : '' }}">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-info-outline"></i>
        </span>
        {{ Form::text('subject', null, ['class' => 'form-control form-control-lg', 'placeholder' => __('Subject')]) }}
    </div>
    @if ($errors->has('subject'))
        <div class="form-control-feedback">{{ $errors->first('subject') }}</div>
    @endif
</div>

<div class="form-group{{ $errors->has('project_id') ? ' has-danger' : '' }}">
    @if($projects->count() > 1)
        {{ Form::select('project_id', $projects->pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => __('Project')]) }}
    @elseif($projects->count() == 0)
        <div class="alert alert-warning">
            <strong>{{ __("There's no project in the system.") }}</strong>
            @can('create projects')
                <a href="{{ route('stift.project.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create project') }}
                </a>
            @else
                <i class="zmdi zmdi-mood-bad"></i> {{ __("Unfortunately you can't create one") }}
            @endcan
        </div>
    @else
        <?php $project = $projects->first(); ?>
        {{ Form::hidden('project_id', $project->id) }}
        <label class="text-muted">{{ __('Project') }}: {{ $project->name }}</label>
    @endif

    @if ($errors->has('project_id'))
        <div class="form-control-feedback">{{ $errors->first('project_id') }}</div>
    @endif
</div>

<div class="form-group row">
    <label class="form-control-label col-md-2">{{ __('Status') }}</label>
    <div class="col-md-10">
        @foreach($statuses as $status)
            <label class="radio-inline" for="status_{{ $status }}">
                {{ Form::radio('status', $status, $issue->status == $status, ['id' => "status_$status"]) }}
                {{ $status }}
                &nbsp;
            </label>
        @endforeach

        @if ($errors->has('status'))
            <div class="invalid-feedback">{{ $errors->first('status') }}</div>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('issue_type_id') ? ' has-danger' : '' }}">
    {{ Form::hidden('issue_type_id', 'task') }}
    <label class="text-muted">{{ __('Type') }}: Task</label>

    @if ($errors->has('issue_type_id'))
        <div class="form-control-feedback">{{ $errors->first('issue_type_id') }}</div>
    @endif
</div>

<div class="form-group{{ $errors->has('severity_id') ? ' has-danger' : '' }}">
    {{ Form::hidden('severity_id', 'normal') }}
        <label class="text-muted">{{ __('Severity') }}: Normal</label>

    @if ($errors->has('severity_id'))
        <div class="form-control-feedback">{{ $errors->first('severity_id') }}</div>
    @endif
</div>

{{--<div class="form-group row {{ $errors->has('permissions') ? ' has-danger' : '' }}">--}}

    {{--@foreach($permissions as $permission)--}}
        {{--<div class="col-6 col-sm-2 @unless(($loop->index + 5) % 5)offset-sm-1 @endunless">--}}
            {{--{{ $permission->name }}--}}
            {{--<label class="switch switch-icon switch-pill switch-primary">--}}
                {{--{{ Form::checkbox("permissions[{$permission->name}]", 1, $role->hasPermissionTo($permission), ['class' => 'switch-input']) }}--}}
                {{--<span class="switch-label" data-on="&#xf26b;" data-off="&#xf136;"></span>--}}
                {{--<span class="switch-handle"></span>--}}
            {{--</label>--}}
        {{--</div>--}}
    {{--@endforeach--}}

    {{--@if ($errors->has('permissions'))--}}
        {{--<div class="form-control-feedback">{{ $errors->first('permissions') }}</div>--}}
    {{--@endif--}}

{{--</div>--}}
