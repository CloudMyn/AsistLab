<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">


    <div class="chat-bubble">
        {{ $getState() }}
    </div>


</x-dynamic-component>
