@extends('login.master')

@section('content')
<section>
    <article id="form_container">
        <div class="logo">
            <img src="{{ asset('imgs/gianpierre.png?'.time()) }}">
        </div>
        <login-form
            :url="'{{ route('login') }}'"
            >
        </login-form>
    </article>
    <article id="form_img">
        <figure>
            <img src="{{ asset('imgs/form_bg.jpg') }}">
        </figure>
    </article>
</section>
@endsection