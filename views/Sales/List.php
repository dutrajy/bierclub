<?php $layout = "Default" ?>

<?php $block("content") ?>
<div class="my-4">

<h2>Vendas</h2>

<a href="/sales/new" class="btn btn-outline-dark my-4">Realizar Venda</a>

<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Descrição</th>
        <th scope="col">Total</th>
        <th scope="col">Ações</th>
    </tr>
    </thead>

    <tbody>
        <?php foreach ($sales as $sale) : ?>
        <tr>
            <td><?= $sale->getId() ?></td>
            <td><?= $sale->getObservations() ?></td>
            <td class="text-right">R$ <?= number_format($sale->getTotal(), 2, ",", ".") ?></td>
            <td>
                <a href="/sales/edit/<?= $sale->getId() ?>">Detalhes</a>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

</div>
<?php $block("content") ?>
