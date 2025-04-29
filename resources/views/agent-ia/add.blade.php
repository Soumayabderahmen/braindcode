@extends('Layouts.general')
@section('title')
Agent IA
@endsection
@section('page_description')

@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bs-stepper/bs-stepper.css')}}" />
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y mt-0">
  <add-agent-ia></add-agent-ia>
</div>

@endsection

@section('script')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection