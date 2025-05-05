@extends('Layouts.general')
@section('title')
Liste des startups
@endsection
@section('page_description')

@endsection

@section('css')


@endsection

@section('content')
<div>
<div class="container-xxl flex-grow-1 container-p-y mt-0">
    <startups
    
    :startupes='@json($startupes)'
  ></startups>
</div>

@endsection

@section('script')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection