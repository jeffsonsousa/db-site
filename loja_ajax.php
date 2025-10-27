<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Loja com AJAX</title>
    <style>
        body { font-family: sans-serif; max-width: 700px; margin: 40px auto; }
        h1 { margin-bottom: 0.5rem; }
        .painel {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 24px;
        }
        label { display: block; margin-bottom: 10px; }
        input[type=text], input[type=number] {
            padding: 6px;
            width: 100%;
            max-width: 300px;
        }
        button {
            padding: 8px 16px;
            cursor: pointer;
        }
        ul { padding-left: 20px; line-height: 1.6; }
        .msg { font-size: 0.9rem; margin-top: 10px; }
        .msg.ok { color: green; }
        .msg.err { color: red; }
        small { color: #666; }
        hr { margin: 24px 0; }
        a { color:#0066cc; text-decoration:none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <h1>Loja (AJAX + PHP + MySQL)</h1>
    <small>Versão dinâmica sem recarregar a página</small>

    <div class="painel">
        <h2>Produtos cadastrados</h2>
        <button id="btnCarregar">Atualizar lista</button>
        <ul id="lista"></ul>
        <div id="msgLista" class="msg"></div>
    </div>

    <div class="painel">
        <h2>Novo produto</h2>
        <label>
            Nome:
            <input type="text" id="nome" placeholder="Mouse Gamer">
        </label>

        <label>
            Preço:
            <input type="number" id="preco" step="0.01" placeholder="199.90">
        </label>

        <button id="btnAdicionar">Adicionar</button>
        <div id="msgForm" class="msg"></div>
    </div>

    <p><a href="index.php">&larr; Versão simples (sem AJAX)</a></p>

<script>
// Só roda depois que o HTML existir
document.addEventListener('DOMContentLoaded', () => {
    const listaEl      = document.getElementById('lista');
    const msgListaEl   = document.getElementById('msgLista');
    const msgFormEl    = document.getElementById('msgForm');
    const btnCarregar  = document.getElementById('btnCarregar');
    const btnAdicionar = document.getElementById('btnAdicionar');
    const nomeInput    = document.getElementById('nome');
    const precoInput   = document.getElementById('preco');

    // Função para buscar produtos do servidor e mostrar na UL
    async function carregarProdutos() {
        msgListaEl.textContent = "Carregando...";
        msgListaEl.className = "msg";

        try {
            const resp = await fetch('api_listar.php');
            if (!resp.ok) {
                msgListaEl.textContent = "Erro ao carregar lista.";
                msgListaEl.className = "msg err";
                return;
            }

            const data = await resp.json();
            if (!data.ok) {
                msgListaEl.textContent = "Erro: " + (data.erro || "desconhecido");
                msgListaEl.className = "msg err";
                return;
            }

            // Monta o HTML da lista
            listaEl.innerHTML = data.dados.map(p => {
                return `<li>
                    #${p.id} <strong>${p.nome}</strong> — R$ ${Number(p.preco).toFixed(2)}
                </li>`;
            }).join('');

            msgListaEl.textContent = "Lista atualizada.";
            msgListaEl.className = "msg ok";

        } catch (erro) {
            console.error(erro);
            msgListaEl.textContent = "Falha geral ao buscar dados.";
            msgListaEl.className = "msg err";
        }
    }

    // Função para enviar um produto novo pro servidor
    async function adicionarProduto() {
        msgFormEl.textContent = "";
        msgFormEl.className = "msg";

        const nome  = nomeInput.value.trim();
        const preco = parseFloat(precoInput.value);

        if (!nome || !preco) {
            msgFormEl.textContent = "Preencha nome e preço válidos.";
            msgFormEl.className = "msg err";
            return;
        }

        try {
            const resp = await fetch('api_criar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',   // estamos enviando JSON
                    'Accept': 'application/json'          // queremos JSON de volta
                },
                body: JSON.stringify({ nome, preco })
            });

            const data = await resp.json();

            if (!data.ok) {
                msgFormEl.textContent = "Erro ao salvar: " + (data.erro || "desconhecido");
                msgFormEl.className = "msg err";
                return;
            }

            // Sucesso!
            msgFormEl.textContent = "Produto cadastrado com sucesso! (ID " + data.id + ")";
            msgFormEl.className = "msg ok";

            // limpa campos
            nomeInput.value = '';
            precoInput.value = '';

            // atualiza a lista automaticamente
            carregarProdutos();

        } catch (erro) {
            console.error(erro);
            msgFormEl.textContent = "Falha geral ao cadastrar.";
            msgFormEl.className = "msg err";
        }
    }

    // Eventos dos botões
    btnCarregar.onclick  = carregarProdutos;
    btnAdicionar.onclick = adicionarProduto;

    // Carrega a lista assim que a página abre
    carregarProdutos();
});
</script>
</body>
</html>
