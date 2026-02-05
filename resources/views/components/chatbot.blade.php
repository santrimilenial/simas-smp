<!-- Chatbot Widget -->
<div id="chatbot-container" class="fixed bottom-6 right-6 z-50">
    <!-- Chat Button -->
    <button id="chatbot-toggle" 
            class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full w-16 h-16 shadow-2xl hover:shadow-3xl hover:scale-110 transition-all duration-300 flex items-center justify-center">
        <svg id="chat-icon" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 9V7c0-1.1-.9-2-2-2h-3c0-1.66-1.34-3-3-3S9 3.34 9 5H6c-1.1 0-2 .9-2 2v2c-1.66 0-3 1.34-3 3s1.34 3 3 3v4c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4c1.66 0 3-1.34 3-3s-1.34-3-3-3zM7.5 11.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5S9.83 13 9 13s-1.5-.67-1.5-1.5zM16 17H8v-2h8v2zm-.5-4c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
        </svg>
        <svg id="close-icon" class="w-8 h-8 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Chat Window -->
    <div id="chatbot-window" 
         class="hidden absolute bottom-20 right-0 w-96 h-[600px] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-200">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 9V7c0-1.1-.9-2-2-2h-3c0-1.66-1.34-3-3-3S9 3.34 9 5H6c-1.1 0-2 .9-2 2v2c-1.66 0-3 1.34-3 3s1.34 3 3 3v4c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4c1.66 0 3-1.34 3-3s-1.34-3-3-3zM7.5 11.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5S9.83 13 9 13s-1.5-.67-1.5-1.5zM16 17H8v-2h8v2zm-.5-4c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Asisten SIMAS</h3>
                    <p class="text-xs opacity-90">Sistem Informasi Manajemen Sekolah</p>
                </div>
            </div>
            <button id="clear-chat" class="hover:bg-white/20 p-2 rounded-lg transition" title="Hapus riwayat chat">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>

        <!-- Chat Messages -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-2">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 9V7c0-1.1-.9-2-2-2h-3c0-1.66-1.34-3-3-3S9 3.34 9 5H6c-1.1 0-2 .9-2 2v2c-1.66 0-3 1.34-3 3s1.34 3 3 3v4c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4c1.66 0 3-1.34 3-3s-1.34-3-3-3zM7.5 11.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5S9.83 13 9 13s-1.5-.67-1.5-1.5zM16 17H8v-2h8v2zm-.5-4c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                    </svg>
                </div>
                <div class="bg-white rounded-2xl rounded-tl-none p-3 shadow-sm max-w-[75%]">
                    <p class="text-gray-800 text-sm">Halo! 👋 Saya Asisten AI untuk <strong>SIMAS (Sistem Informasi Manajemen Sekolah)</strong>. Saya bisa membantu Anda dengan:</p>
                    <ul class="text-sm text-gray-700 mt-2 space-y-1 list-disc list-inside">
                        <li>Pertanyaan tentang jurnal mengajar & absensi</li>
                        <li>Statistik dan laporan sekolah</li>
                        <li><strong>Materi pelajaran</strong> (Matematika, Sains, Bahasa, dll)</li>
                        <li>Tips & ide untuk mengajar</li>
                    </ul>
                    <p class="text-gray-800 text-sm mt-2">Silakan tanyakan apa saja! 😊</p>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="typing-indicator" class="hidden px-4 py-2">
            <div class="flex items-start space-x-2">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 9V7c0-1.1-.9-2-2-2h-3c0-1.66-1.34-3-3-3S9 3.34 9 5H6c-1.1 0-2 .9-2 2v2c-1.66 0-3 1.34-3 3s1.34 3 3 3v4c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4c1.66 0 3-1.34 3-3s-1.34-3-3-3zM7.5 11.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5S9.83 13 9 13s-1.5-.67-1.5-1.5zM16 17H8v-2h8v2zm-.5-4c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                    </svg>
                </div>
                <div class="bg-white rounded-2xl rounded-tl-none p-3 shadow-sm">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-gray-200">
            <form id="chat-form" class="flex items-center space-x-2">
                <input type="text" 
                       id="chat-input" 
                       placeholder="Ketik pesan Anda..." 
                       class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       autocomplete="off"
                       maxlength="1000">
                <button type="submit" 
                        id="send-button"
                        class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full p-2 hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
            <p class="text-xs text-gray-500 mt-2 text-center">Didukung oleh Google Gemini AI ✨</p>
        </div>
    </div>
</div>

