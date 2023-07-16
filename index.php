<?php
require('db/conexao.php');

//COMANDO PARA ALTERAR DADOS
// $up_nome="José Lima Silva";
// $up_id=4;
// $sql = $pdo->prepare("UPDATE clientes SET nome=? WHERE id=?");
// $sql->execute(array($up_nome, $up_id));

//COMANDO PARA DELETAR DADOS
//$sql = $pdo->prepare("DELETE FROM clientes WHERE id=?");
//$sql->execute(array(18));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="./img/table.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Database System</title>
</head>

<body>
    <div class="div_container">
        <h1>CLIENTS</h1>
        <form class="form_buscar oculto">
            <div class="div_title">Search Client</div>
            <div class="div_input">
                <input type="text" id="input-buscar" placeholder="Id, Name, Email, Data or Hour">
            </div>
            <div class="div_button">
                <button class="btn_geral" type="button" id="button_cancelar_bus"
                    name="button_cancelar_bus">CANCEL</button>
            </div>
        </form>
        <form class="form_inserir oculto" method="post">
            <div class="div_title">Insert Client</div>
            <div class="div_input">
                <input type="text" name="input_nome" id="input_nome" placeholder="Full Name" required>
                <input type="email" name="input_email" id="input_email" placeholder="Email" required>
            </div>
            <div class="div_button">
                <button class="btn_geral" type="button" id="button_cancelar_in"
                    name="button_cancelar_in">CANCEL</button>
                <button class="btn_geral" type="submit" name="button_inserir">INSERT</button>
            </div>
        </form>

        <form class="form_editar oculto" method="post">
            <div class="div_title">Edit Client</div>
            <p type="text" id="p_id" name="p_id" style="display: none;"></p>
            <div class="div_input">
                <input type="text" id="id_editado" name="id_editado" style="display:none" required>
                <input type="text" id="nome_editado" name="nome_editado" placeholder="Edit Name" required>
                <input type="email" id="email_editado" name="email_editado" placeholder="Edit Email" required>
            </div>
            <div class="div_button">
                <button class="btn_geral" type="button" id="button_cancelar_ed"
                    name="button_cancelar_ed">CANCEL</button>
                <button class="btn_geral" type="submit" id="button_salvar" name="button_salvar">SAVE</button>
            </div>
        </form>

        <form class="form_deletar oculto" method="post">
            <div class="div_title">Delete Client</div>
            <div class="div_input">
                <div class="div_deletar"><div class="div_deletar_txt"> Are you sure you want to delete <strong><span
                            id="cliente_adel"></span></strong>?</div></div>
                <input type="hidden" id="id_adel" name="id_adel">
                <input type="hidden" id="nome_adel" name="nome_adel">
                <input type="hidden" id="email_adel" name="email_adel">
            </div>
            <div class="div_button">
                <button class="btn_geral" type="button" id="button_cancelar_del"
                    name="button_cancelar_del">CANCEL</button>
                <button class="btn_geral" type="submit" id="button_deletar" name="button_deletar">DELETE</button>
            </div>
        </form>


        <?php
        //INSERIR UM DADO NO BANCO (modo simples)
        //$sql = $pdo->prepare("INSERT INTO clientes VALUES (null, 'Dimitri Teixeira', 'teste@teste.com', '18/09/2021')");
        //$sql -> execute();

        //PROCESSO DE INSERCAO DE UM DADO NO BANCO (modo correto ANTI SQL INJECTION)
        //VALIDACAO
        if (isset($_POST['button_inserir']) && isset($_POST['input_nome']) && isset($_POST['input_email'])){
            $nome = limpar_dado($_POST['input_nome']);
            $email = limpar_dado($_POST['input_email']);
            $data_cadastro = date('d/m/Y-H:i:s');
            //VALIDACAO DE CAMPO VAZIO
            if ($nome=="" || $nome==null || $email=="" || $email==null){
                echo '<div class="msg_erro">NOME e EMAIL não podem ser vazios!</div>';
                exit();
            }
            //VALIDACAO DE NOME
            if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
                echo '<div class="msg_erro">Somente letras e espaços são permitidos no campo NOME!</div>';
                exit();
            }  
            //VALIDACAO DE EMAIL
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo '<div class="msg_erro">Formato inválido no campo EMAIL!</div>';
                exit();
            }
            //INSERCAO NO BANCO
            $sql = $pdo->prepare("INSERT INTO clientes VALUES (null,?,?,?)");
            $sql -> execute(array($nome,$email,$data_cadastro));
            echo '<div class="msg_sucesso">Client has been successfully inserted!</div>';                               
        }

        ?>

        <?php
        //PROCESSO DE EDICAO
        if(isset($_POST['button_salvar']) && isset($_POST['id_editado']) && isset($_POST['nome_editado']) && isset($_POST['email_editado'])){
            $id = limpar_dado($_POST['id_editado']);
            $nome=limpar_dado($_POST['nome_editado']);
            $email=limpar_dado($_POST['email_editado']);

            //VALIDAÇÃO DE CAMPO VAZIO
            if ($nome=="" || $nome==null || $email=="" || $email==null){
                echo '<div class="msg_erro">NOME e EMAIL não podem ser vazios!</div>';
                exit();
            }
            //VALIDACAO DE NOME
            if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
                echo '<div class="msg_erro">Somente letras e espaços são permitidos no campo NOME!</div>';
                exit();
            }  
            //VALIDACAO DE EMAIL
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo '<div class="msg_erro">Formato inválido no campo EMAIL!</div>';
                exit();
            }    

            //COMANDO PARA ATUALIZAR
            $sql = $pdo->prepare("UPDATE clientes SET nome=?, email=? WHERE id=?");
            $sql->execute(array($nome,$email,$id));
            echo '<div class="msg_sucesso">Registration updated successfully!</div>';
            //echo '<div class="msg_sucesso">'.$sql->rowCount().' registro atualizado com sucesso!</div>';
        }
        ?>

        <?php
        //DELETAR DADOS
        if(isset($_POST['button_deletar']) && isset($_POST['id_adel']) && isset($_POST['nome_adel']) && isset($_POST['email_adel'])){
            $id=limpar_dado($_POST['id_adel']);
            $nome=limpar_dado($_POST['nome_adel']);
            $email=limpar_dado($_POST['email_adel']); 
            //COMANDO PARA DELETAR       
            $sql = $pdo->prepare("DELETE FROM clientes WHERE id=? AND nome=? AND email=?");
            $sql->execute(array($id, $nome, $email));
            echo '<div class="msg_sucesso">Client successfully deleted!</div>';
        }
        ?>

        <?php     
            //SELECIONAR DADO ESPECIFICO DO BANCO PELO EMAIL
            // $sql = $pdo->prepare("SELECT * FROM clientes WHERE email=?");
            // $email= "elis@henrique.com";
            // $sql->execute(array($email));
            // $dados = $sql->fetchAll();

            
            //PROCESSO DE SELECIONAR TODOS OS DADOS DO BANCO
            $sql = $pdo->prepare("SELECT * FROM clientes"); //ORDEM PADRAO (POR ID)
            //$sql = $pdo->prepare("SELECT * FROM clientes ORDER BY nome"); //ORDEM POR NOME ASCENTEDENTE
            //$sql = $pdo->prepare("SELECT * FROM clientes ORDER BY nome ASC"); //ORDEM POR NOME ASCENDENTE (OUTRA FORMA)
            //$sql = $pdo->prepare("SELECT * FROM clientes ORDER BY nome DESC"); //ORDEM POR NOME DECRESCENTE
            //$sql = $pdo->prepare("SELECT * FROM clientes ORDER BY nome LIMIT 5"); //ORDEM POR NOME ASCENTEDENTE, LIMITANDO A SELECAO EM 5 REGISTROS
            //$sql = $pdo->prepare("SELECT * FROM clientes LIMIT 5"); //LIMITANDO A SELECAO EM 5 REGISTROS (ORDEM PADRAO)
            //$sql = $pdo->prepare("SELECT * FROM clientes LIMIT 5,3"); //LIMITA A SELECAO EM 3 REGISTROS, COMEÇANDO A SELECAO NO REGISTRO 5 (LEMBRANDO QUE O PRIMEIRO É 0)
            //$sql = $pdo->prepare("SELECT * FROM clientes ORDER BY id LIMIT 5,3"); //IDEM ANTERIOR
            if(isset($_POST['id-baixo'])){
                $sql = $pdo->prepare("SELECT * FROM clientes ORDER BY id ASC");
            }
            if(isset($_POST['id-cima'])){
                $sql = $pdo->prepare("SELECT * FROM clientes ORDER BY id DESC");
            }
            if(isset($_POST['nome-baixo'])){
                $sql = $pdo->prepare("SELECT * FROM clientes ORDER BY nome ASC");
            }
            if(isset($_POST['nome-cima'])){
                $sql = $pdo->prepare("SELECT * FROM clientes ORDER BY nome DESC");
            }
            if(isset($_POST['email-baixo'])){
                $sql = $pdo->prepare("SELECT * FROM clientes ORDER BY email ASC");
            }
            if(isset($_POST['email-cima'])){
                $sql = $pdo->prepare("SELECT * FROM clientes ORDER BY email DESC");
            }
            if(isset($_POST['data-baixo'])){
                $sql = $pdo->prepare("SELECT * FROM clientes ORDER BY data_cadastro ASC");
            }
            if(isset($_POST['data-cima'])){
                $sql = $pdo->prepare("SELECT * FROM clientes ORDER BY data_cadastro DESC");
            }
            $sql->execute();
            $dados = $sql->fetchAll();
            // echo "<pre>"; 
            // print_r($dados); IMPRESSÃO SIMPLES AGRUPADA
            // echo "</pre>";                                 
            //LISTAR DADOS NA TABELA
            if(count($dados)>0){
                echo "
                <button class='btn-main' id='btn-buscar'>SEARCH CLIENT</button> 
                <button class='btn-main' id='btn-inserir'>INSERT CLIENT</button>
                <div class='div_tabela'>
                    <table>
                        <thead>
                            <tr>
                                <th class='col-id'><div class='div-col'>ID<form method='post'><div class='div-btn-filtro'><button class='btn-up' title='Sort in descending' type='submit' id='id-cima' name='id-cima'></button><button class='btn-down' title='Sort in ascending' type='submit' id='id-baixo' name='id-baixo'></button></div></div></th>
                                <th class='col-nome'><div class='div-col'>NAME<form method='post'><div class='div-btn-filtro'><button class='btn-up' title='Sort in descending' type='submit' id='nome-cima' name='nome-cima'></button><button class='btn-down' title='Sort in ascending' type='submit' id='nome-baixo' name='nome-baixo'></button></div></div></th>
                                <th class='col-email'><div class='div-col'>EMAIL<form method='post'><div class='div-btn-filtro'><button class='btn-up' title='Sort in descending' type='submit' id='email-cima' name='email-cima'></button><button class='btn-down' title='Sort in ascending' type='submit' id='email-baixo' name='email-baixo'></button></div></div></th>
                                <th class='col-data'><div class='div-col'>DATA-HOUR<form method='post'><div class='div-btn-filtro'><button class='btn-up' title='Sort in descending' type='submit' id='data-cima' name='data-cima'></button><button class='btn-down' title='Sort in ascending' type='submit' id='data-baixo' name='data-baixo'></button></div></div></th>
                                <th class='col-acao'>ACTIONS</th>
                            </tr>
                        </thead>";
                foreach($dados as $chave=>$valor){
                    echo "<tbody id='table-buscar'>
                            <tr>
                                <td>".$valor['id']."</td>
                                <td>".$valor['nome']."</td>
                                <td>".$valor['email']."</td>
                                <td>".$valor['data_cadastro']."</td>
                                <td><div class='div-action'><a class='button_editar' title='Edit Client' data-id='".$valor['id']."' data-nome='".$valor['nome']."'data-email='".$valor['email']."'></a><a class='button_adel' title='Delete Client' data-id='".$valor['id']."' data-nome='".$valor['nome']."'data-email='".$valor['email']."'></a></div></td>
                                
                            </tr>
                        </tbody>";                
                }
                echo "</table></div>";
            }else{
                echo "<button class='btn-main' id='btn-inserir'>INSERT CLIENT</button>
                <div id='div_nenhum'>No client registered!</div>";
            }
            
        ?>

        <script src="js/jquery-3.7.0.js"></script>
        <script>
            $(document).ready(function () {
                $("#btn-buscar").click(function () {
                    $('.form_buscar').removeClass('oculto');
                    $('.form_inserir').addClass('oculto');
                    $('.form_editar').addClass('oculto');
                    $('.form_deletar').addClass('oculto');
                    $('.msg_sucesso').remove();
                    $('.msg_erro').remove();
                    $('#input-buscar').focus();
                });

                $("#btn-inserir").click(function () {
                    $('.form_inserir').removeClass('oculto');
                    $('.form_buscar').addClass('oculto');
                    $('.form_editar').addClass('oculto');
                    $('.form_deletar').addClass('oculto');
                    $('.msg_sucesso').remove();
                    $('.msg_erro').remove();

                    $('#input-buscar').val('');
                    var valor_busca = '';
                    $('#table-buscar tr').filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(valor_busca) > -1);
                    })

                    $('#input_nome').focus();

                });

                $(".button_editar").click(function () {
                    var id = $(this).attr('data-id');
                    var nome = $(this).attr('data-nome');
                    var email = $(this).attr('data-email');

                    $("#p_id").text(id);
                    $("#id_editado").val(id);
                    $("#nome_editado").val(nome);
                    $("#email_editado").val(email);

                    $('.form_buscar').addClass('oculto');
                    $('.form_inserir').addClass('oculto');
                    $('.form_deletar').addClass('oculto');
                    $('.form_editar').removeClass('oculto');
                    $('.msg_sucesso').remove();
                    $('.msg_erro').remove();

                    $('#nome_editado').focus();

                });

                $(".button_adel").click(function () {
                    var id = $(this).attr('data-id');
                    var nome = $(this).attr('data-nome');
                    var email = $(this).attr('data-email');

                    $("#id_adel").val(id);
                    $("#nome_adel").val(nome);
                    $("#email_adel").val(email);
                    $("#cliente_adel").html(nome);

                    $('.form_buscar').addClass('oculto');
                    $('.form_inserir').addClass('oculto');
                    $('.form_editar').addClass('oculto');
                    $('.form_deletar').removeClass('oculto');
                    $('.msg_sucesso').remove();
                    $('.msg_erro').remove();
                    //alert('O ID é: '+id+" | nome é: "+nome+" | email é: "+email);
                });

                $('#button_cancelar_bus').click(function () {
                    $('.form_buscar').addClass('oculto');
                    $('#input-buscar').val('');
                    var valor_busca = '';
                    $('#table-buscar tr').filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(valor_busca) > -1);
                    })
                });

                $('#button_cancelar_in').click(function () {
                    $('.form_inserir').addClass('oculto');

                    $('#input_nome').val('');
                    $('#input_email').val('');

                });

                $('#button_cancelar_ed').click(function () {
                    $('.form_editar').addClass('oculto');
                    $('#input-buscar').val('');
                    var valor_busca = '';
                    $('#table-buscar tr').filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(valor_busca) > -1);
                    })
                });

                $('#button_cancelar_del').click(function () {
                    $('.form_deletar').addClass('oculto');
                    $('#input-buscar').val('');
                    var valor_busca = '';
                    $('#table-buscar tr').filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(valor_busca) > -1);
                    })
                });

                $('.msg_sucesso').click(function () {
                    $('.msg_sucesso').remove();
                });

                $('#input-buscar').on('keyup', function () {
                    var valor_busca = $(this).val().toLowerCase();

                    $('#table-buscar tr').filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(valor_busca) > -1);
                    })
                });


            });
        </script>
    </div>
</body>

</html>