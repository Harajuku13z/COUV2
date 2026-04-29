<form method="POST" action="{{ route('public.leads.devis') }}" enctype="multipart/form-data" class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    <input type="hidden" name="page_id" value="{{ $page->id ?? '' }}">
    <input type="hidden" name="source_url" value="{{ url()->current() }}">
    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
    <input type="text" name="company_name" class="hidden" tabindex="-1" autocomplete="off">
    <div class="grid gap-4 md:grid-cols-2">
        <input name="name" class="rounded-2xl border border-slate-300 px-4 py-3" placeholder="Nom*" required>
        <input name="phone" class="rounded-2xl border border-slate-300 px-4 py-3" placeholder="Telephone*" required>
        <input name="email" class="rounded-2xl border border-slate-300 px-4 py-3" placeholder="Email">
        <input name="city_label" class="rounded-2xl border border-slate-300 px-4 py-3" placeholder="Ville">
    </div>
    <input name="service_requested" class="w-full rounded-2xl border border-slate-300 px-4 py-3" placeholder="Service souhaite">
    <textarea name="message" rows="4" class="w-full rounded-2xl border border-slate-300 px-4 py-3" placeholder="Decrivez votre besoin"></textarea>
    <input type="file" name="uploaded_files[]" multiple class="w-full rounded-2xl border border-dashed border-slate-300 px-4 py-3">
    <button class="w-full rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--brand-primary)">
        Obtenir mon devis gratuit — Reponse sous 2h
    </button>
</form>
