@extends('layouts.admin')

@section('title', 'Manage Health Tips')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center justify-between mb-8">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-black text-gray-900">Health Tips Management</h1>
            <p class="mt-2 text-sm text-gray-700">Manage health tips and advice displayed on the patient dashboard.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button onclick="openModal('addModal')" class="inline-flex items-center justify-center rounded-full bg-[#d81b60] px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#c2185b] transition">
                <span class="material-symbols-outlined mr-2">add</span> Add New Tip
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tips as $tip)
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <span class="bg-[#fce4ec] text-[#d81b60] text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider">
                        {{ $tip->category }}
                    </span>
                    <div class="flex gap-2">
                        <button onclick="editTip({{ $tip }})" class="text-blue-500 hover:bg-blue-50 p-2 rounded-full transition">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <form action="{{ route('admin.health-tips.destroy', $tip) }}" method="POST" onsubmit="return confirm('Delete this tip?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-full transition">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                
                <h3 class="mt-4 text-base font-black text-gray-900 leading-snug">{{ $tip->tip }}</h3>
                <p class="mt-2 text-sm text-gray-500 line-clamp-3 leading-relaxed">{{ $tip->content }}</p>
            </div>
            
            <div class="mt-auto px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <form action="{{ route('admin.health-tips.toggle', $tip) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2">
                        <div class="w-10 h-5 rounded-full relative transition {{ $tip->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                            <div class="absolute top-1 left-1 w-3 h-3 bg-white rounded-full transition-transform {{ $tip->is_active ? 'translate-x-5' : '' }}"></div>
                        </div>
                        <span class="text-[11px] font-bold text-gray-500">{{ $tip->is_active ? 'Active' : 'Draft' }}</span>
                    </button>
                </form>
                <div class="text-[10px] font-bold text-gray-400">Order: {{ $tip->order }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Add Modal --}}
<div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-8 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-900">Add New Health Tip</h3>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.health-tips.store') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Category</label>
                <input type="text" name="category" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-pink-500/20 outline-none transition" placeholder="e.g. Mental Wellness">
                <input type="hidden" name="icon" value="lightbulb">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Main Tip (Short)</label>
                <input type="text" name="tip" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-pink-500/20 outline-none transition" placeholder="e.g. Try 5 minutes of mindful breathing">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Full Content / Explanation</label>
                <textarea name="content" rows="4" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-pink-500/20 outline-none transition resize-none"></textarea>
            </div>
            <button type="submit" class="w-full bg-[#d81b60] text-white py-4 rounded-2xl font-black shadow-lg shadow-pink-500/20 hover:bg-[#c2185b] transition">Save Health Tip</button>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-lg overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-900">Edit Health Tip</h3>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-8 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Category</label>
                <input type="text" name="category" id="edit_category" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-pink-500/20 transition outline-none">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Main Tip</label>
                <input type="text" name="tip" id="edit_tip" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-pink-500/20 transition outline-none">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Full Content</label>
                <textarea name="content" id="edit_content" rows="4" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-pink-500/20 transition outline-none resize-none"></textarea>
            </div>
            <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black hover:bg-gray-800 transition">Update Health Tip</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    
    function editTip(tip) {
        document.getElementById('editForm').action = "/dashboard/admin/health-tips/" + tip.id;
        document.getElementById('edit_category').value = tip.category;
        document.getElementById('edit_tip').value = tip.tip;
        document.getElementById('edit_content').value = tip.content;
        openModal('editModal');
    }
</script>
@endsection
