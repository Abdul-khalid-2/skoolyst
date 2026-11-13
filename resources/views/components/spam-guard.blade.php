{{-- Captcha-free bot protection fields. Place inside <form>, right after @csrf. --}}
<div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
    <label for="{{ config('spam.honeypot_field') }}">Leave this field empty</label>
    <input type="text" id="{{ config('spam.honeypot_field') }}" name="{{ config('spam.honeypot_field') }}" value="" tabindex="-1" autocomplete="off">
</div>
<input type="hidden" name="{{ config('spam.time_field') }}" value="{{ encrypt(now()->timestamp) }}">
@error('spam')
    <div class="input-error" style="margin-bottom:1rem;">{{ $message }}</div>
@enderror
