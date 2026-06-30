<x-organization-sidebar-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">SumUp Card Readers</h1>
                <p class="mt-2 text-gray-600">Pair your SumUp Solo card readers to accept card donations in person.</p>
            </div>
            <a href="{{ route('organization.sumup.show') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">&larr; Back to SumUp</a>
        </div>

        @if(session('completed'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-6">
                <p class="text-sm text-green-700">{{ session('completed') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <!-- How to pair -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">How to pair a Solo reader</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-blue-900">
                <li>Turn on your SumUp Solo terminal and connect it to the internet.</li>
                <li>On the terminal, open <strong>Settings &rarr; Card Reader Pairing</strong>. A pairing code will appear (e.g. <code>ABCD-EFGH</code>).</li>
                <li>Enter that code below along with a name to identify this reader (e.g. "Front desk").</li>
                <li>Click <strong>Pair reader</strong>. The terminal will confirm pairing within a few seconds.</li>
            </ol>
        </div>

        <!-- Pair form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pair a new reader</h3>

            <form method="POST" action="{{ route('organization.sumup.readers.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="pairing_code" class="block text-sm font-medium text-gray-700 mb-2">Pairing code *</label>
                        <input type="text"
                               name="pairing_code"
                               id="pairing_code"
                               required
                               autocomplete="off"
                               minlength="4"
                               maxlength="16"
                               value="{{ old('pairing_code') }}"
                               placeholder="ABCD-EFGH"
                               style="text-transform: uppercase;"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-mono text-sm @error('pairing_code') border-red-500 @enderror">
                        @error('pairing_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Reader name *</label>
                        <input type="text"
                               name="name"
                               id="name"
                               required
                               minlength="2"
                               maxlength="60"
                               value="{{ old('name') }}"
                               placeholder="e.g. Front desk"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="submit" class="btn-primary px-8">Pair reader</button>
                </div>
            </form>
        </div>

        <!-- Existing readers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Paired readers</h3>

            @if($readers->isEmpty())
                <p class="text-sm text-gray-500">No readers paired yet. Use the form above to pair your first Solo terminal.</p>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($readers as $reader)
                        <div class="py-4 flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-3">
                                    <h4 class="text-base font-semibold text-gray-900">{{ $reader->name }}</h4>
                                    @if($reader->isPaired())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Paired</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">{{ ucfirst($reader->status) }}</span>
                                    @endif
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    ID: <code>{{ $reader->sumup_reader_id }}</code>
                                    @if($reader->device_model) &middot; {{ $reader->device_model }} @endif
                                    @if($reader->device_serial_number) &middot; SN {{ $reader->device_serial_number }} @endif
                                </p>
                                @if($reader->last_seen_at)
                                    <p class="text-xs text-gray-400">Paired {{ $reader->last_seen_at->diffForHumans() }}</p>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('organization.sumup.readers.destroy', $reader) }}"
                                  onsubmit="return confirm('Unpair this reader? You will need a fresh pairing code from the terminal to re-pair it.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 rounded-lg hover:bg-red-100">Unpair</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-organization-sidebar-layout>
