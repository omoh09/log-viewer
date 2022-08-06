@php
    /** @var \Arukompas\BetterLogViewer\LogFile $file */
@endphp
<div class="h-full w-full py-5">
    @empty($selectedFileName)
        <div class="flex h-full items-center justify-center">
            Please select a file...
        </div>
    @else
        <div class="flex flex-col h-full w-full mx-3 mb-4">
            <div class="px-4 mb-4 flex items-center">
                <div class="flex-1 mr-6">
                    <p class="text-xs text-gray-500 mb-2">Memory: <span class="font-semibold">{{ $memoryUsage }}</span>, Duration: <span class="font-semibold">{{ $requestTime }}</span></p>
                    @foreach($levels as $levelCount)
                        @continue($levelCount->count === 0)
                        <span class="badge {{ $levelCount->level->getClass() }} @if($levelCount->selected) active @endif"
                              wire:click="toggleLevel('{{ $levelCount->level->value }}')"
                        >
                            <span class="opacity-90">{{ $levelCount->level->name }}:</span>
                            <span class="font-semibold">{{ number_format($levelCount->count) }}</span>
                        </span>
                    @endforeach
                </div>
                <div class="flex-1">
                    <label for="query" class="sr-only">Search</label>
                    <div class="relative">
                        <input name="query" id="query" type="text"
                               class="border rounded-md pl-3 pr-10 py-2 mb-2 w-full" placeholder="Search..."
                               wire:model.lazy="query"
                        />
                        <div class="absolute top-0 right-0 p-1.5">
                            @if(!empty($query))
                            <button class="text-gray-500 hover:text-gray-600 p-1" wire:click="clearQuery">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden">
                <div class="absolute top-0 h-6 w-full bg-gradient-to-b from-gray-100 to-transparent"></div>

                <div class="log-item-container h-full overflow-y-scroll text-sm py-6 px-4">
                    <div class="rounded-md">
                        @forelse($logs as $log)
                        <div wire:key="log-item-{{ $log->index }}" class="log-item {{ $log->level->getClass() }}" x-data="{ showStack: false }" x-bind:class="showStack ? 'active' : ''">
                            <div class="cursor-pointer pl-2 pr-4 py-3 flex" x-on:click="showStack = !showStack">
                                <div class="log-level-indicator h-full rounded w-1 mr-2">&nbsp;</div>
                                <div class="flex overflow-hidden">
                                    <div class="whitespace-nowrap">
                                        <span class="log-time">{{ $log->time->toDateTimeString() }}</span>
                                        <span class="mx-1.5">·</span>
                                        <span class="log-level font-semibold">{{ strtoupper($log->level->value) }}</span>
                                        <span class="log-context text-gray-500">@ {{ $log->environment }}</span>
                                        <span class="ml-1.5 mr-2">·</span>
                                    </div>
                                    <div class="flex-none">
                                        <p class="text-gray-700">{{ $log->text }}</p>
                                    </div>
                                </div>
                            </div>
                            <pre class="log-stack px-3 py-2 border-t border-gray-200 text-xs whitespace-pre-wrap break-all" x-show="showStack">{{ $log->fullText }}</pre>
                        </div>
                        @empty
                            <div class="my-12">
                                <div class="text-center font-semibold italic">No results...</div>
                                @if(!empty($query))
                                <div class="text-center mt-6">
                                    <button class="px-3 py-2 border border-200 bg-white text-gray-800 rounded-md" wire:click="clearQuery">Clear search query</button>
                                </div>
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="absolute bottom-0 h-8 w-full bg-gradient-to-t from-gray-100 to-transparent"></div>

                <div class="absolute hidden inset-0 py-6 px-4" wire:loading.class.remove="hidden">
                    <div class="rounded-md bg-white opacity-90 w-full h-full flex items-center justify-center">
                        <div class="loader">Loading...</div>
                    </div>
                </div>
            </div>

            @if($logs->hasPages())
            <div class="px-4 mb-5">
                {{ $logs->links('better-log-viewer::pagination') }}
            </div>
            @endif
        </div>
    @endempty
</div>