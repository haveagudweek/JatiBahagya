@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>

        <div class="row">
            <!-- Sidebar: List Produk -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold">
                        Produk Chat
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach ($products as $product)
                            <a href="#"
                                class="list-group-item list-group-item-action d-flex align-items-center product-item"
                                data-product-id="{{ $product->id }}">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    width="40" height="40" class="rounded me-2">
                                <div class="text-truncate">
                                    <strong>{{ $product->name }}</strong>
                                    <div class="small text-muted">Klik untuk konsultasi</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Panel Chat -->
            <div class="col-md-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light d-flex align-items-center">
                        <img src="{{ asset('images/asisstant.png') }}" alt="Bahagya Assistant" width="40" height="40"
                            class="rounded-circle me-2">
                        <div>
                            <h6 class="mb-0 fw-bold">Jati Bahagya Assistant</h6>
                            <small class="text-muted">Online</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chat-container" id="chat-container">
                            <!-- Greeting atau isi chat akan muncul di sini -->
                        </div>

                        <div class="mt-3">
                            <form id="message-form" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <label for="chat-attachment" class="btn btn-outline-secondary">
                                        <i class="bi bi-paperclip"></i>
                                    </label>
                                    <input type="file" id="chat-attachment" name="attachment" accept="image/*"
                                        style="display: none;">

                                    <input type="text" class="form-control" placeholder="Ketik pesan Anda..."
                                        name="message" id="message-input" required>

                                    <button class="btn btn-primary" type="submit">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .chat-container {
            height: 400px;
            overflow-y: scroll;
            padding: 10px;
        }

        .message {
            margin-bottom: 10px;
            clear: both;
            /* Important for proper float clearing */
        }

        .message-bubble {
            max-width: 70%;
            padding: 12px 20px;
            border-radius: 15px;
            font-size: 16px;
            line-height: 1.4;
            clear: both;
            /* Ensure each bubble clears floats */
        }

        .message-bubble.user {
            background-color: #e2f7cb;
            /* Light green for user */
            float: right;
            /* Float user bubbles to the right */
            text-align: right;
        }

        .message-bubble.bot {
            background-color: #e7e3e3;
            /* White for bot */
            float: left;
            /* Float bot bubbles to the left */
            text-align: left;
        }

        /* Optional: Add some margin to the left of the bot's bubbles */
        .message-bubble.bot {
            margin-left: 10px;
        }

        /* Optional: Add some margin to the right of the user's bubbles */
        .message-bubble.user {
            margin-right: 10px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            const greeting =
                "Halo, selamat datang di Jati Bahagya!<br>Saya Bahagya Assistant siap membantu Anda.<br><br>Dengan melanjutkan percakapan ini, Anda menyetujui proses pengumpulan dan pemrosesan data pribadi Anda yang berlaku sesuai dengan kebijakan privasi kami. Tujuan pengumpulan data ini adalah untuk bagaimana diatur dalam Kebijakan Privasi kami di sini: <br><br><a href='#'>Syarat & Ketentuan JatiBahagya</a>";
            appendMessage(greeting, 'bot');

            $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));

            // Tambahkan tombol balasan cepat
            $("#chat-container").append(`
                <div class="message py-1">
                    <div class="container bot my-4">
                        <button class="btn btn-sm btn-success me-2 quick-reply" data-reply="Ya, saya setuju.">✅ Ya, Setuju</button>
                        <button class="btn btn-sm btn-outline-secondary quick-reply" data-reply="Tidak, terima kasih.">❌ Tidak, Terima Kasih</button>
                    </div>
                </div>
            `);

            $(document).on('click', '.quick-reply', function() {
                const userReply = $(this).data('reply');
                appendMessage(userReply, 'user');

                // Hapus tombol setelah diklik
                $(this).closest('.message-bubble').remove();

                // Bot balas setelah pilih
                setTimeout(function() {
                    const botReply =
                        "Terima kasih atas persetujuannya. Bagaimana saya bisa membantu Anda hari ini?";
                    appendMessage(botReply, 'bot');
                    $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));
                }, 500);
            });

            $("#message-form").submit(function(event) {
                event.preventDefault();

                var message = $("#message-input").val();

                if (message.trim() !== "") {
                    appendMessage(message, 'user');
                    $("#message-input").val("");

                    setTimeout(function() {
                        var botReply = generateBotReply(message);
                        appendMessage(botReply, 'bot');

                        $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));
                    }, 500);

                    $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));

                }
            });
        });

        function appendMessage(message, sender) {
            $("#chat-container").append(`
                <div class="message">
                    <div class="message-bubble ${sender}">${message}</div>
                </div>
            `);
        }

        function generateBotReply(userMessage) {
            var responses = [
                "Tentu, ada yang bisa dibantu?",
                "Silakan ajukan pertanyaan Anda.",
                "Produk ini tersedia dalam berbagai warna.",
                "Terima kasih atas pesan Anda.",
                "Maaf, saya tidak mengerti pertanyaan Anda."
            ];
            var randomIndex = Math.floor(Math.random() * responses.length);
            return responses[randomIndex];
        }

        $(document).ready(function() {
            $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));
        });
    </script>
@endsection
