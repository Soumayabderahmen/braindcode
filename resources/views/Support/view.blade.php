@extends('layouts.general')

@section('title', 'Détail du Message')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y mt-0">
  <view-message :message='@json($message)'></view-message>
</div>

<script>
    window.authUser = @json(Auth::user());
</script>
@endsection

@section('script')
    @routes
@endsection
