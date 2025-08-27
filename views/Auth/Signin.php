<?php $layout = "Auth" ?>

<?php $block("content") ?>
<div class="col-md-5 mt-2 mx-auto w-100">
<div class="card">
<article class="card-body">
	<a href="https://403bierclub.ml">
	<h4 class="card-title text-center mt-2">Commercial Base Project</h4></a>
	<hr class="my-4">
	<form action="/auth/signin" method="POST">
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-user"></i> </span>
				</div>
				<input name="username" id="user-field" class="input form-control" placeholder="Usuário" type="text">
			</div> <!-- input-group -->
		</div> <!-- form-group -->

		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-lock"></i> </span>
				</div>
				<input name="password" id="pass-field" class="input form-control" placeholder="Senha" type="password">
			</div> <!-- input-group -->
		</div> <!-- form-group -->

		<hr class="my-4">

		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-block"> Entrar </button>
		</div> <!-- form-group -->
	</form>
</article>
</div><!-- card -->
</div><!-- column -->
<?php $block("content") ?>
