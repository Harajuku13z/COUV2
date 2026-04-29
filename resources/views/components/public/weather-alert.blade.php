@if(($weatherAlert ?? null) !== null)
    <div x-data="{open:true}" x-show="open" class="border-y border-amber-200 bg-amber-50">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 text-sm text-amber-900">
            <p>
                Alerte meteo : {{ $weatherAlert->event_type }} {{ $weatherAlert->intensity }}.
                {{ $weatherAlert->description }}
            </p>
            <button type="button" @click="open=false" class="font-medium">Fermer</button>
        </div>
    </div>
@endif
