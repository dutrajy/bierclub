<?php $layout = "Default" ?>

<?php $block("content") ?>
<div class="my-4">

<h2>Membros</h2>

<div class="row">
    <div class="col-md-2">
        <a href="/bierclub/members/new" class="btn btn-outline-dark">Cadastrar Novo</a>
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
        <th scope="col">Saldo</th>
        <th scope="col">Ações</th>
    </tr>
    </thead>

    <tbody id="bierclub_members_table">
        <?php foreach ($members as $member) : ?>
        <tr>
            <td><?= $member->getId() ?></td>
            <td><?= $member->getFullName() ?></td>
            <td><?= $money($member->getBalance()) ?></td>
            <td><a href="/bierclub/members/edit/<?= $member->getId() ?>">Editar</a></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
  $('input[name="search"]').on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#bierclub_members_table tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
<?php $block("content") ?>
