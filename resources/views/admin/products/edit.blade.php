@extends('layouts.app')

@section('title', 'Editar producto | Administracion')

@section('content')
    <section class="page-hero compact"><p class="eyebrow">Administracion</p><h1>Editar producto</h1></section>
    <section class="section-wrap"><div class="admin-form-panel">@include('admin.products._form', ['product' => $product])</div></section>
@endsection
