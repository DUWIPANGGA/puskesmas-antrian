@extends('layouts.admin')

@section('title', 'Reset Queues')
@section('page-title', 'Reset Queues')

@section('content')
<div class="bg-white rounded-2xl p-6 shadow-sm border border-pink-50 max-w-2xl mx-auto mt-8">
    <div class="flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-4xl text-red-500">history</span>
        </div>
        <h2 class="text-2xl font-black text-gray-900 mb-2">Reset Daily Queues</h2>
        <p class="text-sm font-medium text-gray-500 mb-8 max-w-md">Warning: This action will clear all active and waiting patients from today's queue across all clinics. This action cannot be undone.</p>
        
        <div class="flex gap-4">
            <button class="bg-gray-100 text-gray-700 px-6 py-3 rounded-full text-sm font-bold hover:bg-gray-200 transition">
                Cancel
            </button>
            <button class="bg-red-500 text-white px-6 py-3 rounded-full text-sm font-bold flex items-center gap-2 hover:bg-red-600 transition shadow-md">
                <span class="material-symbols-outlined text-sm">warning</span> Confirm Reset
            </button>
        </div>
    </div>
</div>
@endsection
