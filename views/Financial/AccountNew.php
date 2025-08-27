<?php $layout = "Default"; $title = "Criar Conta" ?>

<?php $block("content") ?>
<div class="container mx-auto">
<h2><?= $title ?></h2>

<form class="py-2" action="/financial/accounts/<?= $action ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="account[id]" value="<?= $account->getId() ?>">

    <div class="form-group row">
    <div class="col-md-5">
        <label>Proprietário</label>

        <select class="form-control" name="owner[id]">
            <?php foreach($users as $user) : ?>
                <option value="<?= $user->getId() ?>"><?= $user->getId() . " - " . $user->getFullName() ?></option>
            <?php endforeach ?>
        </select>
    </div>
    </div>


    <div class="form-group row">
        <div class="col-md-8">
            <label>Descrição</label>
            <textarea name="account[description]" class="form-control" rows="5"></textarea>
        </div>
    </div>


    <button type="submit" class="btn btn-primary"><?= $action == "create" ? "Cadastrar" : "Atualizar" ?></button>
</form>
</div>
<?php $block("content") ?>
