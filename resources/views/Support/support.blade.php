@extends('layouts.general')
@section('title')
Support Message
@endsection
@section('page_description')

@endsection

@section('css')


@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y mt-0">
<support :messages='@json($messages)'></support>
</div>

@endsection

@section('script')
    @routes
    <script>
    window.authUser = @json(Auth::user());
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection