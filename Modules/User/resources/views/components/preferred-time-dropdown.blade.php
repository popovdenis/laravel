<x-static-dropdown align="right" width="w-[27rem]">
    <x-slot name="trigger">
        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
            <div>Set Preferred Time</div>
            <div class="ms-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </button>
    </x-slot>

    <x-slot name="content">
        <div x-data="preferredTimePicker()" x-init="init()" class="flex items-center space-x-2 px-4 py-3">
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Start</label>
                <input type="text"
                       x-ref="start"
                       class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2 w-20"
                       placeholder="HH:MM">

                <label class="text-sm text-gray-600">End</label>
                <input type="text"
                       x-ref="end"
                       class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2 w-20"
                       placeholder="HH:MM">

                <button @click="submitPreferredTime" class="btn btn-primary text-sm">Apply</button>
                <button @click="open = false" class="btn btn-secondary text-sm" x-ref="closeBtn">Cancel</button>
            </div>
        </div>
    </x-slot>
</x-static-dropdown>

{{--<!-- Flatpickr CSS -->--}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
{{--<!-- Flatpickr JS -->--}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('preferredTimePicker', () => ({
            start: '{{ $startTime?->format('H:i') }}',
            end: '{{ $endTime?->format('H:i') }}',

            init() {
                flatpickr(this.$refs.start, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    defaultDate: this.start,
                    onClose: () => setTimeout(() => {
                        this.$refs.end._flatpickr.open();
                    }, 1),
                    onChange: ([date]) => {
                        this.start = date.toTimeString().slice(0, 5);
                        this.$refs.end._flatpickr.set('minTime', date);
                    },
                });

                flatpickr(this.$refs.end, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    defaultDate: this.end,
                    onChange: ([date]) => {
                        this.end = date.toTimeString().slice(0, 5);
                        this.$refs.start._flatpickr.set('maxTime', date);
                    },
                    onClose: () => setTimeout(() => {
                        this.$dispatch('preferred-time-updated', {
                            start: this.start,
                            end: this.end
                        });
                    }, 1),
                });
            },

            async submitPreferredTime() {
                try {
                    const res = await axios.post('/booking/preferred-time', {
                        start_time: this.start,
                        end_time: this.end,
                    });

                    if (res.data.success) {
                        window.dispatchEvent(new Event('preferred-time-updated', {
                            detail: { start: this.start, end: this.end }
                        }));

                        this.closeDropdown();
                    }
                } catch (e) {}
            },
            cancelDropdown() {
                this.closeDropdown();
            },
            closeDropdown() {
                this.$refs.closeBtn.click();
                this.$refs.start.focus();
                this.$refs.end.focus();
            }
        }))
    });
</script>


