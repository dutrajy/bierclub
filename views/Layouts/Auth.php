<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, shrink-to-fit=no">

    <title><?= $title ?? "Commercial Base Project" ?></title>
    <meta name="description" content="<?= $description ?? "Commercial Base Project" ?>">

    <link rel="stylesheet" href="/styles/fontawesome.min.css">
    <link rel="stylesheet" href="/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/styles/simple-keyboard.css">
    <link rel="stylesheet" href="/styles/main.css">

    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="icon" type="image/png" href="/images/favicon.png">
</head>
<body>

    <main class="container-fluid h-100">
        <div class="row h-100">
            <?php $block("content") ?>
            <div class="col-md-4 mx-auto w-100">
                <h1>Default</h1>
            </div>
            <?php $block("content") ?>
        </div>
    </main>


    <div class="simple-keyboard" style="position: fixed; bottom: 0; left: 0; width: 100%"></div>

    <script src="/scripts/jquery.min.js"></script>
    <script src="/scripts/popper.min.js"></script>
    <script src="/scripts/bootstrap.min.js"></script>
    <script src="/scripts/simple-keyboard.js"></script>
    <script>
        let Keyboard = window.SimpleKeyboard.default;

        // Here we'll store the input id that simple-keyboard will be using.
        var selectedInput;

        // Initialize simple-keyboard as usual
        var keyboard = new Keyboard({
            onChange: input => onChange(input),
            onKeyPress: button => onKeyPress(button)
        });


        /**
        * When an input is focused, it will be marked as selected (selectedInput)
        * This is so we can replace it's value on the onChange function
        *
        * Also, we will set the inputName option to a unique string identifying the input (id)
        * simple-keyboard save the input in this key and report changes through onChange
        */
        onInputFocus = event => {
        // Setting input as selected
        selectedInput = `#${event.target.id}`;

        // Set the inputName option on the fly !
        keyboard.setOptions({
            inputName: event.target.id
        });
        }

        // When the current input is changed, this is called
        function onChange(input) {
            // If the input is not defined, grabbing the first ".input".
            let currentInput = selectedInput || '.input';

            // Updating the selected input's value
            document.querySelector(currentInput).value = input;
        }

        function onKeyPress(button) {
            /**
             * If you want to handle the shift and caps lock buttons
             */
            if (button === "{shift}" || button === "{lock}") handleShift();
        }

        // Add an event listener for the inputs to be tracked
        document.querySelectorAll('.input')
        .forEach(input => input.addEventListener('focus', onInputFocus));


        function handleShift() {
            let currentLayout = keyboard.options.layoutName;
            let shiftToggle = currentLayout === "default" ? "shift" : "default";

            keyboard.setOptions({
                layoutName: shiftToggle
            });
        }

    </script>
</body>
</html>
