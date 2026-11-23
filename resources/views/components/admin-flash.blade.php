@if (session('success'))
    <div data-aos="fade-up" class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 flex items-center justify-between text-emerald-900 shadow-sm">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
        <span class="text-[11px] uppercase tracking-widest text-emerald-700 font-semibold">Saved</span>
    </div>
@endif

@if (session('error'))
    <div data-aos="fade-up" class="mb-6 rounded-2xl border border-red-200 bg-red-50/90 px-5 py-4 flex items-center justify-between text-red-900 shadow-sm">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
        <span class="text-[11px] uppercase tracking-widest text-red-700 font-semibold">Alert</span>
    </div>
@endif

@if ($errors->any())
    <div data-aos="fade-up" class="mb-6 rounded-2xl border border-red-200 bg-red-50/90 px-5 py-4 text-red-900 shadow-sm">
        <p class="text-sm font-semibold mb-1">Please fix the following errors:</p>
        <ul class="list-disc list-inside text-xs space-y-1 text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
