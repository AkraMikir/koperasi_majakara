@extends('layouts.nasabah')

@section('title', 'Dashboard')

@section('content')
    <!-- Data Akun Nasabah -->
    <x-nasabah.data-akun :user="$user" :dummyNasabah="$dummyNasabah ?? null" />
    
    <!-- Informasi Cards -->
    <x-nasabah.info-cards />
    
    <!-- Table Section -->
    <x-nasabah.table-section />
@endsection

