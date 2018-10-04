@extends('appshell::layouts.default')

@section('title')
    {{  $issue->subject }}
@stop

@section('content')

    <div class="row">

        <div class="col-sm-6 col-md-4">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'time-interval',
                    'type' => $issue->worklogsTotalDuration() ? 'info' : 'warning'
            ])
                @if($issue->worklogsTotalDuration())
                    {{ show_duration_in_hours($issue->worklogsTotalDuration()) }}
                @else
                    {{ __('no work yet') }}
                @endif

                @slot('subtitle')
                    {{ __('Total Work Logged') }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6 col-md-4">
            @component('appshell::widgets.card_with_icon', ['icon' => 'account-circle'])
                @if ($issue->assignedTo)
                    <span title="{{ __('Assigned to :name', ['name' => $issue->assignedTo->name]) }}">
                        {{ $issue->assignedTo->name }}
                    </span>
                @else
                    {{ __('Unassigned') }}
                @endif

                @slot('subtitle')
                    {{ __('Created by') }}
                    {{ $issue->createdBy->name }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6 col-md-4">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'folder-star',
                    'type' => $issue->status == 'done' ? 'success' : null
            ])
                {{ $issue->project->name }}
                @slot('subtitle')
                    {{ $issue->project->customer->name }}
                @endslot
            @endcomponent
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            {{ __('Description') }}

            <div class="card-actionbar">
                @can('edit issues')
                    <a href="{{ route('stift.issue.edit', $issue) }}" class="btn btn-outline-primary">{{ __('Edit issue') }}</a>
                @endcan
            </div>

        </div>
        <div class="card-block">
            {!! $issue->getMarkdownDescriptionsAsHtml() !!}
        </div>
    </div>

    @include('stift::issue._worklogs')

@stop
