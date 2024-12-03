<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <style>
        .chat-bubble-left-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .chat-bubble-left {
            background-color: #f0ffde;
            color: #343434;
            border-radius: 20px;
            padding: 10px;
            position: relative;
            text-align: left;
        }

        .chat-bubble-date-left {
            font-size: 10px;
            margin-top: 10px;
            color: #898989;
            margin-left: 10px;
        }
    </style>

    @php
        $array_data = $getState();
    @endphp

    <div class="chat-bubble-left-wrapper">
        <div class="chat-bubble-left">
            {!! $array_data['text'] !!}
        </div>
        <div class="chat-bubble-date-left">
            {{ $array_data['date'] }}
        </div>
    </div>
</x-dynamic-component>
