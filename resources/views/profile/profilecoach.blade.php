@extends('Layouts.general')
@section('title')
coach
@endsection

@section('content')
<div id="app" >
  <profile-edit  
    :must-verify-email='@json($mustVerifyEmail)'
    :status='@json($status)'
     :user='@json($userInfo)'
    :role='@json($role)'>
  </profile-edit>
</div>
@endsection

@section('script')
@routes()
<script src="{{ mix('/js/app.js') }}"></script>
@endsection
