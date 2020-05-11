@extends('layouts.default')

@section('content')
  @if (Auth::check())
    <div class="row">
      <div class="col-md-8">
        <section class="status_form">
          @include('shared._status_form')
        </section>
        <h4>微博列表</h4>
        <hr>
        @include('shared._feed')
      </div>
      <aside class="col-md-4">
        <section class="user_info">
          @include('shared._user_info', ['user' => Auth::user()])
        </section>
        <section class="status mt-2">
          @include('shared._status', ['user' => Auth::user()])
        </section>
    </div>
  @else
    <div class="jumbotron">
      <h1>Hello Laravel</h1>
      <p class="lead">
        您現在所看到的是 <a href="https://learnku.com/courses/laravel-essential-training">Laravel 入門課程</a> 的範例主頁。
      </p>
      <p>
        一切，將從這裡開始。
      </p>
      <p>
        <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">現在註冊</a>
      </p>
    </div>
  @endif
@stop