<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8"/>
  <meta http-equiv="Content-Type" content="text/html"/>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/form-validation.css" rel="stylesheet">
  <title>PrevineBSB</title>
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
  <!-- Adicionando Javascript -->
  <script>

    function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('endereco_paciente').value=("");
            document.getElementById('bairro_paciente').value=("");
            document.getElementById('cidade_paciente').value=("");
            document.getElementById('uf_paciente').value=("");
            //document.getElementById('ibge').value=("");
          }

          function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('endereco_paciente').value=(conteudo.logradouro);
            document.getElementById('bairro_paciente').value=(conteudo.bairro);
            document.getElementById('cidade_paciente').value=(conteudo.localidade);
            document.getElementById('uf_paciente').value=(conteudo.uf);
            //document.getElementById('ibge').value=(conteudo.ibge);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
          }
        }
        
        function pesquisacep(valor) {

        //Nova variável "cep" somente com dígitos.
        var cep_paciente = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep_paciente != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep_paciente)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('endereco_paciente').value="...";
                document.getElementById('bairro_paciente').value="...";
                document.getElementById('cidade_paciente').value="...";
                document.getElementById('uf_paciente').value="...";
                //document.getElementById('ibge').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep_paciente + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
              }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
          }
        };
      </script>
    </head>

    <body class="bg-light container">

      <div>
        <header>
          <figure>
            <img src="imagens/1.jpeg" alt="" width="300" height="150">
          </figure>
          <hgroup>
            <div class="px-4 py-5" id="hanging-icons">
              <h2 class="pb-2 border-bottom">Dados do Paciente</h2>
            </div>
          </hgroup>
        </header>
        
        <div class="container">
          <main>
           <div class="col-md-7 col-lg-8">
           
            <?php
            include ("config.php");
            $sql_usuarios = "SELECT u.*,p.*,s.*
            FROM usuarios u JOIN paciente p
            ON u.id_usuarios = p.usuarios_id_usuarios
            JOIN StatusPaciente s 
            ON p.StatusPaciente_id_statuspaciente = s.id_statuspaciente
            WHERE id_usuarios = ".$_REQUEST["id_usuarios"];

            $res_usuarios = $conn -> query($sql_usuarios) or die($conn->error);

            $row = $res_usuarios->fetch_object();

            ?>

            <form class="needs-validation" novalidate method="post" action="?page=save-paciente">
              <input type="hidden" name="acao" value="editar">
              <input type="hidden" name="id_usuarios" value="<?php echo $row->id_usuarios; ?>">
              <div class="row g-3">

                <div class="col-sm-6">
                  <label class="form-label">CPF*</label>
                  <input type="text" class="form-control" placeholder="000.000.000-00" value="<?php echo $row->cpf; ?>" disabled="">
                  <div class="invalid-feedback">Digite um CPF válido</div>
                </div>

                <div class="col-sm-6">
                  <label class="form-label">Cartão SUS*</label>
                  <input type="number"  class="form-control" placeholder="000 0000 0000 0000" value="<?php echo $row->CartaoSUS; ?>" disabled="">
                  <div class="invalid-feedback">Digite um número válido</div>
                </div>

                <div class="col-12">
                  <label class="form-label">Nome Completo*</label>
                  <div class="input-group has-validation">
                    <input type="text" class="form-control" name="nome" value="<?php echo $row->nome; ?>" placeholder="" required>
                    <div class="invalid-feedback">Digite um nome válido</div>
                  </div>
                </div>

                <div class="col-sm-6">
                  <label class="form-label">E-mail*</label>
                  <input type="email" class="form-control" name="email" placeholder="voce@exemplo.com" value="<?php echo $row->email; ?>" required>
                  <div class="invalid-feedback">Digite um e-mail válido</div>
                </div>

                <div class="col-sm-6">
                  <label class="form-label">Data de Nascimento*</label>
                  <input type="date" class="form-control" name="DataNascimento" value="<?php echo $row->DataNascimento; ?>" placeholder="">
                  <div class="invalid-feedback">Digite uma data válida</div>
                </div>
                
                <div class="col-sm-6">
                  <label class="form-label">Telefone (Opcional)</label>
                  <input type="number" class="form-control" name="telefone" value="<?php echo $row->telefone; ?>" placeholder="(DDD) 0000-0000">
                  <div class="invalid-feedback">Digite um número válido</div>
                </div>

                <div class="col-sm-6">
                  <label class="form-label">Celular*</label>
                  <input type="number" class="form-control" name="celular" value="<?php echo $row->celular; ?>" placeholder="(DDD) 00000-0000" required>
                  <div class="invalid-feedback">Digite um número válido</div>
                </div>

                <div class="col-sm-6">
                  <label class="form-label">Gênero*</label>
                  <select class="form-select" name="genero" aria-label="Default select example" required>
                    <option selected><?php echo $row->genero;?></option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                  </select>
                  <div class="invalid-feedback">Selecione uma opção válida</div>
                </div>

                <div class="col-sm-6">
                  <label class="form-label">Cor*</label>
                  <select class="form-select" name="cor" aria-label="Default select example" required>
                    <option selected><?php echo $row->cor;?></option>
                    <option value="Amarelo">Amarelo</option>
                    <option value="Branco">Branco</option>
                    <option value="Indigena">Indígena</option>
                    <option value="Negro">Negro</option>
                    <option value="Pardo">Pardo</option>
                  </select>
                  <div class="invalid-feedback">Selecione uma opção válida</div>
                </div>

                <div class="col-sm-3">
                  <label class="form-label">CEP*</label>
                  <input type="text"class="form-control" name="cep" size="10" maxlength="9" id="cep_paciente" placeholder="00.000-000" value="<?php echo $row->cep; ?>"  required onblur="pesquisacep(this.value);">
                  <div class="invalid-feedback">Digite um CEP válido</div>
                </div>

                <div class="col-sm-3">
                  <label class="form-label">Cidade*</label>
                  <input type="text" class="form-control" name="cidade" size="60" id="cidade_paciente" placeholder="" value="<?php echo $row->cidade; ?>" required>
                  <div class="invalid-feedback">Digite uma informação válida</div>
                </div>

                <div class="col-sm-3">
                  <label class="form-label">Bairro*</label>
                  <input type="text" class="form-control" name="bairro" size="40" id="bairro_paciente" placeholder="" value="<?php echo $row->bairro; ?>" required>
                  <div class="invalid-feedback">Digite uma informação válida</div>
                </div>

                <div class="col-sm-3">
                  <label class="form-label">Estado*</label>
                  <input type="text" class="form-control" name="estado" size="2" id="uf_paciente" placeholder="" value="<?php echo $row->estado; ?>" required>
                  <div class="invalid-feedback">Digite uma informação válida</div>
                </div>

                <div class="col-12">
                  <label class="form-label">Endereço*</label>
                  <input type="text" class="form-control" name="logradouro" size="40" id="endereco_paciente" placeholder="" value="<?php echo $row->logradouro; ?>" required>
                  <div class="invalid-feedback">Digite uma informação válida</div>
                </div>

                <div class="col-12">
                  <label class="form-label">Complemento (Opcional)</label>
                  <input type="text" class="form-control" name="complemento" size="40" placeholder="" value="<?php echo $row->complemento; ?>">
                  <div class="invalid-feedback">Digite uma informação válida</div>
                </div>

                <div class="col-sm-3">
                  <label class="form-label">Dependentes*</label>
                  <select class="form-select" name="dependentes" aria-label="Default select example" required>
                    <option selected><?php echo $row->dependentes;?></option>
                    <option value="Sim">Sim</option>
                    <option value="Nao">Não</option>
                  </select>
                  <div class="invalid-feedback">Selecione uma opção válida</div>
                </div>

                <div class="col-sm-2">
                  <label class="form-label">Quantidade</label>
                  <input type="text" class="form-control" name="QuantDep" placeholder="" value="<?php echo $row->QuantDep; ?>">
                  <div class="invalid-feedback">Selecione uma opção válida</div>
                </div>

                <div class="col-sm-4">
                  <label class="form-label">Agendamento*</label>
                  <select class="form-select" name="DiaSemana" aria-label="Default select example" required>
                    <option selected><?php echo $row->DiaSemana;?></option>
                    <option value="Segunda">Segunda-Feira</option>
                    <option value="Terça">Terça-Feira</option>
                    <option value="Quarta">Quarta-Feira</option>
                    <option value="Quinta">Quinta-Feira</option>
                    <option value="Sexta">Sexta-Feira</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                  </select>
                  <div class="invalid-feedback">Selecione uma opção válida</div>
                </div>

                <div class="col-sm-3">
                  <label class="form-label">Período*</label>
                  <select class="form-select" name="periodo" aria-label="Default select example" required>
                    <option selected><?php echo $row->periodo;?></option>
                    <option value="Manha">Manhã</option>
                    <option value="Tarde">Tarde</option>
                    <option value="Noite">Noite</option>
                  </select>
                  <div class="invalid-feedback">Selecione uma opção válida</div>
                </div>

                <div class="col-sm-3">
                  <label class="form-label">Status*</label>
                  <select class="form-select" name="StatusPaciente_id_statuspaciente" aria-label="Default select example" required>
                    <option selected><?php echo $row->StatusPaciente_id_statuspaciente;?></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                  <div class="invalid-feedback">Selecione uma opção válida</div>
                </div>

                <div class="col-12">
                  <input type="submit" class="btn btn-primary"  value="Editar"/>
                </div>
              </form>
              
              <div class="my-4">
                <a href="paciente-relatorio.php">Voltar para Relatório Paciente</a>
              </div>

            </div>
          </main>
        </div>

      </div>

      <?php
      switch(@$_REQUEST["page"]){
      //paciente
      case "cad-paciente":
      include("paciente-cadastrar.php");
      break;
      case "list-paciente":
      include("paciente-listar.php");
      break;
      case "edit-paciente":
      include("paciente-editar.php");
      break;
      case "save-paciente":
      include("paciente-salvar.php");
      break;
    }
    ?>

    <footer>
      <p class="mt-5 mb-3 text-muted">&copy; Desenvolvido por Keilly Francielly, Matheus Corrêa e Thairo Fortes :: Todos os Direitos Reservados.</p>
    </footer>
  </body>
  </html>