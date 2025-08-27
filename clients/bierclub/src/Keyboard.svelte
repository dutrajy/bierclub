<script>
    export let text = "";

    function add(letter) {
        text += letter.toUpperCase();
    }

    function removeLetter()
    {
        text = text.slice(0, -1);
    }

</script>

<style>
    div {
        font-family: monospace;
    }
    input {
        font-size: 1rem !important;
    }
    button {
        font-size: 1rem !important;
        padding: 0.5rem 0.8rem;
    }
</style>

<div class="row">
    <button type="button" class="btn btn-lg btn-outline-dark" on:click={() => add(".")}>.</button>
    <button type="button" class="btn btn-lg btn-outline-dark ml-2" on:click={() => add("7")}>7</button>
    <button type="button" class="btn btn-lg btn-outline-dark ml-2" on:click={() => add("8")}>8</button>
    <button type="button" class="btn btn-lg btn-outline-dark ml-2" on:click={() => add("9")}>9</button>
</div>
<div class="row mt-2">
    <button type="button" class="btn btn-lg btn-outline-dark" on:click={() => add(",")}>,</button>
    <button type="button" class="btn btn-lg btn-outline-dark ml-2" on:click={() => add("4")}>4</button>
    <button type="button" class="btn btn-lg btn-outline-dark ml-2" on:click={() => add("5")}>5</button>
    <button type="button" class="btn btn-lg btn-outline-dark ml-2" on:click={() => add("6")}>6</button>
</div>
<div class="row mt-2">
    <button type="button" class="btn btn-lg btn-outline-dark" on:click={() => add("0")}>0</button>
    <button type="button" class="btn btn-lg btn-outline-dark ml-2" on:click={() => add("1")}>1</button>
    <button type="button" class="btn btn-lg btn-outline-dark ml-2" on:click={() => add("2")}>2</button>
    <button type="button" class="btn btn-lg btn-outline-dark ml-2" on:click={() => add("3")}>3</button>
</div>
<div class="row">
    <button type="button" class="btn btn-lg btn-outline-dark my-2" on:click={removeLetter}><i class="fas fa-backspace"></i></button>
</div>
