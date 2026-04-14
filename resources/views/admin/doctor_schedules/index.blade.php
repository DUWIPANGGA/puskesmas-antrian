@extends('layouts.admin')

@section('title', 'Doctor Schedules')
@section('page-title', 'Doctor Schedules')

@section('content')
<div class="bg-white rounded-2xl p-6 shadow-sm border border-pink-50">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-black text-gray-900">Manage Schedules</h2>
            <p class="text-xs font-medium text-gray-500">Edit, add, or remove doctor working hours</p>
        </div>
        <button class="bg-[#f06292] text-white px-4 py-2 rounded-full text-xs font-bold flex items-center gap-1 hover:bg-[#d81b60] transition shadow-sm">
            <span class="material-symbols-outlined text-sm">add_circle</span> Add Schedule
        </button>
    </div>

    <div class="flex flex-col items-center justify-center py-16 text-center border-2 border-dashed border-gray-200 rounded-xl">
        <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">calendar_month</span>
        <h3 class="text-base font-bold text-gray-900">No Schedules Listed</h3>
        <p class="text-xs text-gray-500 mt-1 max-w-sm">There are no active doctor schedules at the moment. Click the button above to create one.</p>
    </div>
</div>
@endsection
