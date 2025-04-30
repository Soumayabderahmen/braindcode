@php
    $role = auth()->user()->role; 
@endphp
@if ($role === 'admin')
    @include('Layouts.partials.sidebarAdmin')

@elseif ($role === 'startup')
    @include('Layouts.partials.sidebarStartup')

@elseif ($role === 'coach')
    @include('Layouts.partials.sidebarCoach')

@else
    @include('Layouts.partials.sidebarInvestisseur') 
@endif
