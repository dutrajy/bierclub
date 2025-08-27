<?php $layout = "Default"; $title = "Commercial Accounts" ?>

<?php $block("content") ?>
<div class="container">

<h2>Contas</h2>

<a href="/financial/accounts/new" class="btn btn-outline-dark my-4">Criar Conta</a>

<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Dono</th>
        <th scope="col">Saldo</th>
        <th scope="col">Ações</th>
    </tr>
    </thead>

    <tbody>
        <?php foreach ($accounts as $account) : ?>
        <?php $balance = $account->getBalance() ?>
        <tr>
            <td><?= $account->getId() ?></td>
            <td>
                <?= $account->getOwner()->getFullName() . ": " ?>
                <span class="text-secondary">
                    <?= $account->getOwnerType() . " #". $account->getOwnerId() ?>
                </span>
            </td>
            <td class="<?= $balance < 0 ? "text-danger" : "text-success"?>">
                <?= "R$ " . number_format($balance, 2, ",", ".") ?>
            </td>
            <td>
                <a class="text-primary ml-0" href="financial/accounts/details/<?= $account->getId() ?>">Extrato</a>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

</div>
<?php $block("content") ?>
