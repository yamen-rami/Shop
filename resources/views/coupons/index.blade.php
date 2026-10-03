@extends('admin')
@section('title', 'Coupon offers')
@section('header', 'Coupon offers')
@section('content')
    <livewire:record-table resource="coupon-offers" />
@endsection
