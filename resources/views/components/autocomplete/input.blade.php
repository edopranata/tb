<div class="form-group"
     x-data="
        {
            open: @entangle('showDropdown'),
            search: @entangle('search'),
            selected: @entangle('selected'),
            highlightedIndex: 0,
            highlightPrevious() {
                if (this.highlightedIndex > 0) {
                  this.highlightedIndex = this.highlightedIndex - 1;
                  this.scrollIntoView();
                }
            },
            highlightNext() {
                if (this.highlightedIndex < this.$refs.results.children.length - 1) {
                  this.highlightedIndex = this.highlightedIndex + 1;
                  this.scrollIntoView();
                }
            },
            scrollIntoView() {
                this.$refs.results.children[this.highlightedIndex].scrollIntoView({
                  block: 'nearest',
                  behavior: 'smooth'
                });
            },
            updateSelected(id, name) {
                this.selected = id;
                this.search = name;
                this.open = false;
                this.highlightedIndex = 0;
                },
            }"
>
    <div x-on:value-selected="updateSelected($event.detail.id, $event.detail.name)">
        <label>{{ $slot }}</label>
        <input class="form-control-lg form-control rounded-0"
               wire:model.debounce.300ms="search"
               x-on:keydown.arrow-down.stop.prevent="highlightNext()"
               x-on:keydown.arrow-up.stop.prevent="highlightPrevious()"
               x-on:keydown.enter.stop.prevent="$dispatch('value-selected', {
                    id: $refs.results.children[highlightedIndex].getAttribute('data-result-id'),
                    name: $refs.results.children[highlightedIndex].getAttribute('data-result-name')
              })">
        <div class="tw-absolute tw-w-full pr-3" x-show="open" x-on:click.away="open = false">
            <ul class="nav nav-pills flex-column tw-bg-slate-100 tw-rounded-b-xl tw-bordered tw-border-indigo-700 tw-text-slate-700" x-ref="results">
                @isset($results)
                    @forelse($results as $index => $result)
                        <li class="py-2 px-4"
                            wire:key="{{ $index }}"
                            x-on:click.stop="$dispatch('value-selected', {
                                                        id: {{ $result->id }},
                                                        name: '{{ $result->name }}'
                                                      })"
                            :class="{
                                                        'tw-bg-slate-400': {{ $index }} === highlightedIndex
                                                      }"
                            data-result-id="{{ $result->id }}"
                            data-result-name="{{ $result->name }}">
                            <span>
                              {{ $result->barcode . ' - ' . $result->name }}
                            </span>
                        </li>
                    @empty
                        <li class="py-2 px-4 tw-bg-slate-400">No results found</li>
                    @endforelse
                @endisset
            </ul>
        </div>
    </div>
</div>
