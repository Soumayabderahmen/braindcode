@extends('Layouts.general')
@section('title')
Disponibilité
@endsection
@section('page_description')

@endsection

@section('css')


@endsection

@section('content')
<div>
<div class="container-xxl flex-grow-1 container-p-y mt-0">
    <disponibilite
    :availabilities='@json($availabilities)'
    :coach-id='@json($coachId)'
    ></disponibilite>
</div>

@endsection
@section('script')
    @routes
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

