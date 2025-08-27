<?php $layout = "Default"; $title = "Extrato Conta" ?>

<?php $block("content") ?>
<div class="container mx-auto">
<h2><?= $title ?></h2>

<div class="row pl-3 mt-4">
    <h4>Usuário: <?= $account->getOwner()->getFullName()?> #<?= $account->getOwner()->getId() ?></h4>
</div>

<div class="row pl-3 mt-4">
    <p><?= $account->getDescription() ?></p>
</div>

<?php $operations = $account->getOperations() ?>
<table class="table table-bordered mt-4">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Descrição</th>
        <th scope="col">Data e Hora</th>
        <th scope="col">Valor</th>
        <th scope="col">Saldo</th>
    </tr>
    </thead>

    <?php $balance = 0 ?>
    <tbody>
        <?php foreach ($operations as $operation) : ?>
        <tr>
            <td><?= $operation->getId() ?></td>
            <td><?= $operation->getDescription() ?></td>
            <td><?= $operation->getCreatedAt() ?></td>
            <td class="<?= $operation->getAmount() >= 0 ? "text-success" : "text-danger" ?>">
                <?= "R$ " . number_format($operation->getAmount(), 2, ",", ".") ?>
            </td>
            <?php $balance += $operation->getAmount() ?>
            <td class="<?= $balance >= 0 ? "text-success" : "text-danger" ?>">
                <?= "R$ " . number_format($balance, 2, ",", ".") ?>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php $balance = $account->getBalance() ?>
<div class="row pl-3 mt-4">
    <h5>
        Saldo Atual:
        <span class="<?= $balance >= 0 ? "text-success" : "text-danger" ?>">
            <?= "R$ " . number_format($account->getBalance(), 2, ",", ".") ?>
        </span>
    </h5>
</div>

<button class="btn btn-primary mt-4">Fazer Lançamento</button>

</div>


<script>
document.addEventListener("DOMContentLoaded", function(){
  $('.money').mask('000.000.000.000.000,00', {reverse: true});
});
</script>
<?php $block("content") ?>
