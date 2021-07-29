<p>This is a sample payment requests form. Modify it according to the Payment Gateway you're implementing</p>

<form method="post" action="{{ $url }}" name="{:payment_gateway_name|slug:}" target="_self">
    @if($autoRedirect)
        <p>{{ __('You will be redirected to the secure payment page') }}</p>
        <p>
            <img src="{{ $url }}" alt="" title=""
                 onload="javascript:document.{:payment_gateway_name|slug:}.submit()">
        </p>
    @endif
        <button type="submit">
            {{ __('Proceed to Payment') }}
        </button>
</form>
