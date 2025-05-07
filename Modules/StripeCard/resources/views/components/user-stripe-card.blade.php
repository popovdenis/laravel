<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ $stripeKey }}');
</script>

<div class="bg-gray-100 px-4 py-6">
    <h2 class="text-xl font-bold inline-block mb-4">
        {{ __('My Card') }}
    </h2>

    <div class="bg-white p-4 rounded shadow-sm flex flex-col">
        <div x-data="stripeCardForm({ stripe, elements: stripe.elements(), clientSecret: '{{ $clientSecret }}', hasCard: {{ $hasCard ? 'true' : 'false' }} })"
             x-init="init()" class="flex-1"
        >
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                <div x-show="!showForm && '{{ $hasCard }}'" class="flex flex-col justify-between rounded">
                    @if($hasCard)
                        <div class="text-gray-700">
                            <p class="text-sm font-medium"><strong>{{ strtoupper($card->brand) }} **** **** **** {{ $card->last4 }}</strong></p>
                            <p class="text-sm text-gray-500">Expires {{ $card->exp_month }}/{{ $card->exp_year }}</p>
                        </div>
                    @endif
                    <div class="flex items-center space-x-2 mt-4">
                        <button @click="showCardForm()" class="px-4 py-2 mr-2 btn-secondary rounded">
                            {{ __('Change card')  }}
                        </button>
                        <div x-data="{ showModal: false }">
                            <button @click="showModal = true" class="px-4 py-2 btn-cancel rounded">
                                {{ __('Delete Card') }}
                            </button>

                            <!-- Modal overlay -->
                            <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                                <!-- Modal content -->
                                <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Confirm deletion</h3>
                                    <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete your saved card?</p>
                                    <div class="flex justify-end space-x-3">
                                        <form method="POST" action="{{ route('stripecard::detach') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                                Delete
                                            </button>
                                        </form>
                                        <button @click="showModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <form x-show="showForm" x-cloak method="POST" action="{{ route('stripecard::attach') }}" id="card-form">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-lg font-medium text-gray-700 mb-2">
                                {{ __('Card Details') }}
                            </label>
                            <div id="card-element" class="p-3 border border-gray-300 rounded bg-gray-50"></div>
                            <div id="card-errors" class="text-sm text-red-600 mt-2"></div>
                        </div>

                        <input type="hidden" name="payment_method" id="payment-method" />

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="px-5 py-2 text-sm bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                                {{ __('Save Card') }}
                            </button>
                            @if($hasCard)
                                <button type="button" @click="showForm = false" class="text-sm text-gray-500 hover:underline">
                                    {{ __('Cancel') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function stripeCardForm({ stripe, elements, clientSecret, hasCard }) {
        return {
            showForm: !hasCard,
            stripe,
            elements,
            card: null,
            isProcessing: false,

            init() {
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

                    if (this.isProcessing) return;
                    this.isProcessing = true;

                    const { setupIntent, error } = await this.stripe.confirmCardSetup(clientSecret, {
                        payment_method: { card: this.card }
                    });

                    if (error) {
                        document.getElementById('card-errors').textContent = error.message;
                        this.isProcessing = false;
                    } else {
                        document.getElementById('payment-method').value = setupIntent.payment_method;
                        form.submit();
                    }
                });
            }
        };
    }
</script>
