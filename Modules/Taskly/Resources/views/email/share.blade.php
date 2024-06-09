@component('mail::message')
#  {{ __('Hello')}}, {{ $client->name }}

{{ __('You are invited into new project')}} <b> {{ $project->name }}</b> {{ __('by')}} {{ $project->creater->name }}
@component('mail::button', ['url' => route('projects.show',[$project->id])])
{{ __('Open Project')}}
@endcomponent

{{ __('Thanks')}},<br>
{{ config('app.name') }}
@endcomponent
