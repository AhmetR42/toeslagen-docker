{{-- Eenvoudige lijn-iconen per toeslag (wit op een blauwe tegel, zoals in het Canva-ontwerp) --}}
<div class="icoon" aria-hidden="true">
@switch($slug)
@case('huurtoeslag')
<svg viewBox="0 0 24 24"><path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/></svg>
@break
@case('zorgtoeslag')
<svg viewBox="0 0 24 24"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M12 9v6M9 12h6"/></svg>
@break
@case('kinderopvangtoeslag')
<svg viewBox="0 0 24 24"><circle cx="8" cy="7" r="3"/><circle cx="17" cy="9" r="2.3"/><path d="M3 20c0-3.5 2.2-6 5-6s5 2.5 5 6"/><path d="M13.5 20c0-2.6 1.6-4.6 3.5-4.6S20.5 17.4 20.5 20"/></svg>
@break
@default
<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/></svg>
@endswitch
</div>
