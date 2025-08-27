<?php $layout = "Default" ?>

<?php $block("content") ?>
<div class="container mx-auto">
<h2><?= $title ?></h2>

<form id="product_form" class="py-2" action="/products/<?= $action ?>" method="POST" enctype="multipart/form-data">
    <input id="hidden_image" name="product_image" type="hidden" value="">
    <input type="hidden" name="product[id]" value="<?= $product->getId() ?>">

    <div class="clearfix">
        <div class="float-right">
            <select class="form-control form-control-lg" name="product[active]">
                <option value="1" <?= $product->getActive() ? "selected" : "" ?>>Ativo</option>
                <option value="0" <?= !$product->getActive() ? "selected" : "" ?>>Inativo</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-8">
            <label>Nome</label>
            <input name="product[name]" type="text" class="form-control" value="<?= $product->getName() ?>">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <h4>Escolher arquivo...</h4>
            <label class="label" data-toggle="tooltip" title="Change your avatar">
            <img class="rounded" id="avatar" src="<?= $product->getImage() ? "/images/uploads/" . $product->getImage() : "https://via.placeholder.com/512" ?>" alt="avatar">
            <input type="file" class="sr-only" id="input" name="product_image_form" accept="image/*">
            </label>
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Recortar Imagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image" src="https://via.placeholder.com/512">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="crop">Recortar</button>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-8">
            <label>Descrição</label>
            <textarea name="product[description]" class="form-control" rows="5"><?= $product->getDescription() ?></textarea>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <label>EAN</label>
            <input name="product[ean]" type="text" class="form-control ean" value="<?= $product->getEan() ?>">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <label>Categoria</label>
            <select class="form-control" name="product[category_id]">
                <?php foreach($categories as $category) : ?>
                    <option value="<?= $category->getId() ?>" <?= $product->getCategoryId() === $category->getId() ? "selected" : ""?>>
                        <?= $category->getName() ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-3">
            <label>Preço</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">R$</div>
                </div>
                <input name="product[price]" type="text" class="form-control money" value="<?= number_format($product->getPrice(), 2, ",", ".") ?: "0,00" ?>">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary"><?= $action == "create" ? "Cadastrar" : "Atualizar" ?></button>
</form>
</div>


<script>
document.addEventListener("DOMContentLoaded", function(){
  $('.money').mask('000.000.000.000.000,00', {reverse: true});
  $('.ean').mask('0000000000000', {reverse: true});
});

window.addEventListener('DOMContentLoaded', function () {
    var avatar = document.getElementById('avatar');
    var image = document.getElementById('image');
    var input = document.getElementById('input');
    var $progress = $('.progress');
    var $progressBar = $('.progress-bar');
    var $alert = $('.alert');
    var $modal = $('#modal');
    var cropper;

    $('[data-toggle="tooltip"]').tooltip();

    input.addEventListener('change', function (e) {
        
        var files = e.target.files;
        var done = function (url) {
            input.value = '';
            image.src = url;
            $alert.hide();
            $modal.modal('show');
        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
            done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                done(reader.result);
            };
            reader.readAsDataURL(file);
            }
        }
    });

    $modal
        .on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
            });
        })
        .on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

    document.getElementById('crop').addEventListener('click', function () {
        var initialAvatarURL;
        var canvas;

        $modal.modal('hide');

        if (cropper) {
            canvas = cropper.getCroppedCanvas({
                width: 160,
                height: 160,
            });

            initialAvatarURL = avatar.src;
            avatar.src = canvas.toDataURL();

            document.getElementById('hidden_image').value = canvas.toDataURL();
        }
    });

    
});
</script>
<?php $block("content") ?>
