<div class="card">
    <div class="card-header" id="asd">
        {{ __('Worklogs') }}
        <div class="card-actionbar">
            @can('create worklogs')
                {!! Form::open(['route' => 'stift.worklog.store', 'style' => 'display: inline;']) !!}
                {{ Form::hidden('issue_id', $issue->id) }}
                <button class="btn btn-sm btn-primary float-right">
                    <i class="zmdi zmdi-play"></i>
                    {{ __('Start work') }}
                </button>
                {!! Form::close() !!}
                <button type="button" data-toggle="modal" data-target="#worklog_form--create"
                        class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Log work') }}
                </button>
            @endcan
        </div>
    </div>
    <div class="card-block">
        @can('create worklogs')
            @component('stift::worklog.create_form', ['issue' => $issue])
            @endcomponent
        @endcan
        <table class="table">
            <thead>
            <tr>
                <th>{{ __('State') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Duration') }}</th>
                <th>{{ __('Description') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($issue->worklogs->sortByDesc('started_at') as $worklog)
                <tr>
                    <td>{{ $worklog->state->label() }}</td>
                    <td>{{ $worklog->started_at }}</td>
                    <td>
                        @if ($worklog->isRunning())
                            @component('stift::worklog.edit_form', [
                                            'worklog'  => $worklog,
                                            'state'    => 'finished',
                                            'btnTitle' => __('Stop Timer and log work')
                                     ])
                            @endcomponent
                            <button type="button" data-toggle="modal" data-target="#worklog_form--{{ $worklog->id }}"
                                    class="btn btn-xs btn-primary" title="{{ __('Stop work') }}">
                                <i class="zmdi zmdi-stop"></i>
                            </button>

                            <span data-running="1" data-worklog_id="{{$worklog->id}}" data-duration="{{ $worklog->started_at->diffInSeconds() }}">
                                {{ duration_secs_to_human_readable($worklog->runningDuration(), true) }}
                            </span>
                        @else
                            <span>
                                {{ duration_secs_to_human_readable((int)$worklog->duration) }}
                            </span>
                        @endif
                    </td>
                    <td>{!! nl2br($worklog->description) !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">{{ __('No work has been logged yet') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
<script>
    $('document').ready(function () {
        setInterval(function() {
            $('[data-running=1]').each(function(index, item) {
                var secs = parseInt($(item).data('duration')) + 1;
                $(item).data('duration', secs);
                $('#worklog-duration--' + $(item).data('worklog_id')).val(secs);
                $(item).text(duration_secs_to_human_readable(secs, true));
            });
        }, 1000);
    });
</script>
@stop