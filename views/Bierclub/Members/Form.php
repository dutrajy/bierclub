<?php
use Commercial\Application\Bierclub\Member;
$layout = "Default"
?>

<?php $block("content") ?>
<div class="container mx-auto">
<div class="clearfix">
<h1 class="float-left"><?= $action == "create" ? "Cadastrar Novo" : "Editar" ?> Membro</h1>
<?php if ($member->getId()) : ?>
        <h2 class="float-right">ID: <?= str_pad($member->getId(), 4, '0', STR_PAD_LEFT) ?></h2>
        <h2 class="float-right mr-4 text-success">Saldo: <?= $money($member->getBalance()) ?></h2>
<?php endif ?>
</div>

<?php if ($user->getRole() === "manager" || $user->getRole() === "administrator") : ?>
<form class="float-right form-inline mt-2" action="/bierclub/member/<?= $member->getId() ?>/credit" method="post">
    <div class="form-group">
        <input name="amount" type="text" class="form-control money">
    </div>
    <button type="submit" class="btn btn-success ml-2">Inserir Saldo</button>
</form>
<?php endif ?>

<form class="py-2" action="/bierclub/members/<?= $action ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="member[id]" value="<?= $member->getId() ?>">

    
    <div class="clearfix">
        <div class="float-left">
            <select class="form-control form-control-md mt-0" name="member[active]">
                <option value="1" <?= $member->getActive() ? "selected" : "" ?>>Ativo</option>
                <option value="0" <?= !$member->getActive() ? "selected" : "" ?>>Inativo</option>
            </select>
        </div>
    </div>

    <div class="form-group row mt-3">
    <div class="col-md-10">
        <label>Nome Completo</label>
        <input name="member[full_name]" type="text" class="form-control" value="<?= $member->getFullName() ?>">
    </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label>Email</label>
            <input name="member[email]" type="text" class="form-control" value="<?= $member->getEmail() ?>">
        </div>
        <div class="col-md-4">
            <label>CPF</label>
            <input name="member[document]" type="text" class="form-control cpf" value="<?= $member->getDocument() ?>">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label>Telefone</label>
            <input name="member[phone]" type="text" class="form-control phone" value="<?= $member->getPhone() ?>">
        </div>
    </div>

    <div class="form-group row mt-4">
        <div class="col-md-2">
            <img id="member-image-preview" src="<?= $member->getImage() ? "/images/uploads/{$member->getImage()}" : "https://via.placeholder.com/512" ?>" class="img-fluid">
        </div>
        <div class="col-md-6">
            <div class="custom-file">
                <input type="file" name="member_image" class="custom-file-input" id="member-image">
                <label class="custom-file-label" for="member-image">Escolher imagem</label>
            </div>
        </div>
    </div>

    <h3 class="mb-4 mt-2">Cartão</h3>
    <div class="form-group row">
        <div class="col-md-8">
            <label>Código do Cartão</label>
            <input name="member[card]" type="text" class="form-control" value="<?= $member->getCard() ?: Member::getNewCardCode() ?>">
        </div>
    </div>


    <div class="form-group row">
        <div class="col-md-4">
            <label>Titular</label>
            <select class="form-control" name="member[titular]">
                <option></option>
                <?php foreach($members as $titular) : ?>
                    <option value="<?= $titular->getId() ?>" <?= $titular->getId() === $member->getTitular() ? "selected" : ""?>>
                        <?= $titular->getFullName() . " - " . $titular->getId() ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    
    <div class="form-group row mt-3">
    <div class="col-md-2">
        <label>Vencimento</label>
        <div class="input-group date" data-provide="datepicker">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
            </div>
            <input type="text" class="form-control">
        </div>
    </div>
    </div>

    <button type="submit" class="btn btn-primary mt-4">Cadastrar</button>
</form>
</div>


<script>
document.addEventListener("DOMContentLoaded", function(){
  $('.money').mask('#.##0,00', {reverse: true});
  $('.cpf').mask('000.000.000-00', {reverse: true});
  $('.phone').mask('(00) 00000-0000');

  function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#member-image-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#member-image").change(function() {
        readURL(this);
    });
});
</script>
<?php $block("content") ?>
