<script src="https://js.stripe.com/v3/"></script>
@php
    $user = auth()->user();
    $hasCard = $user->hasDefaultPaymentMethod();
@endphp

<div class="bg-white rounded-lg shadow-sm p-6 flex items-start gap-4">
    <div class="text-blue-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-2 0-3.5 1.5-3.5 3s1.5 3 3.5 3 3.5 1.5 3.5 3-1.5 3-3.5 3m0-16v1m0 18v1" />
        </svg>
    </div>

    <div x-data="stripeCardForm({{ json_encode(['hasCard' => $hasCard, 'stripeKey' => config('cashier.key')]) }})"
         x-init="init()" class="flex-1"
    >
        <h2 class="text-xl font-semibold mb-4 text-gray-800">My Card</h2>

        <div x-show="!showForm && '{{ $hasCard }}'" class="flex items-center justify-between rounded">
            @php $card = $user->defaultPaymentMethod()->card; @endphp
            <div class="text-gray-700">
                <p class="text-sm font-medium"><strong>{{ strtoupper($card->brand) }} **** **** **** {{ $card->last4 }}</strong></p>
                <p class="text-sm text-gray-500">Expires {{ $card->exp_month }}/{{ $card->exp_year }}</p>
            </div>
            <button @click="showCardForm()" class="ml-4 px-4 py-2 bg-gray-200 text-sm text-gray-700 rounded hover:bg-gray-300">
                Change card
            </button>
        </div>

        <form x-show="showForm" x-cloak method="POST" action="{{ route('stripecard::attach') }}" id="card-form" class="mt-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Card Details</label>
                <div id="card-element" class="p-3 border rounded bg-gray-50"></div>
                <div id="card-errors" class="text-sm text-red-600 mt-2"></div>
            </div>
            <input type="hidden" name="payment_method" id="payment-method" />
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Card
                </button>
                @if($hasCard)
                    <button type="button" @click="showForm = false" class="text-sm text-gray-500 hover:underline">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    function stripeCardForm({ hasCard, stripeKey }) {
        return {
            showForm: !hasCard,
            stripe: null,
            elements: null,
            card: null,

            init() {
                this.stripe = Stripe(stripeKey);
                this.elements = this.stripe.elements();
                if (this.showForm) this.mountCard();
                this.handleSubmit();
            },

            showCardForm() {
                this.showForm = true;
                this.$nextTick(() => this.mountCard());
            },

            mountCard() {
                if (!this.card) {
                    this.card = this.elements.create('card');
                    this.card.mount('#card-element');
                    this.card.on('change', event => {
                        const errorDisplay = document.getElementById('card-errors');
                        errorDisplay.textContent = event.error ? event.error.message : '';
                    });
                }
            },

            handleSubmit() {
                const form = document.getElementById('card-form');
                if (!form) return;

                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const { setupIntent, error } = await this.stripe.confirmCardSetup('{{ auth()->user()->createSetupIntent()->client_secret }}', {
                        payment_method: { card: this.card }
                    });

                    if (error) {
                        document.getElementById('card-errors').textContent = error.message;
                    } else {
                        document.getElementById('payment-method').value = setupIntent.payment_method;
                        form.submit();
                    }
                });
            }
        };
    }
</script>
