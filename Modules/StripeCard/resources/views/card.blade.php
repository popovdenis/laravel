<script src="https://js.stripe.com/v3/"></script>

@if(session('success'))
    <div class="text-green-700 bg-green-100 border px-4 py-2 mb-4 rounded">
        {{ session('success') }}
    </div>
@endif

<form id="card-form" method="POST" action="{{ route('stripecard::attach') }}">
    @csrf
    <input type="hidden" name="payment_method" id="payment-method">
    <div id="card-element" class="mb-4 p-4 border rounded"></div>
    <button type="submit" id="submit-button" class="bg-blue-600 text-white px-4 py-2 rounded">Save Card</button>
</form>

<script>
    const stripe = Stripe('{{ config('cashier.key') }}');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('card-form');
    const clientSecret = @json($clientSecret);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const {setupIntent, error} = await stripe.confirmCardSetup(clientSecret, {
            payment_method: {
                card: card,
            }
        });

        if (error) {
            alert(error.message);
        } else {
            document.getElementById('payment-method').value = setupIntent.payment_method;
            form.submit();
        }
    });
</script>
