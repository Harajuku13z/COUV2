@extends('layouts.admin')
@section('title', 'Modifier la realisation')

@section('content')
<div class="space-y-8">
    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="admin-panel admin-panel-strong p-4 p-lg-5">
        <div class="admin-section-head">
            <div>
                <h2 class="admin-section-title">{{ $realization->title }}</h2>
                <p class="admin-section-copy">Ajuste les textes, ajoute de nouvelles photos ou retire celles qui ne doivent plus apparaître.</p>
            </div>
            <a href="{{ \App\Support\CentralAppUrl::admin('realizations') }}" class="admin-link-btn admin-btn-secondary">Retour à la liste</a>
        </div>

        <form action="{{ \App\Support\CentralAppUrl::admin('realizations/'.$realization->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.realizations._form', ['realization' => $realization])

            <div class="d-flex flex-wrap gap-3">
                <button type="submit" class="admin-btn admin-btn-primary">Mettre à jour</button>
                <a href="{{ \App\Support\CentralAppUrl::admin('realizations') }}" class="admin-link-btn admin-btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
