<div class="overflow-x-auto rounded-2xl border border-slate-200">
    <table class="w-full border-collapse text-left">
        <thead>
            <tr class="bg-[#10243A] text-xs font-bold uppercase tracking-wider text-white">
                <th class="px-6 py-4">Batch / Jadwal</th>
                <th class="px-6 py-4">Program</th>
                <th class="hidden px-6 py-4 md:table-cell">Lokasi & Mode</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            @forelse($upcomingSessions as $session)
                <tr class="transition-colors hover:bg-slate-50/80">
                    <td class="px-6 py-4">
                        <div class="font-bold text-[#10243A]">
                            {{ $session->start_date->translatedFormat('d M Y') }}
                            @if($session->end_date && $session->end_date != $session->start_date)
                                - {{ $session->end_date->translatedFormat('d M Y') }}
                            @endif
                        </div>
                        <span class="block text-xs font-medium text-slate-400">{{ $session->batch_name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('courses.show', $session->course->slug) }}" class="font-bold text-[#10243A] transition-colors hover:text-[#087F78]">
                            {{ $session->course->title }}
                        </a>
                        <span class="block text-xs text-slate-400">
                            {{ $session->course->category->name }} • ⏱ {{ $session->duration }}
                        </span>
                    </td>
                    <td class="hidden px-6 py-4 text-xs text-slate-600 md:table-cell">
                        <div class="font-medium text-[#10243A]">{{ $session->location }}</div>
                        <span class="text-slate-400">Sisa Kuota: {{ max(0, $session->quota - $session->registrations_count) }} kursi</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($session->status === 'Buka')
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3.5 py-1 text-xs font-bold text-emerald-800">Buka</span>
                        @elseif($session->status === 'Segera')
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-3.5 py-1 text-xs font-bold text-amber-800">Segera</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-rose-100 px-3.5 py-1 text-xs font-bold text-rose-800">Penuh</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($session->status === 'Buka')
                            <a href="{{ route('register', ['course_id' => $session->course_id, 'session_id' => $session->id]) }}" class="inline-block rounded-xl bg-[#087F78] px-4 py-2 text-xs font-bold text-white shadow-xs transition-all hover:bg-[#066761]">Daftar</a>
                        @elseif($session->status === 'Segera')
                            <span class="inline-block cursor-default rounded-xl bg-[#E2F6F2] px-4 py-2 text-xs font-bold text-[#087F78]">Segera dibuka</span>
                        @elseif(config('services.whatsapp.admin_number'))
                            <a href="https://wa.me/{{ preg_replace('/\D+/', '', config('services.whatsapp.admin_number')) }}?text={{ rawurlencode('Halo Admin JCP, saya ingin masuk Waiting List kelas '.$session->course->title) }}" target="_blank" rel="noopener" class="inline-block rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-400 hover:text-slate-600">Waitlist</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">Belum ada jadwal pelatihan yang tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($upcomingSessions->hasPages())
    <div data-home-schedule-pagination class="border-t border-slate-100 pt-6">
        {{ $upcomingSessions->links() }}
    </div>
@endif
