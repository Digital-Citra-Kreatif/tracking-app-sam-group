{{-- File: resources/views/livewire/customer/partials/shipment-notes.blade.php
     Responsibility: The shipment-level notes card in the customer portal.
     What it does: lists every note on the shipment, newest first, with its
       body, author ("You" for the viewer's own notes) and time. Container
       notes are not shown. The card hides while there are no notes.
     How to use: @include('livewire.customer.partials.shipment-notes', ['notes' => $notes]).
     How to extend: keep the fields in sync with the Filament notes panel. --}}
@if ($notes->isNotEmpty())
    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="border-b border-slate-200 px-4 py-3 sm:px-6">
            <h2 class="flex items-center gap-2 font-semibold text-slate-900">Notes</h2>
        </div>
        <ul class="divide-y divide-slate-100 px-4 sm:px-6">
            @foreach ($notes as $note)
                <li wire:key="portal-note-{{ $note->id }}" class="py-3">
                    <p class="whitespace-pre-line text-sm text-slate-700">{{ $note->body }}</p>
                    <p class="mt-1 text-xs text-slate-400">
                        <span class="font-medium text-slate-500">
                            {{ $note->author_id === auth()->id() ? 'You' : ($note->author?->name ?? 'Deleted user') }}
                        </span>
                        <span>·</span>
                        <span title="{{ $note->created_at->toDayDateTimeString() }}">
                            {{ $note->created_at->diffForHumans() }}
                        </span>
                    </p>
                </li>
            @endforeach
        </ul>
    </div>
@endif
