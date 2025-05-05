@extends('Layouts.general')
@section('title')
Réservations
@endsection
@section('page_description')

@endsection

@section('css')


@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y mt-0">
    <add-reservations
    :coach='@json($coach)'
    :availability='@json($availability)'
    :slots='@json($slots)'
    :date='@json($date)'
    :honoraire='@json($honoraire)'
    :startup_id='@json($startup_id)'
  ></add-reservations>
</div>

@endsection

@section('script')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection