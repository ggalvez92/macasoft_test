@extends('backend.master')

@section('content')
<section id="menu_container">
    <div class="container">
        @include('backend.menu')
    </div>
</section>
<?php 
    $auth_roles = Auth::user()->roles()->select('code')->get()->pluck('code')->toArray();
?>

@if(in_array("003",$auth_roles))
<section id="users_list">
    <article id="filters">
        <filters
            :url-roles="'{{ route('user.roles.list') }}'"
            >
        </filters>
    </article>
    <article id="table">
        <users-table
            :url="'{{ route('user.list') }}'"
            :url-delete="'{{ route('user.delete') }}'"
            >
        </users-table>
    </article>
    <div class="plus_button">
        <img src="{{ asset('imgs/plus.svg') }}" @click="openModal(null)">
    </div>
</section>
<modal-user
    :url="'{{ route('user.ct') }}'"
    :url-detail="'{{ route('user.detail') }}'"
    :close-img="'{{ asset('imgs/close.svg') }}'"
    >
</modal-user>
@else
    <div class="container mt-4">
        <h5>Bienvenido, {{ Auth::user()->name }}. Para editar tienes que entrar una cuenta de administrador.</h5>
    </div>
@endif


@endsection