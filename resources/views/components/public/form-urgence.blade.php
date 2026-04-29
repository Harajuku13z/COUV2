<form method="POST" action="{{ route('public.leads.urgence') }}" class="space-y-4 rounded-3xl border border-red-200 bg-red-50 p-6">
    @csrf
    <input type="hidden" name="page_id" value="{{ $page->id ?? '' }}">
    <input type="text" name="company_name" class="hidden" tabindex="-1" autocomplete="off">
    <input name="name" class="w-full rounded-2xl border border-red-200 px-4 py-3" placeholder="Nom*" required>
    <input name="phone" class="w-full rounded-2xl border border-red-200 px-4 py-3" placeholder="Telephone*" required>
    <input name="service_requested" class="w-full rounded-2xl border border-red-200 px-4 py-3" placeholder="Service">
    <textarea name="message" rows="3" class="w-full rounded-2xl border border-red-200 px-4 py-3" placeholder="Message"></textarea>
    <button class="w-full rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white">Urgence — Appel immediat</button>
</form>
