@php
    $role = auth()->user()->role; 
@endphp
@if ($role === 'admin')
    @include('layouts.Partials.admin')

@elseif ($role === 'coach')
    @include('layouts.Partials.coach')

@elseif ($role === 'investisseur')
    @include('layouts.Partials.investisseur')
@elseif ($role === 'startup')
    @include('layouts.Partials.startup')



 <!-- @else
    @include('Layouts.partials.sidebarInvestisseur') 
@endif  -->