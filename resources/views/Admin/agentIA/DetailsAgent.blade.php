@extends('Layouts.general')
@section('title')
Agent IA
@endsection
@section('page_description')

@endsection

@section('css')


@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y mt-0">
    <details_agent :agent='@json($agent)'></details_agent>

@endsection
@section('script')
    @routes
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

