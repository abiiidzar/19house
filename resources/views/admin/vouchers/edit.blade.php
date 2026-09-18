@extends('layouts.admin')
@section('title', 'Edit Voucher')
@section('content')
<h1 class="mb-6 text-2xl font-semibold">Edit {{ $voucher->code }}</h1>
<form method="POST" action="{{ route('admin.vouchers.update', $voucher) }}">@include('admin.vouchers._form')</form>
@endsection
