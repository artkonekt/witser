<div id="worklog_form--{{ $worklog->id }}" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="invoice-settings-title" aria-hidden="true">


    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::model($worklog, ['route' => ['stift.worklog.update', $worklog], 'method' => 'PUT']) !!}
            <div class="modal-header">
                <h5 class="modal-title" id="worklog-modal-title">{{ __('Worklog') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @isset($state)
                    {{ Form::hidden('state', $state) }}
                @endisset

                <div class="form-group row">
                    <label class="col-form-label col-md-4">{{ __('Issue') }}</label>
                    <div class="col-md-8">
                        <div class="{{ $errors->has('issue_id') ? ' has-danger' : '' }}">
                            {{ Form::select(
                                        'issue_id',
                                        stift_open_issues($worklog->issue)->pluck('subject', 'id'),
                                        $worklog->issue ? $worklog->issue->id : null,
                                        [
                                            'class'        => 'form-control',
                                            'autocomplete' => 'off',
                                            'placeholder'  => __('Select issue to log work to')
                                        ]
                            ) }}
                            @if ($errors->has('issue_id'))
                                <div class="form-control-feedback">{{ $errors->first('issue_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-4">{{ __('Duration') }}</label>
                    <div class="col-md-8">
                        <div class="{{ $errors->has('duration') ? ' has-danger' : '' }}">
                            {{ Form::text('duration', duration_secs_to_human_readable($worklog->runningDuration() ?? (int) $worklog->duration, true),
                                [
                                    'class' => 'form-control',
                                    'placeholder' => __('Eg: 1h 15m'),
                                    'data-worklog-human-val' => $worklog->id
                                ]
                            ) }}
                            @if ($errors->has('duration'))
                                <div class="form-control-feedback">{{ $errors->first('duration') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="form-control-label col-md-4">{{ __('Billable') }}</label>
                    <div class="col-md-8">
                        {{ Form::hidden('is_billable', 0) }}
                        <label class="switch switch-icon switch-pill switch-primary">
                            {{ Form::checkbox('is_billable', 1, null, ['class' => 'switch-input']) }}
                            <span class="switch-label" data-on="&#xf26b;" data-off="&#xf136;"></span>
                            <span class="switch-handle"></span>
                        </label>

                        @if ($errors->has('is_billable'))
                            <input type="text" hidden class="form-control is-invalid">
                            <div class="invalid-feedback">{{ $errors->first('is_billable') }}</div>
                        @endif

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-form-label">{{ __('Description') }}</label>
                    <div class="{{ $errors->has('description') ? ' has-danger' : '' }}">
                        {{ Form::textarea('description', $worklog->description, [ 'class' => 'form-control', 'placeholder' => __('Type worklog description...')]) }}
                        @if ($errors->has('description'))
                            <div class="form-control-feedback">{{ $errors->first('description') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-link text-danger mr-auto" type="button"
                        onclick="$('#worklog-delete-form--{{ $worklog->id }}').submit()"
                >{{ __('Delete') }}</button>
                <button class="btn btn-primary">{{ $btnTitle ?? __('Save') }}</button>
                <button type="button" class="btn btn-link"
                        data-dismiss="modal">{{ __('Close') }}</button>
            </div>
            {!! Form::close() !!}
            {!! Form::open([
                            'route' => ['stift.worklog.destroy', $worklog],
                            'method' => 'DELETE',
                            'id' => "worklog-delete-form--{$worklog->id}",
                            'data-confirmation-text' => __("Are you sure to delete the worklog at :date for Issue ':issue'?", [
                                    'date' => $worklog->started_at,
                                    'issue' => $worklog->issue->subject
                                ])
                            ])
                    !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
