document.addEventListener("DOMContentLoaded", function () {
    function reloadTable() {
        fetch("/documents") // Endpoint da listagem
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, "text/html");
                const newTable = newDoc.querySelector("table tbody").innerHTML; // Obtém a nova tabela
                document.querySelector("table tbody").innerHTML = newTable; // Substitui a tabela
                attachEventListeners(); // Reanexa os eventos aos botões da nova tabela
            })
            .catch(error => console.error("Erro ao recarregar a tabela:", error));
    }

    function attachEventListeners() {
        document.querySelectorAll(".toggle-lock").forEach(button => {
            button.addEventListener("click", function () {
                const docId = this.getAttribute("data-id");
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

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
                        reloadTable(); // Recarrega a tabela após alterar status
                    } else {
                        console.error("Erro na resposta do servidor:", data);
                    }
                })
                .catch(error => console.error("Erro ao Ativar/Inativar:", error));
            });
        });
    }

    attachEventListeners(); // Inicializa os eventos ao carregar a página
});
