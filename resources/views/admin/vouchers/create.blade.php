@extends('layouts.admin')
@section('title', 'Create Voucher')
@section('content')
<h1 class="mb-6 text-2xl font-semibold">Create voucher</h1>
<form method="POST" action="{{ route('admin.vouchers.store') }}">@include('admin.vouchers._form')</form>
@endsection