<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    #chatbot-window.show {
        animation: slideIn 0.3s ease-out;
    }

    /* Custom scrollbar */
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    #chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #chat-messages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    #chat-messages::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
    // Toggle chatbot
    const chatToggle = document.getElementById('chatbot-toggle');
    const chatWindow = document.getElementById('chatbot-window');
    const chatIcon = document.getElementById('chat-icon');
    const closeIcon = document.getElementById('close-icon');
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const typingIndicator = document.getElementById('typing-indicator');
    const clearChatBtn = document.getElementById('clear-chat');

    // Toggle chat window
    chatToggle.addEventListener('click', () => {
        const isHidden = chatWindow.classList.contains('hidden');
        
        if (isHidden) {
            chatWindow.classList.remove('hidden');
            chatWindow.classList.add('show');
            chatIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            chatInput.focus();
        } else {
            chatWindow.classList.add('hidden');
            chatWindow.classList.remove('show');
            chatIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });

    // Clear chat history
    clearChatBtn.addEventListener('click', () => {
        if (confirm('Hapus semua riwayat percakapan?')) {
            // Keep only welcome message
            const welcomeMessage = chatMessages.querySelector('.flex.items-start');
            chatMessages.innerHTML = '';
            chatMessages.appendChild(welcomeMessage.cloneNode(true));
        }
    });

    // Send message
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        if (!message) return;

        // Add user message
        addMessage(message, 'user');
        chatInput.value = '';
        sendButton.disabled = true;
        chatInput.disabled = true;

        // Show typing indicator
        typingIndicator.classList.remove('hidden');
        scrollToBottom();

        try {
            // Send to API
            const response = await fetch('{{ route('chat') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();

            // Hide typing indicator
            typingIndicator.classList.add('hidden');

            if (data.success) {
                addMessage(data.message, 'ai');
            } else {
                addMessage(data.error || 'Maaf, terjadi kesalahan. Silakan coba lagi.', 'error');
            }
        } catch (error) {
            typingIndicator.classList.add('hidden');
            addMessage('Maaf, tidak dapat terhubung ke server. Silakan coba lagi.', 'error');
        }

        sendButton.disabled = false;
        chatInput.disabled = false;
        chatInput.focus();
    });

    // Add message to chat
    function addMessage(text, type) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('flex', 'items-start', 'space-x-2');

        if (type === 'user') {
            messageDiv.classList.add('justify-end');
            messageDiv.innerHTML = `
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl rounded-tr-none p-3 shadow-sm max-w-[75%]">
                    <p class="text-sm">${escapeHtml(text)}</p>
                </div>
            `;
        } else if (type === 'error') {
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-2xl rounded-tl-none p-3 shadow-sm max-w-[75%]">
                    <p class="text-red-800 text-sm">${escapeHtml(text)}</p>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 9V7c0-1.1-.9-2-2-2h-3c0-1.66-1.34-3-3-3S9 3.34 9 5H6c-1.1 0-2 .9-2 2v2c-1.66 0-3 1.34-3 3s1.34 3 3 3v4c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4c1.66 0 3-1.34 3-3s-1.34-3-3-3zM7.5 11.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5S9.83 13 9 13s-1.5-.67-1.5-1.5zM16 17H8v-2h8v2zm-.5-4c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                    </svg>
                </div>
                <div class="bg-white rounded-2xl rounded-tl-none p-3 shadow-sm max-w-[75%]">
                    <p class="text-gray-800 text-sm whitespace-pre-wrap">${formatAIResponse(text)}</p>
                </div>
            `;
        }

        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    // Format AI response with basic markdown support
    function formatAIResponse(text) {
        // Convert basic markdown to HTML
        text = escapeHtml(text);
        
        // Bold: **text** or __text__
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        text = text.replace(/__(.*?)__/g, '<strong>$1</strong>');
        
        // Italic: *text* or _text_
        text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
        text = text.replace(/_(.*?)_/g, '<em>$1</em>');
        
        // Lists with proper indentation
        text = text.replace(/^- (.+)$/gm, '• $1');
        text = text.replace(/^\* (.+)$/gm, '• $1');
        text = text.replace(/^\d+\. (.+)$/gm, '<span class="font-semibold">$&</span>');
        
        return text;
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Scroll to bottom
    function scrollToBottom() {
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }

    // Auto-focus on input when opening
    chatToggle.addEventListener('click', () => {
        setTimeout(() => {
            if (!chatWindow.classList.contains('hidden')) {
                chatInput.focus();
            }
        }, 100);
    });
</script>
