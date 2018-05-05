<div id="worklog_form--{{ $worklog->id }}" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="invoice-settings-title" aria-hidden="true">


    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::model($worklog, ['route' => ['stift.worklog.update', $worklog], 'method' => 'PUT']) !!}
            <div class="modal-header">
                <h5 class="modal-title" id="invoice-settings-title">{{ __('Worklog') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @isset($state)
                {{ Form::hidden('state', $state) }}
                @endisset
                {{ Form::hidden('duration', $worklog->runningDuration(), ['id' => 'worklog-duration--' . $worklog->id ]) }}
                <div class="form-group">
                    <label class="col-form-label">{{ __('Description') }}</label>
                    <div class="{{ $errors->has('invoice_series_id') ? ' has-danger' : '' }}">
                        {{ Form::textarea('description', $worklog->description, [ 'class' => 'form-control', 'placeholder' => __('Type worklog description...')]) }}
                        @if ($errors->has('description'))
                            <div class="form-control-feedback">{{ $errors->first('description') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">{{ $btnTitle ?? __('Save') }}</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>