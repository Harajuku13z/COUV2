<form method="POST" action="{{ route('public.leads.devis') }}" enctype="multipart/form-data" class="space-y-5 rounded-[2rem] border border-slate-200/80 bg-white/95 p-6 shadow-[0_24px_60px_rgba(15,23,42,0.08)] backdrop-blur md:p-7">
    @csrf
    <input type="hidden" name="page_id" value="{{ $page->id ?? '' }}">
    <input type="hidden" name="source_url" value="{{ url()->current() }}">
    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
    <input type="text" name="company_name" class="hidden" tabindex="-1" autocomplete="off">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Estimation</p>
        <h3 class="mt-2 text-2xl font-semibold text-slate-950">Parle-nous de ton projet</h3>
        <p class="mt-2 text-sm leading-6 text-slate-600">Réponse claire, rappel rapide et devis adapté à ton besoin.</p>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <input name="name" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3.5" placeholder="Nom*" required>
        <input name="phone" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3.5" placeholder="Téléphone*" required>
        <input name="email" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3.5" placeholder="Email">
        <input name="city_label" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3.5" placeholder="Ville">
    </div>
    <input name="service_requested" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3.5" placeholder="Service souhaité">
    <textarea name="message" rows="4" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3.5" placeholder="Décris ton besoin, le contexte ou l’urgence éventuelle"></textarea>
    <input type="file" name="uploaded_files[]" multiple class="w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3.5">
    <button class="w-full rounded-full px-5 py-3.5 text-sm font-semibold text-white shadow-sm" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));">
        Obtenir mon devis gratuit
    </button>
    <p class="text-center text-xs uppercase tracking-[0.22em] text-slate-400">Réponse sous 2h ouvrées</p>
</form>
