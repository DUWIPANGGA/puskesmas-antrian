@extends('layouts.pasien')

@section('title', 'Check-in Arrival')

@section('content')

@if(session('popup_success'))
    <div class="mb-6 bg-green-50 text-green-700 border border-green-200 rounded-2xl p-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <p class="text-sm font-bold">{{ session('popup_success') }}</p>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-800"><span class="material-symbols-outlined">close</span></button>
    </div>
@endif

@if(session('popup_error'))
    <div class="mb-6 bg-red-50 text-red-700 border border-red-200 rounded-2xl p-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-red-600">error</span>
            <p class="text-sm font-bold">{{ session('popup_error') }}</p>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="text-red-600 hover:text-red-800"><span class="material-symbols-outlined">close</span></button>
    </div>
@endif

<div class="max-w-[1200px] mx-auto flex flex-col lg:flex-row gap-6 items-start h-[calc(100vh-140px)] overflow-hidden pb-4">
    
    @if(!$activeTicket)
        {{-- EMPTY STATE --}}
        <div class="flex-1 bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(216,27,96,0.03)] border border-pink-50 flex flex-col items-center justify-center h-full text-center p-12">
            <div class="w-32 h-32 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-gray-300 text-[80px]">location_off</span>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-2">No Active Tickets Today</h2>
            <p class="text-gray-500 font-medium max-w-sm mb-6">You don't have any appointments scheduled for today that require check-in.</p>
            <a href="{{ route('pasien.book-appointment') }}" class="bg-[#d81b60] text-white px-8 py-3.5 rounded-full font-bold text-sm hover:bg-[#c2185b] transition shadow-lg shadow-pink-500/30">
                Book an Appointment
            </a>
        </div>
    @else
        {{-- LEFT COLUMN --}}
        <div class="flex-1 flex flex-col gap-6 h-full overflow-y-auto pr-2 custom-scrollbar">
            
            {{-- Ticket Selector --}}
            @if($allTickets->count() > 1)
            <div class="flex gap-3 overflow-x-auto pb-2 custom-scrollbar shrink-0">
                @foreach($allTickets as $ticket)
                    <a href="?ticket_id={{ $ticket->id }}" class="flex items-center gap-3 bg-white p-3 pr-5 rounded-2xl border-2 transition whitespace-nowrap {{ $activeTicket->id == $ticket->id ? 'border-[#d81b60] shadow-md ring-1 ring-pink-50' : 'border-pink-50/50 hover:border-pink-200 opacity-80 hover:opacity-100' }}">
                        <div class="w-10 h-10 rounded-xl {{ \Carbon\Carbon::parse($ticket->tanggal)->isToday() ? 'bg-[#fce4ec] text-[#d81b60]' : 'bg-gray-100 text-gray-500' }} flex flex-col items-center justify-center shrink-0 leading-none">
                            <span class="text-[9px] font-black tracking-widest uppercase mb-0.5">{{ \Carbon\Carbon::parse($ticket->tanggal)->format('M') }}</span>
                            <span class="text-[14px] font-black">{{ \Carbon\Carbon::parse($ticket->tanggal)->format('d') }}</span>
                        </div>
                        <div>
                            <p class="text-[13px] font-black text-gray-900">{{ explode(' ', $ticket->poli->nama_poli)[0] }}</p>
                            <p class="text-[10px] font-bold text-gray-500 uppercase">{{ $ticket->nomor_antrian }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            @endif

            {{-- Ticket Info Card --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col relative overflow-hidden shrink-0">
                @if(\Carbon\Carbon::parse($activeTicket->tanggal)->isToday())
                <div class="absolute top-6 right-6 bg-[#f8bbd9] text-[#d81b60] text-[10px] font-black tracking-widest px-3 py-1 rounded-full uppercase">
                    TODAY
                </div>
                @else
                <div class="absolute top-6 right-6 bg-blue-100 text-blue-600 text-[10px] font-black tracking-widest px-3 py-1 rounded-full uppercase">
                    {{ \Carbon\Carbon::parse($activeTicket->tanggal)->format('d M') }}
                </div>
                @endif
                
                <div class="flex items-start gap-4 mb-8">
                    <div class="w-14 h-14 rounded-xl bg-pink-50 border border-pink-100 text-[#d81b60] flex items-center justify-center shrink-0">
                        <i class="{{ $activeTicket->poli->icon ?? 'fa-solid fa-stethoscope' }} text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-900 mb-1">{{ $activeTicket->poli->nama_poli }}</h2>
                        <p class="text-xs font-bold text-gray-500 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">person</span> 
                            Dr. {{ $activeTicket->jadwalDokter->dokter->name ?? 'TBA' }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-12 mb-6 ml-[72px]">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Time</p>
                        <p class="text-sm font-black text-gray-900">
                            {{ $activeTicket->jadwalDokter ? \Carbon\Carbon::parse($activeTicket->jadwalDokter->jam_mulai)->format('H:i A') : 'Queue' }}
                        </p>
                    </div>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Room</p>
                        <p class="text-sm font-black text-gray-900">Studio {{ $activeTicket->poli->kode_poli ?? '1A' }}</p>
                    </div>
                </div>

                <div class="mt-2 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if($activeTicket->status == 'check_in')
                            <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-black text-green-600">Status: Checked In</span>
                        @else
                            <div class="w-2.5 h-2.5 bg-amber-400 rounded-full animate-pulse"></div>
                            <span class="text-xs font-black text-amber-600">Status: Awaiting Arrival</span>
                        @endif
                    </div>
                    @if($activeTicket->status == 'menunggu')
                    <p class="text-[11px] font-medium text-gray-400 italic">Please check-in once you are on site.</p>
                    @endif
                </div>
            </div>

            {{-- Check-in Action Card --}}
            <div class="bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(216,27,96,0.03)] border border-pink-50 p-10 flex flex-col items-center justify-center text-center flex-1 relative overflow-hidden">
                
                @if($activeTicket->status == 'check_in')
                    {{-- Checked in state --}}
                    <div class="w-24 h-24 rounded-full bg-green-50 text-green-500 border border-green-100 flex items-center justify-center mb-6 ring-[12px] ring-green-50/50">
                        <span class="material-symbols-outlined text-[48px]">where_to_vote</span>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">You are checked in!</h3>
                    <p class="text-gray-500 font-medium mb-8 max-w-[280px]">Please wait comfortably in the designated area. The doctor will call your queue number shortly.</p>
                    <p class="text-xs font-bold text-gray-400 border border-gray-100 px-4 py-2 rounded-full">Your Queue: <span class="text-green-600">{{ $activeTicket->nomor_antrian }}</span></p>
                @else
                    {{-- Default Waiting State --}}
                    <div class="w-24 h-24 rounded-full bg-[#fae8f0] text-[#9f2b4c] flex items-center justify-center mb-6 border-4 border-white shadow-[0_0_0_8px_rgba(159,43,76,0.05)]">
                        <span class="material-symbols-outlined text-[48px]">location_on</span>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 mb-2">You're at the clinic!</h3>
                    
                    @if(!\Carbon\Carbon::parse($activeTicket->tanggal)->isToday())
                        <p class="text-gray-500 font-medium mb-8 max-w-[280px]">Jadwal kunjungan Anda bukan hari ini melainkan pada <span class="font-black text-[#d81b60]">{{ \Carbon\Carbon::parse($activeTicket->tanggal)->format('d M Y') }}</span>. Check-in hanya dapat dilakukan pada hari H.</p>
                        <button disabled class="w-full max-w-[320px] bg-gray-100 text-gray-400 py-4 rounded-full font-black text-[15px] flex items-center justify-center gap-2 cursor-not-allowed">
                            <span class="material-symbols-outlined">event_upcoming</span> Check-in Belum Tersedia
                        </button>
                    @else
                        <p class="text-gray-500 font-medium mb-8 max-w-[280px]">We've detected you are within the Puskesmas Jagapura grounds. Confirm your arrival to join the queue.</p>
                        <form action="{{ route('pasien.checkin') }}" method="POST" class="w-full max-w-[320px]">
                            @csrf
                            <input type="hidden" name="antrian_id" value="{{ $activeTicket->id }}">
                            <button type="submit" class="w-full bg-gradient-to-r from-[#90305a] to-[#ab3e6f] hover:from-[#7c284d] hover:to-[#90305a] text-white py-4 rounded-full font-black text-[15px] shadow-lg shadow-pink-900/20 flex items-center justify-center gap-2 transition transform hover:scale-[1.02]">
                                <span class="material-symbols-outlined">check_circle</span> Check-in Now
                            </button>
                        </form>
    
                        <button class="mt-6 flex items-center gap-2 text-[12px] font-bold text-[#b45c7e] hover:text-[#90305a] transition">
                            <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span> Or scan clinic QR code
                        </button>
                    @endif
                @endif
                
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="w-full lg:w-[360px] shrink-0 h-full overflow-y-auto pr-2 custom-scrollbar flex flex-col gap-6">
            
            {{-- Map Location --}}
            <div class="bg-[#78909c] h-[220px] rounded-3xl relative overflow-hidden shadow-sm flex-shrink-0">
                <img src="https://static.vecteezy.com/system/resources/previews/000/153/588/original/vector-city-map-background.jpg" alt="Map" class="absolute w-full h-full object-cover opacity-60 mix-blend-multiply grayscale">
                <div class="absolute inset-x-4 bottom-4 bg-white/90 backdrop-blur rounded-2xl p-4 flex items-center gap-4 shadow-lg top-auto">
                    <div class="w-10 h-10 rounded-full bg-[#b2dfdb] text-[#00695c] flex items-center justify-center drop-shadow-sm shrink-0">
                        <span class="material-symbols-outlined text-[20px]">near_me</span>
                    </div>
                    <div>
                        <p class="text-[12px] font-black text-gray-900 leading-tight">Current Location Verified</p>
                        <p class="text-[9px] font-bold text-gray-500 truncate mt-0.5">124 Wellness Way, Medical District</p>
                    </div>
                </div>
            </div>

            {{-- Arrival Info --}}
            <div class="bg-gradient-to-br from-[#e0f7fa] to-[#b2ebf2] rounded-3xl p-6 shadow-sm border border-[#b2ebf2]/50 flex-shrink-0">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-[#006064]">info</span>
                    <h3 class="text-[15px] font-black text-[#006064]">Arrival Info</h3>
                </div>

                <div class="flex flex-col gap-5 relative before:content-[''] before:absolute before:left-3 before:-ml-px before:top-2 before:bottom-2 before:w-px before:bg-[#80cbc4]">
                    
                    <div class="flex items-start gap-4 relative z-10">
                        <div class="w-5 h-5 rounded-full bg-white text-[#00838f] text-[10px] font-black flex items-center justify-center shrink-0 shadow-sm border border-[#80cbc4]">1</div>
                        <p class="text-[12px] font-medium text-[#006064] leading-relaxed pt-0.5">Please wait in the <span class="font-bold">Pink Lounge</span> area on the 2nd floor.</p>
                    </div>
                    
                    <div class="flex items-start gap-4 relative z-10">
                        <div class="w-5 h-5 rounded-full bg-white text-[#00838f] text-[10px] font-black flex items-center justify-center shrink-0 shadow-sm border border-[#80cbc4]">2</div>
                        <p class="text-[12px] font-medium text-[#006064] leading-relaxed pt-0.5">Keep your phone <span class="font-bold">active</span> for queue notifications and text alerts.</p>
                    </div>

                    <div class="flex items-start gap-4 relative z-10">
                        <div class="w-5 h-5 rounded-full bg-white text-[#00838f] text-[10px] font-black flex items-center justify-center shrink-0 shadow-sm border border-[#80cbc4]">3</div>
                        <p class="text-[12px] font-medium text-[#006064] leading-relaxed pt-0.5">Help yourself to <span class="font-bold">complimentary refreshments</span> at the bar.</p>
                    </div>

                </div>

                <div class="mt-8 bg-white/60 backdrop-blur rounded-2xl p-4 flex items-center gap-4 hover:bg-white/90 transition cursor-pointer">
                    <div class="text-[#00838f]"><span class="material-symbols-outlined">support_agent</span></div>
                    <div>
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-0.5">NEED HELP?</p>
                        <p class="text-[11px] font-bold text-[#006064]">Ask our concierge at the front desk</p>
                    </div>
                </div>
            </div>

            {{-- Status Banner at bottom right --}}
            @if($activeTicket->status == 'check_in')
                <div class="bg-gradient-to-r from-[#90305a] to-[#ab3e6f] rounded-[2rem] p-6 flex flex-col justify-center text-white shadow-lg shrink-0 border border-pink-900/20 mb-4 items-center">
                    <span class="material-symbols-outlined text-[32px] text-pink-200 mb-2">done_all</span>
                    <h3 class="text-xl font-black mb-1">Checked-in!</h3>
                    <p class="text-[11px] text-pink-100 font-medium">The doctor and admin have been notified.</p>
                </div>
            @else
                <div class="bg-gradient-to-r from-[#4dd0e1] to-[#26c6da] rounded-[2rem] p-6 flex items-center justify-between text-white shadow-sm shrink-0">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#e0f7fa] mb-1">EST. WAIT TIME</p>
                        <p class="text-3xl font-black tracking-tight">~12 min</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white">timer</span>
                    </div>
                </div>
            @endif

        </div>
    @endif
</div>

@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; } 
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; } 
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 99px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #bdbdbd; }
</style>
@endpush

@endsection
