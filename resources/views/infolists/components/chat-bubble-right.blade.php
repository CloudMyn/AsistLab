<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <style>
        .chat-bubble-right-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: flex-end;
        }

        .chat-bubble-right {
            background-color: #fffbcc;
            color: #343434;
            border-radius: 20px;
            padding: 10px;
            position: relative;
        }

        .chat-bubble-date-right {
            font-size: 10px;
            margin-top: 10px;
            color: #898989;
            margin-right: 10px;
        }
    </style>

    @php
        $array_data = $getState();
    @endphp

    <div class="chat-bubble-right-wrapper">
        <div class="chat-bubble-right">
            {!! $array_data['text'] !!}
        </div>
        <div class="chat-bubble-date-right">
            {{ $array_data['date'] }}
        </div>
    </div>
</x-dynamic-component>
