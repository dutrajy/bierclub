<?php $layout = "Default" ?>

<?php $block("content") ?>
<div class="container">

<h2>Produtos</h2>


<div class="row">
    <div class="col-md-2">
        <a href="/products/new" class="btn btn-outline-dark">Adicionar Produto</a>
    </div>
    <div class="col-md-10">
        <div class="form-group float-right">
            <input name="search" type="text" class="form-control money">
        </div>
        <label class="float-right mt-2 mr-2">Pesquisar</label>
    </div>
</div>

<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Nome</th>
        <th scope="col">Preço</th>
        <th scope="col">Ações</th>
    </tr>
    </thead>

    <tbody id="products_table">
        <?php foreach ($products as $product) : ?>
        <tr>
            <td class="<?= $product->getActive() == 0 ? "text-danger" : "" ?>"><?= $product->getId() ?></td>
            <td class="<?= $product->getActive() == 0 ? "text-danger" : "" ?>"><?= $product->getName() ?></td>
            <td><?= "R$ " . number_format($product->getPrice(), 2, ",", ".") ?></td>
            <td>
                <a class="text-primary ml-0" href="/products/edit/<?= $product->getId() ?>">Editar</a>
                <a class="text-danger mx-x product_delete" href="#" data-id="<?= $product->getId() ?>">Deletar</a>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
    $(".product_delete").click(function(event) {
        event.preventDefault();
        let id = $(this).data("id");

        let sure = window.confirm("Tem certeza que deseja deletar o Produto?");

        if (sure) {
            $.ajax({
                url: "/products/delete/" + id,
                type: "DELETE",
                success: function(result) {
                    alert(result);
                    window.location.replace("/products");
                }
            });
        }
    });

    $('input[name="search"]').on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#products_table tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
<?php $block("content") ?>
