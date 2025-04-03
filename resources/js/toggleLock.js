document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-lock").forEach(button => {
        button.addEventListener("click", function () {
            const docId = this.getAttribute("data-id");
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            const btn = this; // Referência ao botão clicado

            fetch(`/documents/${docId}/toggle-lock`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualiza o ícone e o texto
                    btn.innerHTML = `<i class="bi ${data.locked ? 'bi-lock-fill' : 'bi-unlock-fill'}"></i> ${data.locked ? 'Bloqueado' : 'Desbloqueado'}`;
            
                    // 🔹 Remove todas as possíveis classes para evitar conflito
                    btn.classList.remove("btn-warning", "btn-secondary");
            
                    // 🔹 Adiciona a classe correta
                    if (data.locked) {
                        btn.classList.add("btn-warning"); // Amarelo se bloqueado
                    } else {
                        btn.classList.add("btn-secondary"); // Cinza se desbloqueado
                    }
                } else {
                    console.error("Erro na resposta do servidor:", data);
                }
            })
            .catch(error => console.error("Erro ao bloquear/desbloquear:", error));
            
            
        });
    });
});
