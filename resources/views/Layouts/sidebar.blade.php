@php
    $role = auth()->user()->role; 
@endphp
@if ($role === 'Admin')
    @include('Layouts.partials.sidebarCoach')

@elseif ($role === 'startup')
    @include('Layouts.partials.sidebarStartup')

@elseif ($role === 'coach')
    @include('Layouts.partials.sidebarCoach')

@else
    @include('Layouts.partials.sidebarInvestisseur') 
@endif
