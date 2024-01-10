let contador = 0;

function adicionarInput() {
  contador++;

  const novaDiv = document.createElement('div');
  novaDiv.style.display = 'flow-root';

  const novoInput = document.createElement('input');
  novoInput.type = 'text';
  novoInput.name = `resposta_${contador}`;
  novoInput.classList.add('form-control');
  novoInput.classList.add('respostas');

  const botaoRemover = document.createElement('button');
  botaoRemover.textContent = 'Remover';
  botaoRemover.classList.add('btn');
  botaoRemover.classList.add('btn-warning');
  botaoRemover.classList.add('btn-sm');
  botaoRemover.classList.add('btn-remover');
  botaoRemover.onclick = function() {
    novaDiv.remove(); // Remove a div que encapsula o input e o botão
    //contador--; // Reduz o contador quando o input é removido
  };

  novaDiv.appendChild(novoInput);
  novaDiv.appendChild(botaoRemover);

  const container = document.getElementById('inputsContainer');
  container.appendChild(novaDiv);
}


function habilitarBtn() {
    var select = document.getElementById("tipo");
    var textarea = document.getElementById("add");

    if(select.value === "4" || select.value === "5"){
    textarea.style.display = "block"; // Exibe o campo textarea
    textarea.disabled = false; // Habilita o campo textarea
    }else{
        textarea.style.display = "none"; // Esconde o campo textarea
        textarea.disabled = true; // Desabilita o campo textarea
    }
}



$(document).ready(function(){
    // Intercepta o envio dos formulários com a classe 'ajax-form'
    $('.ajax-form').submit(function(event){
        event.preventDefault(); // Impede o envio padrão do formulário
        
        var form = $(this); // Seleciona o formulário atual
        var formData = form.serialize(); // Serializa os dados do formulário
        formData += '&form-edit=form-edit';

        $.ajax({
            type: 'POST', // Pode ser POST ou GET, dependendo da sua necessidade
            url: 'controller/form-controller.php', // Substitua pela sua URL de envio
            data: formData, // Dados a serem enviados, no formato 'chave=valor&chave=valor'
            success: function(response) {
                // Ação a ser executada em caso de sucesso
                console.log(response); // Exemplo: exibe a resposta no console
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Dados alterados com sucesso!",
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(error){
                // Ação a ser executada em caso de erro
                console.error('Erro:', error); // Exibe o erro no console
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Ocorreu um erro ao executar a ação!",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });



    // Intercepta o clique no botão com ID 'Excluir'
    $('#delPergunta').click(function() {
        var form = $(this).closest('form'); // Encontra o formulário pai do botão clicado
        var formData = form.serialize(); // Serializa os dados do formulário
        formData += '&form-del=form-del';

        $.ajax({
            type: 'POST',
            url: 'controller/form-controller.php',
            data: formData,
            success: function(response) {
                console.log(response);
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Dados excluídos com sucesso!",
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function() {
                    window.location.href = 'index.php?url=form-detail&reg=<?=$_GET['reg']?>';
                }, 1490);
            },
            error: function(error) {
                console.error('Erro:', error);
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Ocorreu um erro ao executar a ação!",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });
});