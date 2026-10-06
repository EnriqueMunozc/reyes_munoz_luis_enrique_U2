@extends('layouts.app')

@section('title', 'Editar categoria | Administracion')

@section('content')
    <section class="page-hero compact"><p class="eyebrow">Administracion</p><h1>Editar categoria</h1></section>
    <section class="section-wrap"><div class="admin-form-panel">@include('admin.categories._form', ['category' => $category])</div></section>
@endsection
