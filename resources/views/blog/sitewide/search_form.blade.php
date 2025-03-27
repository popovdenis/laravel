<div class="max-w-xl mx-auto my-12 p-6 bg-white rounded shadow">
    <form method="GET" action="{{ route('blog.search', app('request')->get('locale')) }}" class="text-center space-y-4">
        <h4 class="text-lg font-semibold text-gray-800">Search for something in our blog:</h4>

        <input
            type="text"
            name="s"
            placeholder="Search..."
            value="{{ \Request::get('s') }}"
            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-300"
        >

        <input
            type="submit"
            value="Search"
            class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
        >
    </form>
</div>
