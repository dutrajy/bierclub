<?php $layout = "Default" ?>

<?php $block("content") ?>
<div class="container mx-auto">
<h2>Impressora</h2>

<form class="py-2" action="/printer/print" method="POST" enctype="multipart/form-data">
    <div class="form-group row">
        <div class="col-md-8">
            <label>Texto</label>
            <textarea name="msg" rows="8" cols="24"class="form-control"></textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Enviar para Impressora</button>
</form>
</div>
<?php $block("content") ?>
