@extends('layouts.general')

@section('title', 'Gestion du Chatbot')

@section('page_description')
<!-- Vous pouvez ajouter une description si nécessaire -->
@endsection

@section('css')
<!-- Ajoutez ici des styles CSS si nécessaire -->
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y mt-0">
  <chatbot-management
    :initial-stats='@json($stats)'
    :initial-settings='@json($settings)'
    :initial-responses='@json($responses)'
  ></chatbot-management>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- @vite(['resources/js/app.js']) -->
@vite('resources/js/app.js')
@endsection
