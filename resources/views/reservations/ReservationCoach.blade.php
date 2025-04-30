@extends('Layouts.general')
@section('title')
Mes Réservations
@endsection
@section('page_description')

@endsection

@section('css')


@endsection

@section('content')
<div id="app">

<div class="container-xxl flex-grow-1 container-p-y mt-0">
  <reservations-coach :reservations='@json($reservations)'>

  </reservations-coach>
</div>
</div>
@endsection

@section('script')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection