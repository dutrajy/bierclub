<script>
    import { onMount } from 'svelte';
    import Keyboard from "./Keyboard.svelte";

    let card = "";
    let confirmCard = "";
    let member = {};
    let logged = false;
    let loggedAsManager = false;

    let paymentModalShow = false;

    let category = [];
    let category_id = 0;

    let creditToInsert = "";

    let cardCredit = "";
    let userCredit = false;
    let confirmCardCredit = "";

    $: if (card.length == 78) login();
    $: if (confirmCard.length == 78) sendSale();
    $: if (cardCredit.length == 78) insertCreditInfo();
    $: if (confirmCardCredit.length == 78) sendCredit();

    function login() {
        fetch("/api/bierclub/member/" + window.encodeURIComponent(card))
            .then(reponse => reponse.json())
            .then((data) => {
                member = data;

                if (member.role === "administrator") {
                    loggedAsManager = true;
                    window.setTimeout(function (){
                        window.$('#confirm-card-field3').focus();
                    }, 100);
                } else {
                    logged = true;
                }
            });
    }


    function insertCreditInfo() {
        fetch("/api/bierclub/member/" + window.encodeURIComponent(cardCredit))
            .then(reponse => reponse.json())
            .then((data) => {
                userCredit = data;
                window.setTimeout(function (){
                    window.$('#credit-to-insert-field').focus();
                }, 100);
            });
    }

    let categories = [];
    let products = [];
    let futureBalance;

    function changeCategory(id) {
        category = products.filter((product) => product.category_id === id);
        category_id = id;
    }

    $: cart = products.filter((p) => p.quantity >= 1);
    $: total = cart.reduce((total, p) => total + p.quantity * p.price, 0);
    $: futureBalance = member.balance - total;

    function money(value) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value)
    }

    function addToCartByIndex(category, j) {
        if (futureBalance >= category[j].price) {
            category[j].quantity = category[j].quantity ? category[j].quantity + 1 : 1;
            products = products;
        } else {
            Swal.fire('Saldo Insuficiente!');
        }
    }

    function incrementOnCart(i) {
        if (futureBalance >= cart[i].price) {
            cart[i].quantity = cart[i].quantity ? cart[i].quantity + 1 : 1;
        } else {
            Swal.fire('Saldo Insuficiente!');
        }
    }

    function sendSale() {
        fetch("/api/bierclub/buy", {method: "POST", body: JSON.stringify({cart, member})})
            .then(function (response) { return response.json(); })
            .then(function(data) {
                Swal.fire('Retire o cupom impressora!').then(endSale);
            });
    }

    function sendCredit() {
        fetch("/api/bierclub/member/" + userCredit.id + "/credit", {method: "POST", body: JSON.stringify({amount: creditToInsert, adminCard: confirmCardCredit})})
            .then(function (response) { return response.json(); })
            .then(function(data) {
                if ( data.success ) {
                    Swal.fire('Crédito inserido com sucesso no cartão!').then(endSale);
                } else {
                    Swal.fire('Erro inesperado. Tente pela tela de administração em outro computador!').then(endSale);
                }
            });
    }

    function endSale() {

        card = "";
        confirmCard = "";
        member = {};
        logged = false;
        loggedAsManager = false;

        paymentModalShow = false;

        category = [];
        category_id = 0;

        creditToInsert = "";

        cardCredit = "";
        userCredit = false;
        confirmCardCredit = "";

        fetch("/api/categories")
            .then((response) => response.json())
            .then((data) => {
                categories = data;
            });

        fetch("/api/products")
            .then((response) => response.json())
            .then((data) => {
                products = data;
                products.forEach(p => p.quantity = 0);
                products = products;
                category = products;
                category_id = 0;
            });

        window.$('#payment-modal').modal('hide');
        window.$('#cancellation-modal').modal('hide');
        window.$('#credit-modal').modal('hide');
    }

	onMount(() => {
        window.document.addEventListener("DOMContentLoaded", function(){
            window.$('.money').mask("#.##0,00", {reverse: true});
            window.$('.credit-card').mask('0000 0000 0000 0000');
            window.$('.cpf').mask('00000000000');
            window.$('.security-code').mask('000');
            window.$('.month').mask('00');
            window.$('.year').mask('0000');
        });

        window.$('#payment-modal').on('shown.bs.modal', function () {
            window.setTimeout(function (){
                window.$('#confirm-card-field').focus();
            }, 100);
        });
        window.$('#credit-modal').on('shown.bs.modal', function () {
            window.setTimeout(function (){
                window.$('#confirm-card-field2').focus();
            }, 100);
        });
        endSale();
	});
</script>

{#if logged}
<!-- Page -->
<div class="pdv row no-gutters h-100 pt-4">

<!-- Side -->
<div class="col-sm-4 border-right border-dark d-flex flex-column h-100">
    <!-- Client -->
    <div class="row no-gutters border-bottom p-1 border-dark">

        <!-- Client Picture -->
        <div class="col-sm-5 pr-1">
            <img src="/images/uploads/{ member.image }" class="img-fluid" alt="Responsive image">
        </div><!-- /Client Picture -->

        <!-- Client Details -->
        <div class="col-md-7 clearfix">
            <div class="clearfix">
                <div class="font-weight-bold text-truncate">{ member.full_name }</div>
                <div class="text-success float-right">{ money(member.balance) }</div>
            </div>
            <button class="btn btn-sm btn-success float-right mt-2" data-toggle="modal" data-target="#mercadopago-modal">Iniserir Crédito</button>
        </div><!-- /Client Details-->

    </div><!-- /Client -->

    <!-- Cart -->
    <div class="overflow-auto flex-grow-1 py-0" style="background-color: #ffff99">
        <ul class="list-group list-group-flush border-bottom">
        {#each cart as product, index}
            <li class="list-group-item p-2 clearfix" style="background-color: #ffff99">

                 <div class="input-group float-left" style="width: 5.8rem">
                    <div class="input-group-prepend">
                        <button class="btn btn-sm btn-danger" on:click="{ () => product.quantity-- }">-</button>
                    </div>
                    <input type="text" class="form-control" bind:value="{product.quantity}">
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-primary" on:click="{ () => incrementOnCart(index) }">+</button>
                    </div>
                </div>
                <div class="float-left font-weight-bold px-2" style="overflow-x: hidden; width: calc(100% - 11rem)">{ product.name }</div>
                <div class="float-right font-weight-bold">{ money(product.quantity * product.price) }</div>
            </li>
        {/each}
        </ul>
    </div><!-- /Cart -->

    <div class="footer border-top border-dark p-2">
        <div class="row mb-2">
            <div class="col-sm-12 text-right">
                <span class="text-success h5">Total: { money(total) }</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <button class="btn btn-sm btn-danger btn-block" data-toggle="modal" data-target="#cancellation-modal">Cancelar</button>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-sm btn-primary btn-block" data-toggle="modal" data-target="#payment-modal">Pagar</button>
            </div>
        </div>
    </div>
</div><!-- /Side -->

<!-- Products -->
<div class="col-md-8 d-flex flex-column px-4 mh-100">

    <div class="row">
        <div class="col-md-12 px-0">
            <ul class="nav nav-tabs nav-fill border-0">
                <li class="nav-item">
                    <span class="nav-link { 0 == category_id ? 'active border-bottom-0 border-dark' : 'border-bottom border-dark' }" on:click="{ () => { category = products;  category_id = 0 } }">Todos</span>
                </li>
                {#each categories as category}
                    <li class="nav-item">
                        <span class="nav-link { category.id == category_id ? 'active border-bottom-0 border-dark' : 'border-bottom border-dark' }" on:click="{() => { changeCategory(category.id) }}">{ category.name }</span>
                    </li>
                {/each}
            </ul>
        </div>
    </div>

    <div class="product-list-pdv row flex-row flex-grow-1 p-0 py-3">
        <!-- Products Cards -->
        <div class="row no-gutters mx-1 pt-1 pb-3 w-100">
            {#each category as product, index}
            <div class="col-md-3 px-1 pt-2">
                <div class="card pt-2 border-dark" on:click="{() => addToCartByIndex(category, index)}">
                    <div class="card-body p-2 text-center">
                        <img src="/images/uploads/{product.image}" class="img-fluid" alt="Responsive image">
                        <p class="font-weight-bold text-center pt-2 mb-2" style="font-size: 0.75rem; min-height: 4rem">{ product.name }</p>
                        <div class="text-success float-right mb-0">{ money(product.price) }</div>
                    </div>
                </div>
            </div>
            {/each}
        </div><!-- /Products Cards -->
    </div>

</div>
<!-- /Products -->
</div><!-- /Page -->

{:else if loggedAsManager}
<main class="container-fluid h-100 pt-5">
<h1 class="mb-5">Inserir Crédito</h1>
<div class="row my-4">
    <div class="col-md-8">
        <p>Clique no campo abaixo e passe o seu cartão</p>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"> <i class="fa fa-id-card"></i> </span>
            </div>
            <input id="confirm-card-field3" class="form-control" placeholder="Passe o cartão..." type="password" bind:value={cardCredit}>

        </div> <!-- input-group -->
    </div>
</div>
<div class="row h-100">
    <div class="col-md-8">
        <p>Digite o valor a ser inserido na sua conta</p>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text">R$</div>
            </div>
            <input id="credit-to-insert-field" type="text" class="form-control money" bind:value="{creditToInsert}">
        </div>
        <div class="mt-5">
            {#if userCredit}
                Membro : { userCredit.full_name } #{ userCredit.id } <br/>
                Saldo Atual: { money(userCredit.balance) } <br/>
                Saldo Após Confirmarção : { money(parseFloat(userCredit.balance) + parseFloat(creditToInsert.replace(".", "").replace(",", "."))) } <br/>
            {/if}
        </div>
        <div>
            <p class="mt-4">Entregue o valor digitado ao gerente <strong>{ member.full_name }</strong> para que ele confirme a transação.</p>
            <button type="button" class="btn btn-success mt-2" data-toggle="modal" data-target="#credit-modal">Confirmar</button>
            <button class="btn btn-danger mt-2" on:click={endSale}>Encerrar Sessão</button>
        </div>
    </div>
    <div class="col-md-4">
        <Keyboard bind:text={creditToInsert}/>
    </div>
</div>
</main>
{:else}

<div style="position: fixed; top: 20px; right: 20px">
    <a href="https://403bierclub.ml/auth/signout"><i class="fas fa-tachometer-alt"></i></a>
</div>
<main class="container-fluid h-100">
<div class="row h-100">
<div class="col-md-9 mt-auto mx-auto w-100 pb-4">
    <img src="/images/logo.png" style="padding-bottom: 7rem;">
<div class="card">
    <article class="card-body pt-4 pb-4">
        <div class="form-group mb-0">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"> <i class="fa fa-id-card"></i> </span>
                </div>
                <input class="form-control" placeholder="Passe o cartão..." type="password" bind:value={card} autofocus>
            </div> <!-- input-group -->
        </div> <!-- form-group -->
    </article>
</div><!-- card -->
</div><!-- column -->
</div>
</main>
{/if}

<div class="modal fade" id="payment-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-4">
                {#if total > 0}
                <p>Voce comprou {money(total)} em produtos</p>
                <p>Seu saldo após a compra será de <span class="text-success">{ money(futureBalance) }</span></p>
                {:else}
                    <p>Você não inseriu nenhum produto</p>
                {/if}
            </div>
            <div class="modal-footer">
                {#if total > 0}
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-id-card"></i> </span>
                    </div>
                    <input id="confirm-card-field" class="form-control" placeholder="Passe o cartão..." type="password" bind:value={confirmCard}>

                </div> <!-- input-group -->
                {:else}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                {/if}
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="cancellation-modal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
    <div class="modal-body">
        Tem certeza que deseja cancelar esta sessão?
    </div>
    <div class="modal-footer">
        <button class="btn btn-success" data-dismiss="modal">Voltar as Compras</button>
        <button class="btn btn-danger" on:click={endSale}>Encerrar Sessão</button>
    </div>
</div>
</div>
</div>


<div class="modal fade" id="credit-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Inserção de Crédito</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-4">
                    <p>Por favor passar o cartão do gerente para confirmar</p>
                </div>
                <div class="modal-footer">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-id-card"></i> </span>
                        </div>
                        <input id="confirm-card-field2" class="form-control" placeholder="Passe o cartão..." type="password" bind:value={confirmCardCredit}>

                    </div> <!-- input-group -->
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="mercadopago-modal" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <form action="/api/bierclub/mercado_pago/pay" method="post" id="pay" name="pay" >
            <div class="modal-header">
                <h5 class="modal-title">Insira as informações do seu cartão de crédito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-4">
                <h5 class="mb-4">Caso queira, você pode fazer essa transação em dinheiro. Fale com o gerente</h5>
                <div class="container-fuild row">
                    <div class="col-md-4">
                        <label for="amount">Valor</label><br/>
                        <input id="amount" class="input money" name="amount" type="text" /><br/><br/>

                        <label for="email">Email</label><br/>
                        <input class="input" id="email" name="email" type="email" placeholder="your email"/><br/><br/>

                        <label for="cardNumber">Número do Cartão de Crédito:</label><br/>
                        <input type="text" id="cardNumber" class="input credit-card" data-checkout="cardNumber" placeholder="0000 0000 0000 0000" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off /><br/><br/>
                    </div>
                    <div class="col-md-4">
                        <label for="securityCode">Código de Segurança:</label><br/>
                        <input type="text" id="securityCode" class="input security-code" data-checkout="securityCode" placeholder="000" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off /><br/><br/>

                        <label for="cardExpirationMonth">Vence Mês:</label><br/>
                        <input type="text" class="input month" id="cardExpirationMonth" data-checkout="cardExpirationMonth" placeholder="12" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off /><br/><br/>

                        <label for="cardExpirationYear">Vence Ano:</label><br/>
                        <input type="text" class="input year" id="cardExpirationYear" data-checkout="cardExpirationYear" placeholder="2024" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off /><br/><br/>
                    </div>
                    <div class="col-md-4">
                        <label for="cardholderName">Nome do Propietário do Cartão:</label><br/>
                        <input class="input" type="text" id="cardholderName" data-checkout="cardholderName" placeholder="JOHN DOE" /><br/><br/>

                        <label for="docType">Tipo de Documento:</label><br/>
                        <select class="input" id="docType" data-checkout="docType"></select><br/><br/>

                        <label for="docNumber">Document number:</label><br/>
                        <input type="text" class="input cpf" id="docNumber" data-checkout="docNumber" placeholder="00000000000"/><br/><br/>
                    </div>
                </div>
                <input type="hidden" name="paymentMethodId" />
                <input type="hidden" name="bierclub_member_id" bind:value="{member.id}" />

                <div class="simple-keyboard"></div>

            </div>
            <div class="modal-footer">
                <input type="submit" value="Pay!" />
            </div>
        </form>
        </div>
    </div>
</div>
