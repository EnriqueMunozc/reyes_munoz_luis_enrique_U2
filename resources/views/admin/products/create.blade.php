@extends('layouts.app')

@section('title', 'Nuevo producto | Administracion')

@section('content')
    <section class="page-hero compact"><p class="eyebrow">Administracion</p><h1>Nuevo producto</h1></section>
    <section class="section-wrap"><div class="admin-form-panel">@include('admin.products._form')</div></section>
@endsection
