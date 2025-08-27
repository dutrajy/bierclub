<script>
    let products = [];
    let member = {};
    let futureBalance;

    $: cart = products.filter((p) => p.quantity >= 1);
    $: total = cart.reduce((total, p) => total + p.quantity * p.price, 0);
    $: futureBalance = member.card_balance - total;

    fetch("/api/products")
        .then((response) => response.json())
        .then((data) => products = data);


    fetch("/api/bierclub/member")
        .then((response) => response.json())
        .then((data) => member = data);

    function money(value) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value)
    }

    function addToCart(i) {
        if (futureBalance >= products[i].price) {
            products[i].quantity = products[i].quantity ? products[i].quantity + 1 : 1;
        } else {
            alert("Saldo insuficiente!");
        }
    }

    function sendSale() {
        fetch("/api/bierclub/buy", {method: "POST", body: JSON.stringify({cart, member})})
            .then(function (response) { return response.json(); })
            .then(function(data) {
                alert(data.msg);

                fetch("/api/bierclub/member")
                    .then((response) => response.json())
                    .then((data) => {
                        member = data;

                        products.forEach(p => p.quantity = 0);
                        products = products;
                    });
            });
    }

    function cancelSale() {
        products.forEach(p => p.quantity = 0);
        products = products;
    }
</script>

<!-- Page -->
<div class="row no-gutters vh-100">

<!-- Side -->
<div class="col-md-5 col-lg-4 border-right d-flex flex-column mh-100">
    <!-- Client -->
    <div class="row no-gutters border-bottom pb-2">

        <!-- Client Picture -->
        <div class="col-sm-5 p-1">
            <img src="/images/uploads/{ member.image }" class="img-fluid" alt="Responsive image">
        </div><!-- /Client Picture -->

        <!-- Client Details -->
        <div class="col-md-7 pr-1 pt-1 clearfix">
            <div class="clearfix">
                <div class="font-weight-bold text-truncate">{ member.full_name }</div>
                <div class="text-success float-right">{ money(member.card_balance) }</div>
            </div>
            <div class="small clearfix">
                <div>Última Recarga:</div>
                <div class="text-info float-right">2019-06-30 16:54</div>

            </div>
            <div class="small clearfix">
                <div>Última Compra:</div>
                <div class="text-info float-right">2019-07-02 20:32</div>
            </div>
        </div><!-- /Client Details-->

    </div><!-- /Client -->

    <!-- Cart -->
    <div class="overflow-auto flex-grow-1 bg-light py-0">
        <ul class="list-group list-group-flush border-bottom">
        {#each cart as product}
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="btn-group pr-3">
                    <button class="btn btn-sm btn-danger" on:click="{ () => product.quantity-- }">-</button>
                    <button class="btn btn-sm btn-primary" on:click="{ () => product.quantity++ }">+</button>
                </div>
                <span class="flex-fill">{ product.quantity } x - { product.name }</span>
                <span>{ money(product.quantity * product.price) }</span>
            </li>
        {/each}
        </ul>
    </div><!-- /Cart -->

    <div class="footer border-top py-3 px-1">
            <button class="btn btn-danger float-left mr-2" on:click="{cancelSale}">Cancelar</button>
            <span class="text-success h2 ml-2">Total: { money(total) }</span>
            <button class="btn btn-primary float-right mr-2" on:click="{sendSale}">Pagar</button>
    </div>
</div><!-- /Side -->

<!-- Products -->
<div class="col-md-7 col-lg-8 d-flex flex-row mh-100">
    <div class="row align-middle pl-3 pr-2 mx-0">
        <div class="my-auto h3">
            <i class="fas fa-chevron-left"></i>
        </div>
    </div>

    <!-- Products Cards -->
    <div class="row no-gutters flex-grow-1 mx-1 pt-1 pb-3 overflow-auto mh-100 align-self-center">
        {#each products as product, index}
        <div class="col-sm-6 col-md-3 px-1 pt-2" on:click="{() => addToCart(index)}">
            <div class="card">
                <div class="card-body p-2">
                    <img src="/images/uploads/{product.image}" class="img-fluid" alt="Responsive image">
                    <p class="font-weight-bold text-center pt-2 mb-2">{ product.name }</p>
                    <div class="small text-success float-right mb-0">{ money(product.price) }</div>
                </div>
            </div>
        </div>
        {/each}
    </div><!-- /Products Cards -->

    <div class="row align-middle pr-3 pl-2 mx-0">
        <div class="my-auto h3">
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
</div>
<!-- /Products -->
</div><!-- /Page -->
