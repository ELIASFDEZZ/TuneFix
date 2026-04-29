<!-- ================================================================
     TUNEBOT – Chatbot flotante
     Incluir en views/layouts/footer.php justo antes de </body>
     ================================================================ -->

<style>
  /* ── Botón flotante ── */
  #chatbot-btn {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgb(164,4,46), #ff3c00);
    color: #fff;
    border: none;
    font-size: 1.4rem;
    box-shadow: 0 6px 20px rgba(164,4,46,0.5);
    cursor: pointer;
    z-index: 9998;
    transition: transform .2s, box-shadow .2s;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  #chatbot-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 10px 28px rgba(164,4,46,0.6);
  }
  #chatbot-btn .badge-notif {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ffc107;
    color: #000;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: .65rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* ── Ventana del chat ── */
  #chatbot-window {
    position: fixed;
    bottom: 100px;
    right: 28px;
    width: 360px;
    max-height: 520px;
    background: #1a1a2e;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    display: flex;
    flex-direction: column;
    z-index: 9999;
    overflow: hidden;
    transform: scale(0.8) translateY(20px);
    opacity: 0;
    pointer-events: none;
    transition: all .25s cubic-bezier(.34,1.56,.64,1);
  }
  #chatbot-window.open {
    transform: scale(1) translateY(0);
    opacity: 1;
    pointer-events: all;
  }

  /* ── Header ── */
  .cb-header {
    background: linear-gradient(135deg, rgb(164,4,46), #ff3c00);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .cb-avatar {
    width: 36px; height: 36px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
  }
  .cb-header-info .cb-name  { color: #fff; font-weight: 700; font-size: .95rem; }
  .cb-header-info .cb-status { color: rgba(255,255,255,0.75); font-size: .72rem; }
  .cb-close {
    margin-left: auto;
    background: none;
    border: none;
    color: rgba(255,255,255,0.8);
    font-size: 1.1rem;
    cursor: pointer;
    padding: 4px;
    line-height: 1;
  }
  .cb-close:hover { color: #fff; }

  /* ── Mensajes ── */
  .cb-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.1) transparent;
  }
  .cb-msg {
    max-width: 82%;
    padding: 10px 14px;
    border-radius: 16px;
    font-size: .85rem;
    line-height: 1.5;
    word-break: break-word;
  }
  .cb-msg.bot {
    background: rgba(255,255,255,0.08);
    color: #e2e8f0;
    border-bottom-left-radius: 4px;
    align-self: flex-start;
  }
  .cb-msg.user {
    background: linear-gradient(135deg, rgb(164,4,46), #ff3c00);
    color: #fff;
    border-bottom-right-radius: 4px;
    align-self: flex-end;
  }
  .cb-msg.typing {
    background: rgba(255,255,255,0.06);
    color: #aaa;
    font-style: italic;
    align-self: flex-start;
  }

  /* ── Sugerencias rápidas ── */
  .cb-suggestions {
    padding: 0 16px 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
  }
  .cb-chip {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    color: #cbd5e0;
    border-radius: 50px;
    padding: 4px 12px;
    font-size: .75rem;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
  }
  .cb-chip:hover {
    background: rgba(164,4,46,0.3);
    border-color: rgba(164,4,46,0.5);
    color: #fff;
  }

  /* ── Input ── */
  .cb-input-area {
    padding: 12px 16px;
    border-top: 1px solid rgba(255,255,255,0.07);
    display: flex;
    gap: 8px;
    align-items: flex-end;
  }
  #cb-input {
    flex: 1;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 12px;
    color: #fff;
    padding: 9px 14px;
    font-size: .85rem;
    resize: none;
    outline: none;
    max-height: 100px;
    overflow-y: auto;
    transition: border-color .2s;
  }
  #cb-input:focus { border-color: rgba(164,4,46,0.6); }
  #cb-input::placeholder { color: rgba(255,255,255,0.3); }
  #cb-send {
    width: 38px; height: 38px;
    background: linear-gradient(135deg, rgb(164,4,46), #ff3c00);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: .95rem;
    cursor: pointer;
    transition: transform .2s;
    flex-shrink: 0;
  }
  #cb-send:hover { transform: scale(1.08); }
  #cb-send:disabled { opacity: .5; cursor: not-allowed; transform: none; }

  @media (max-width: 420px) {
    #chatbot-window { width: calc(100vw - 20px); right: 10px; bottom: 90px; }
    #chatbot-btn { bottom: 16px; right: 16px; }
  }
</style>

<!-- Botón flotante -->
<button id="chatbot-btn" onclick="toggleChat()" title="TuneBot - Asistente TuneFix">
  <i class="fas fa-robot" id="cb-icon"></i>
  <span class="badge-notif" id="cb-notif">1</span>
</button>

<!-- Ventana del chat -->
<div id="chatbot-window">

  <!-- Header -->
  <div class="cb-header">
    <div class="cb-avatar"><i class="fas fa-robot"></i></div>
    <div class="cb-header-info">
      <div class="cb-name">TuneBot</div>
      <div class="cb-status"><span style="color:#4ade80;">●</span> En línea · Asistente TuneFix</div>
    </div>
    <button class="cb-close" onclick="toggleChat()"><i class="fas fa-times"></i></button>
  </div>

  <!-- Mensajes -->
  <div class="cb-messages" id="cb-messages"></div>

  <!-- Sugerencias rápidas -->
  <div class="cb-suggestions" id="cb-suggestions">
    <span class="cb-chip" onclick="enviarSugerencia('¿Cómo encuentro piezas compatibles con mi coche?')">🔧 Piezas compatibles</span>
    <span class="cb-chip" onclick="enviarSugerencia('¿Cuál es la diferencia entre los modos Principiante, Entusiasta y Profesional?')">📚 Modos de uso</span>
    <span class="cb-chip" onclick="enviarSugerencia('¿Cómo puedo ver los distribuidores de una pieza?')">🏪 Distribuidores</span>
    <span class="cb-chip" onclick="enviarSugerencia('¿Qué son los manuales técnicos?')">📄 Manuales</span>
  </div>

  <!-- Input -->
  <div class="cb-input-area">
    <textarea id="cb-input" rows="1" placeholder="Escribe tu pregunta…"
              onkeydown="cbKeyDown(event)" oninput="cbAutoResize(this)"></textarea>
    <button id="cb-send" onclick="enviarMensaje()"><i class="fas fa-paper-plane"></i></button>
  </div>

</div>

<script>
// ── Estado ────────────────────────────────────────────────────────────────────
let cbAbierto   = false;
let cbEsperando = false;
let cbHistorial = []; // [{role:'user'|'assistant', content:'...'}]
let cbIniciado  = false;

// ── Toggle ────────────────────────────────────────────────────────────────────
function toggleChat() {
  cbAbierto = !cbAbierto;
  const win  = document.getElementById('chatbot-window');
  const icon = document.getElementById('cb-icon');
  const notif = document.getElementById('cb-notif');

  win.classList.toggle('open', cbAbierto);
  icon.className = cbAbierto ? 'fas fa-times' : 'fas fa-robot';
  notif.style.display = 'none';

  if (cbAbierto && !cbIniciado) {
    cbIniciado = true;
    agregarMensaje('bot', '¡Hola! 👋 Soy <strong>TuneBot</strong>, tu asistente de TuneFix. Puedo ayudarte con piezas, compatibilidades, tutoriales y cómo usar la plataforma. ¿En qué te ayudo?');
  }

  if (cbAbierto) {
    setTimeout(() => document.getElementById('cb-input').focus(), 300);
  }
}

// ── Agregar mensaje al chat ───────────────────────────────────────────────────
function agregarMensaje(tipo, texto, id) {
  const container = document.getElementById('cb-messages');
  const div = document.createElement('div');
  div.className = 'cb-msg ' + tipo;
  if (id) div.id = id;
  div.innerHTML = texto.replace(/\n/g, '<br>');
  container.appendChild(div);
  container.scrollTop = container.scrollHeight;
  return div;
}

// ── Enviar mensaje ────────────────────────────────────────────────────────────
async function enviarMensaje() {
  const input = document.getElementById('cb-input');
  const texto = input.value.trim();
  if (!texto || cbEsperando) return;

  // Ocultar sugerencias tras primer mensaje
  document.getElementById('cb-suggestions').style.display = 'none';

  input.value = '';
  cbAutoResize(input);
  agregarMensaje('user', escHtml(texto));
  cbHistorial.push({ role: 'user', content: texto });

  cbEsperando = true;
  document.getElementById('cb-send').disabled = true;

  // Indicador de escritura
  const typingId = 'typing-' + Date.now();
  agregarMensaje('typing', '<i class="fas fa-circle-notch fa-spin me-1"></i> TuneBot está escribiendo…', typingId);

  try {
    const res = await fetch('/TunefixMVC/public/ajax/chatbot.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ mensaje: texto, historial: cbHistorial.slice(-10) }),
    });
    const data = await res.json();

    // Eliminar typing
    document.getElementById(typingId)?.remove();

    if (data.error) {
      agregarMensaje('bot', '⚠️ ' + escHtml(data.error));
    } else {
      agregarMensaje('bot', escHtml(data.respuesta));
      cbHistorial.push({ role: 'assistant', content: data.respuesta });
    }
  } catch (e) {
    document.getElementById(typingId)?.remove();
    agregarMensaje('bot', '⚠️ Error de conexión. Inténtalo de nuevo.');
  }

  cbEsperando = false;
  document.getElementById('cb-send').disabled = false;
  document.getElementById('cb-input').focus();
}

function enviarSugerencia(texto) {
  document.getElementById('cb-input').value = texto;
  enviarMensaje();
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function cbKeyDown(e) {
  if (e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault();
    enviarMensaje();
  }
}

function cbAutoResize(el) {
  el.style.height = 'auto';
  el.style.height = Math.min(el.scrollHeight, 100) + 'px';
}

function escHtml(str) {
  return String(str)
    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
    .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>