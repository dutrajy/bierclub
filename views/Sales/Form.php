<?php $layout = "Default" ?>

<?php $block("content") ?>
<div class="container mx-auto">
<h2><?= $title ?></h2>

<form class="py-2" action="/sales/create" method="POST">
    <input type="hidden" name="sale[id]" value="<?= $sale->getId() ?>">

    <h3 class="pt-4">Produtos</h3>
    <div class="mb-4">
        <button type="button" class="btn btn-primary float-right mb-4" data-toggle="modal" data-target="#modal-products">
            Adicionar Produto
        </button>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">EAN</th>
                <th scope="col">Nome</th>
                <th scope="col">Preço</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Total</th>
            </tr>
            </thead>

            <tbody id="table-products">
                <?php foreach ($items as $item) : ?>
                <tr data-id="<?= $item->getProduct()->getId() ?>">
                    <td><?= $item->getProduct()->getEan() ?></td>
                    <td><?= $item->getProduct()->getName() ?></td>
                    <td>R$ <?= number_format($item->getProduct()->getPrice(), 2, ",", ".") ?></td>
                    <td><?= $item->getQuantity() ?></td>
                    <td>R$ <?= number_format($item->getQuantity() * $item->getProduct()->getPrice(), 2, ",", ".") ?></td>
                    <input type="hidden" name="products[<?= $item->getProduct()->getId() ?>]" value="<?= $item->getProduct()->getId() ?>">
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="form-group row mt-4">
        <div class="col-md-10">
            <label>Observações</label>
            <textarea name="sale[observations]" class="form-control" rows="5"><?= $sale->getObservations() ?></textarea>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-3">
            <label>Total</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">R$</div>
                </div>
                <input name="sale[price]" type="text" class="form-control money" value="<?= $sale->getTotal() ? number_format($sale->getTotal(), 2, ",", ".") : "0,00" ?>">
            </div>
        </div>
    </div>

    <div class="modal" id="modal-products" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produtos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="container">
                <div class="row">
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
                </div>

                <div class="my-4 mx-0 px-0">
                    <ul class="list-group" id="list-products">
                    </ul>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        </div>
    </div>

    <button type="submit" class="mt-4 btn btn-primary">Cadastrar</button>
</form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
  $('.money').mask('000.000.000.000.000,00', {reverse: true});

  $.get("/api/products").done(function (data) {
      COMMERCIAL["products"] = data;
      COMMERCIAL["cart"] = [];

      data.forEach(function(item) {
        var row = '<li class="list-group-item">' + item.name
                + '<span class="badge badge-success ml-2">'
                + new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(item.price)
                + '</span>'
                + '<button type="button" data-id="' + item.id + '" class="btn btn-primary btn-sm float-right add-product">'
                + '<i class="fas fa-plus"></i></button>'
                + '</li>';

        $("#list-products").append(row);
      });

    $(".add-product").click(function () {
        var id = $(this).data('id');
        var product = COMMERCIAL['cart'].find(function (product) { return product.id == id; });

        if (product) {
            product.quantity += 1;
            $("#table-products").find('tr[data-id=' + id + ']').remove();
        } else {
            var product = COMMERCIAL['products'].find(function (product) { return product.id == id; });
            product.quantity = 1;
            COMMERCIAL['cart'].push(product);
        }


        var row = '<tr data-id="' + product.id + '">'
            + '<td>' + product.ean + '</td>'
            + '<td>' + product.name + '</td>'
            + '<td>' + new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(product.price) + '</td>'
            + '<td>' + product.quantity + '</td>'
            + '<td>' + new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(product.price * product.quantity) + '</td>'
            + '<input type="hidden" name="products[' + id + ']" value="' + product.quantity + '">'
            + '</tr>';

        $("#table-products").append(row);
        $('#modal-products').modal('hide');

        let products = COMMERCIAL['cart'];
        let total = 0;
        products.forEach(function (product) {
            total += product.price * product.quantity;
        });

        $('input[name="sale[price]"]').val(new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(total));
    });
  });

});
</script>
<?php $block("content") ?>
