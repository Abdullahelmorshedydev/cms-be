{{-- Toastr notifications will be handled via JavaScript --}}
@if (session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('message')['status'] == true)
                if (typeof toastr !== 'undefined') {
                    toastr.success(
                        @if (is_array(session('message')['content']))
                            @php
                                $messageText = '';
                                foreach (session('message')['content'] as $key => $messages) {
                                    foreach ($messages as $message) {
                                        $messageText .= $key . ' => ' . $message . "\n";
                                    }
                                }
                            @endphp
                            {!! json_encode(trim($messageText)) !!}
                        @else
                            {!! json_encode(session('message')['content']) !!}
                        @endif , 'Success'
                    );
                }
            @else
                if (typeof toastr !== 'undefined') {
                    toastr.error(
                        @if (is_array(session('message')['content']))
                            @php
                                $messageText = '';
                                foreach (session('message')['content'] as $key => $messages) {
                                    foreach ($messages as $message) {
                                        $messageText .= $key . ' => ' . $message . "\n";
                                    }
                                }
                            @endphp
                            {!! json_encode(trim($messageText)) !!}
                        @else
                            {!! json_encode(session('message')['content']) !!}
                        @endif , 'Error'
                    );
                }
            @endif
        });
    </script>
@endif
