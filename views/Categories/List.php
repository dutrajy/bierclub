<?php $layout = "Default" ?>

<?php $block("content") ?>
<div class="container">

<h2>Categorias</h2>

<a href="/categories/new" class="btn btn-outline-dark my-4">Adicionar Categoria</a>

<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Nome</th>
        <th scope="col">Descrição</th>
        <th scope="col">Ações</th>
    </tr>
    </thead>

    <tbody>
        <?php foreach ($categories as $category) : ?>
        <tr>
            <td><?= $category->getId() ?></td>
            <td><?= $category->getName() ?></td>
            <td><?= $category->getDescription() ?></td>
            <td>
                <a class="text-primary ml-0" href="categories/edit/<?= $category->getId() ?>">Editar</a>
                <a class="text-danger mx-x product_delete" href="#" data-id="<?= $category->getId() ?>">Deletar</a>
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

        let sure = window.confirm("Tem certeza que deseja deletar o Categoria?");

        if (sure) {
            $.ajax({
                url: "/categories/delete/" + id,
                type: "DELETE",
                success: function(result) {
                    alert(result);
                    window.location.replace("/categories");
                }
            });
        }
    });
});
</script>
<?php $block("content") ?>
