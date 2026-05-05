<!doctype html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Ghina Tour Travel — Serving With Love')</title>
<meta name="description" content="@yield('description', 'PT Ghina Tour Travel — solusi perjalanan wisata rombongan dengan harga sesuai anggaran Anda. Terpercaya, Fleksibel & Fun.')" />

@vite(['resources/css/app.css', 'resources/css/customer.css', 'resources/js/app.js'])
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

@yield('extra_styles')
</head>
<body>

@include('components.layout.navbar')

@yield('content')

@include('components.layout.footer')

<a href="https://wa.me/6281234567890" target="_blank" class="wa-float">
  <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" class="h-14 w-14 rounded-full shadow-lg hover:scale-110 transition-transform" />
</a>

{{-- ── Chatbot Widget ── --}}
<style>
    .cb-container {
        position: fixed; bottom: 154px; right: 26px; width: 370px; height: 520px;
        background: #fff; border: 1px solid #e5e5e5; border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12); display: flex; flex-direction: column;
        overflow: hidden; z-index: 1000; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(20px) scale(0.95); opacity: 0; pointer-events: none;
    }
    .cb-container.active { transform: translateY(0) scale(1); opacity: 1; pointer-events: auto; }
    .cb-header { background: var(--gold); padding: 15px 20px; display: flex; align-items: center; justify-content: space-between; }
    .cb-header h3 { color: #000; font-weight: 700; font-size: 16px; margin: 0; }
    .cb-close { cursor: pointer; color: #000; display: flex; }
    .cb-messages { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 12px; background: #fafaf9; }
    .cb-msg { max-width: 85%; padding: 10px 14px; border-radius: 15px; font-size: 14px; line-height: 1.6; word-wrap: break-word; }
    .cb-bot { align-self: flex-start; background: #fff; color: #333; border: 1px solid #e5e5e5; border-bottom-left-radius: 2px; }
    .cb-user { align-self: flex-end; background: var(--gold); color: #000; border-bottom-right-radius: 2px; }
    .cb-input-area { padding: 15px; border-top: 1px solid #e5e5e5; display: flex; gap: 8px; background: #fff; }
    .cb-input { flex: 1; background: #f5f5f4; border: 1px solid #e5e5e5; border-radius: 999px; padding: 8px 16px; font-size: 14px; color: #333; outline: none; }
    .cb-input:focus { border-color: var(--gold); }
    .cb-send { background: var(--gold); color: #000; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; flex-shrink: 0; transition: background 0.2s; }
    .cb-send:hover { background: var(--gold-dark); }
    .cb-trigger { position: fixed; bottom: 90px; right: 26px; background: var(--gold); color: #000; width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 14px rgba(212,160,23,0.35); z-index: 999; transition: transform 0.2s; border: none; }
    .cb-trigger:hover { transform: scale(1.1); }
    .cb-quick { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .cb-qbtn { font-size: 12px; padding: 4px 12px; border: 1px solid var(--gold); border-radius: 999px; color: var(--gold-dark); background: transparent; cursor: pointer; transition: all 0.2s; }
    .cb-qbtn:hover { background: var(--gold); color: #000; }
    .cb-typing { display: flex; gap: 4px; }
    .cb-typing span { width: 6px; height: 6px; background: #a8a29e; border-radius: 50%; animation: cbBounce 1.4s infinite ease-in-out both; }
    .cb-typing span:nth-child(1) { animation-delay: -0.32s; }
    .cb-typing span:nth-child(2) { animation-delay: -0.16s; }
    @keyframes cbBounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
    
    [data-theme="dark"] .cb-container { background: #1a1a1a; border-color: #333; }
    [data-theme="dark"] .cb-messages { background: #141414; }
    [data-theme="dark"] .cb-bot { background: #262626; color: #fff; border-color: #333; }
    [data-theme="dark"] .cb-input-area { background: #1a1a1a; border-color: #333; }
    [data-theme="dark"] .cb-input { background: #262626; border-color: #333; color: #fff; }
</style>

<button id="cbTrigger" class="cb-trigger" title="Ghina Assistant">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
</button>

<div id="cbContainer" class="cb-container">
    <div class="cb-header">
        <h3>Ghina Assistant</h3>
        <span id="cbClose" class="cb-close">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </span>
    </div>
    <div id="cbMessages" class="cb-messages"></div>
    <div class="cb-input-area">
        <input type="text" id="cbInput" class="cb-input" placeholder="Tulis pesan...">
        <button id="cbSend" class="cb-send">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </div>
</div>

<script>
(function() {
    const trigger = document.getElementById('cbTrigger');
    const container = document.getElementById('cbContainer');
    const closeBtn = document.getElementById('cbClose');
    const input = document.getElementById('cbInput');
    const sendBtn = document.getElementById('cbSend');
    const messages = document.getElementById('cbMessages');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!trigger) return;

    trigger.addEventListener('click', () => {
        container.classList.toggle('active');
        if (container.classList.contains('active') && messages.children.length === 0) {
            fetchMenu();
        }
        input.focus();
    });

    closeBtn.addEventListener('click', () => container.classList.remove('active'));

    async function sendMessage(text) {
        if (!text.trim()) return;
        appendMsg(text, 'user');
        input.value = '';
        showTyping();

        try {
            const res = await fetch('/api/chatbot/message', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ message: text })
            });
            const data = await res.json();
            hideTyping();
            appendMsg(data.response || 'Tidak ada respons.', 'bot');
        } catch (err) {
            hideTyping();
            appendMsg('Terjadi kesalahan koneksi.', 'bot');
        }
    }

    sendBtn.addEventListener('click', () => sendMessage(input.value));
    input.addEventListener('keypress', (e) => { if (e.key === 'Enter') sendMessage(input.value); });

    async function fetchMenu() {
        showTyping();
        try {
            const res = await fetch('/api/chatbot/menu');
            const data = await res.json();
            hideTyping();
            if (data.success) appendMsg(data.response, 'bot', data.options);
        } catch (err) {
            hideTyping();
            appendMsg('Gagal memuat menu.', 'bot');
        }
    }

    function appendMsg(text, side, options = []) {
        const div = document.createElement('div');
        div.className = `cb-msg cb-${side}`;
        let html = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
        div.innerHTML = `<div>${html}</div>`;

        if (options && options.length > 0) {
            const qr = document.createElement('div');
            qr.className = 'cb-quick';
            options.forEach(opt => {
                const btn = document.createElement('button');
                btn.className = 'cb-qbtn';
                btn.textContent = opt;
                btn.onclick = () => sendMessage(opt);
                qr.appendChild(btn);
            });
            div.appendChild(qr);
        }

        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function showTyping() {
        const t = document.createElement('div');
        t.className = 'cb-msg cb-bot'; t.id = 'cbTyping';
        t.innerHTML = '<div class="cb-typing"><span></span><span></span><span></span></div>';
        messages.appendChild(t);
        messages.scrollTop = messages.scrollHeight;
    }

    function hideTyping() {
        const t = document.getElementById('cbTyping');
        if (t) t.remove();
    }
})();
</script>

@include('components.layout.scripts')
@yield('extra_scripts')
</body>
</html>