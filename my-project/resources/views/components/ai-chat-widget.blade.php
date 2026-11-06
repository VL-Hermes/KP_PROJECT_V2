{{-- Floating AI Laptop Recommendation Chat Widget --}}
<div id="ai-chat-widget" class="ai-chat-container position-fixed" style="bottom: 24px; right: 24px; z-index: 1060;">
    {{-- Floating Toggle Trigger Button --}}
    <button id="ai-chat-toggle-btn" 
            type="button" 
            class="btn btn-primary shadow-lg d-flex align-items-center gap-2 rounded-pill px-3 py-2 border-2 border-white"
            aria-label="Tanya AI Rekomendasi Laptop"
            title="Chat dengan AI Advisor untuk Rekomendasi Laptop">
        <span class="ai-sparkle-icon d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle" style="width: 28px; height: 28px;">
            <i class="bi bi-stars fs-6"></i>
        </span>
        <span class="fw-bold small d-none d-sm-inline">Tanya AI Rekomendasi</span>
        <span class="badge bg-warning text-dark fw-bold rounded-pill" style="font-size: 0.65rem;">Gemini</span>
    </button>

    {{-- Expandable Chat Window --}}
    <div id="ai-chat-card" class="card shadow-lg border-0 d-none position-fixed" style="bottom: 84px; right: 24px; width: 380px; max-width: calc(100vw - 32px); height: 530px; max-height: calc(100vh - 100px); z-index: 1065;">
        {{-- Card Header --}}
        <div class="card-header bg-dark text-white p-3 border-0 d-flex justify-content-between align-items-center rounded-top-3">
            <div class="d-flex align-items-center gap-2">
                <div class="position-relative">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-robot fs-5"></i>
                    </div>
                    <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold d-flex align-items-center gap-1">
                        LaptopHub AI Advisor
                    </h6>
                    <small class="text-secondary" style="font-size: 0.72rem;">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>Live Stok Inventory
                    </small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1">
                <button id="ai-chat-clear-btn" type="button" class="btn btn-sm btn-link text-white-50 p-1 text-decoration-none" title="Reset percakapan">
                    <i class="bi bi-arrow-counterclockwise fs-5"></i>
                </button>
                <button id="ai-chat-close-btn" type="button" class="btn-close btn-close-white ms-1" aria-label="Tutup"></button>
            </div>
        </div>

        {{-- Card Body: Chat History & Suggestions --}}
        <div id="ai-chat-body" class="card-body p-3 overflow-y-auto d-flex flex-column gap-3" style="font-size: 0.88rem;">
            {{-- Welcome bubble --}}
            <div class="d-flex gap-2 align-items-start">
                <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 30px; height: 30px;">
                    <i class="bi bi-stars"></i>
                </div>
                <div class="bg-light p-3 rounded-3 text-dark border w-100">
                    <p class="mb-2 fw-semibold">Halo! 👋 Saya asisten AI LaptopHub.</p>
                    <p class="mb-2 text-secondary small">
                        Bingung memilih laptop bekas yang cocok? Beritahu saya kebutuhan Anda (misalnya untuk koding kuliah, desain grafis ringan, kerja kantoran, atau daya tahan baterai).
                    </p>
                    <div class="d-flex flex-column gap-1 mt-2">
                        <span class="text-muted fw-bold" style="font-size: 0.72rem;">CONTOH PERTANYAAN CEPAT:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill py-1 px-2 ai-prompt-chip text-start" style="font-size: 0.75rem;">
                                💻 Laptop untuk koding &amp; kuliah
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill py-1 px-2 ai-prompt-chip text-start" style="font-size: 0.75rem;">
                                🔋 Cari yang baterainya paling awet
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill py-1 px-2 ai-prompt-chip text-start" style="font-size: 0.75rem;">
                                ⚡ Rekomendasi RAM 16GB
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dynamic Messages Rendered by JS --}}
            <div id="ai-messages-container" class="d-flex flex-column gap-3"></div>

            {{-- Typing Indicator Placeholder --}}
            <div id="ai-typing-indicator" class="d-none">
                <div class="d-flex gap-2 align-items-center text-muted small">
                    <div class="spinner-grow spinner-grow-sm text-primary" role="status"></div>
                    <span class="fst-italic">AI sedang menganalisis stok laptop...</span>
                </div>
            </div>
        </div>

        {{-- Card Footer: Input & Send Button --}}
        <div class="card-footer bg-white border-top p-2 rounded-bottom-3">
            <form id="ai-chat-form" class="d-flex gap-2 align-items-center">
                <input type="text" 
                       id="ai-chat-input" 
                       class="form-control form-control-sm border rounded-pill px-3 py-2" 
                       placeholder="Ketik kebutuhan laptop Anda..." 
                       autocomplete="off" 
                       maxlength="500">
                <button type="submit" 
                        id="ai-chat-send-btn" 
                        class="btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                        style="width: 36px; height: 36px;"
                        title="Kirim pesan">
                    <i class="bi bi-send-fill" style="transform: translateX(1px);"></i>
                </button>
            </form>
            <div class="d-flex justify-content-between align-items-center px-2 mt-1">
                <small class="text-muted" style="font-size: 0.68rem;">
                    <i class="bi bi-shield-check text-success me-1"></i>Rekomendasi stok terverifikasi
                </small>
                <small class="text-muted" style="font-size: 0.68rem;">
                    Powered by Gemini AI
                </small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('ai-chat-toggle-btn');
        const chatCard = document.getElementById('ai-chat-card');
        const closeBtn = document.getElementById('ai-chat-close-btn');
        const clearBtn = document.getElementById('ai-chat-clear-btn');
        const chatForm = document.getElementById('ai-chat-form');
        const chatInput = document.getElementById('ai-chat-input');
        const messagesContainer = document.getElementById('ai-messages-container');
        const typingIndicator = document.getElementById('ai-typing-indicator');
        const chatBody = document.getElementById('ai-chat-body');
        const chips = document.querySelectorAll('.ai-prompt-chip');

        const STORAGE_KEY = 'laptophub_ai_chat_history';
        const STATE_KEY = 'laptophub_ai_chat_open';

        let chatHistory = [];

        // Simple Markdown to safe HTML formatter
        function formatMarkdown(text) {
            if (!text) return '';
            
            // Escape HTML entities to prevent XSS
            let safe = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');

            // Headings ###
            safe = safe.replace(/^### (.*$)/gim, '<h6 class="fw-bold mt-2 mb-1 text-primary">$1</h6>');
            safe = safe.replace(/^## (.*$)/gim, '<h6 class="fw-bold mt-2 mb-1 text-primary">$1</h6>');

            // Bold **text**
            safe = safe.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

            // Italic *text*
            safe = safe.replace(/\*(.*?)\*/g, '<em>$1</em>');

            // Links [title](url)
            safe = safe.replace(/\[(.*?)\]\((.*?)\)/g, function(match, label, url) {
                // Keep internal links relative or safe
                const safeUrl = url.trim();
                return `<a href="${safeUrl}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 my-1 py-1 px-2 text-decoration-none"><i class="bi bi-box-arrow-up-right small"></i> ${label}</a>`;
            });

            // Bullet points • or -
            safe = safe.replace(/^\s*[•\-]\s+(.*$)/gim, '<div class="d-flex align-items-start gap-1 mb-1"><i class="bi bi-dot fs-5 text-primary lh-1"></i><div>$1</div></div>');

            // Paragraphs / newlines
            safe = safe.replace(/\n\n+/g, '<br><br>');
            safe = safe.replace(/\n/g, '<br>');

            return safe;
        }

        function loadChat() {
            try {
                const stored = sessionStorage.getItem(STORAGE_KEY);
                if (stored) {
                    chatHistory = JSON.parse(stored);
                    renderMessages();
                }
            } catch (e) {
                chatHistory = [];
            }
        }

        function saveChat() {
            try {
                sessionStorage.setItem(STORAGE_KEY, JSON.stringify(chatHistory));
            } catch (e) {}
        }

        function scrollToBottom() {
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        function renderMessages() {
            messagesContainer.innerHTML = '';
            chatHistory.forEach(msg => {
                const bubble = document.createElement('div');
                if (msg.role === 'user') {
                    bubble.className = 'ai-bubble-user';
                    bubble.textContent = msg.text;
                } else {
                    bubble.className = 'ai-bubble-bot';
                    bubble.innerHTML = formatMarkdown(msg.text);
                }
                messagesContainer.appendChild(bubble);
            });
            scrollToBottom();
        }

        function toggleChat(forceOpen = null) {
            const shouldOpen = forceOpen !== null ? forceOpen : chatCard.classList.contains('d-none');
            if (shouldOpen) {
                chatCard.classList.remove('d-none');
                sessionStorage.setItem(STATE_KEY, 'open');
                setTimeout(() => chatInput.focus(), 150);
                scrollToBottom();
            } else {
                chatCard.classList.add('d-none');
                sessionStorage.setItem(STATE_KEY, 'closed');
            }
        }

        // Initialize state
        loadChat();
        if (sessionStorage.getItem(STATE_KEY) === 'open') {
            toggleChat(true);
        }

        // Event listeners
        toggleBtn.addEventListener('click', () => toggleChat());
        closeBtn.addEventListener('click', () => toggleChat(false));

        clearBtn.addEventListener('click', function () {
            chatHistory = [];
            saveChat();
            renderMessages();
            chatInput.focus();
        });

        // Click on chip prompts
        chips.forEach(chip => {
            chip.addEventListener('click', function () {
                const text = this.innerText.replace(/^[^\w\s]+/g, '').trim();
                sendMessage(text);
            });
        });

        // Form submit
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const text = chatInput.value.trim();
            if (!text) return;
            sendMessage(text);
        });

        async function sendMessage(text) {
            chatInput.value = '';

            // Append user message
            chatHistory.push({ role: 'user', text: text });
            renderMessages();
            saveChat();

            // Show typing indicator
            typingIndicator.classList.remove('d-none');
            scrollToBottom();

            // Get CSRF Token
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch("{{ route('chat.recommend') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message: text,
                        history: chatHistory.slice(-10),
                    }),
                });

                const data = await response.json();

                if (response.ok && data.reply) {
                    chatHistory.push({ role: 'model', text: data.reply });
                } else {
                    chatHistory.push({
                        role: 'model',
                        text: data.message || 'Maaf, terjadi kendala saat memproses permintaan Anda. Silakan coba kembali.'
                    });
                }
            } catch (err) {
                chatHistory.push({
                    role: 'model',
                    text: 'Koneksi terganggu. Silakan periksa jaringan internet Anda dan coba lagi.'
                });
            } finally {
                typingIndicator.classList.add('d-none');
                renderMessages();
                saveChat();
                chatInput.focus();
            }
        }
    });
</script>
@endpush

