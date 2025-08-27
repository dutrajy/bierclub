<?php $layout = "Default" ?>

<?php $block("content") ?>
<div class="container mx-auto">
<h2><?= $title ?></h2>

<form id="product_form" class="py-2" action="/categories/<?= $action ?>" method="POST" enctype="multipart/form-data">
    <input id="hidden_image" name="product_image" type="hidden" value="">
    <input type="hidden" name="category[id]" value="<?= $category->getId() ?>">

    <div class="form-group row">
        <div class="col-md-8">
            <label>Nome</label>
            <input name="category[name]" type="text" class="form-control" value="<?= $category->getName() ?>">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-8">
            <label>Descrição</label>
            <textarea name="category[description]" class="form-control" rows="5"><?= $category->getDescription() ?></textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-primary"><?= $action == "create" ? "Cadastrar" : "Atualizar" ?></button>
</form>
</div>
<?php $block("content") ?>
