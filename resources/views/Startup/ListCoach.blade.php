@extends('Layouts.general')
@section('title')
List des Coachs 
@endsection
@section('page_description')

@endsection

@section('css')


@endsection

@section('content')
<div>
<div class="container-xxl flex-grow-1 container-p-y mt-0">
    <List_coach
    :coachs='@json($coachs)'
   
    ></List_coach>
</div>

@endsection
@section('script')
    @routes
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

