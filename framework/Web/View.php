<?php

namespace Commercial\Framework\Web;

class View
{
    const VIEWS_PATH = __DIR__ . "/../../views/";

    // Temporary render
    public static function render($path, $data = [])
    {
        $_blocks = [];
        $_lastBlockName = null;
        $layout = "Default";

        $money = function($value) {
            return "R$ " . number_format($value, 2, ",", ".");
        };

        $block = function ($name) use (&$_blocks, &$_lastBlockName) {

            // When start the block there's no index in the array
            if (!isset($_blocks[$name])) {
                ob_start(); // Start to record the block
                $_blocks[$name] = "";

            // When close the block there's an empty string in teh associated index
            } elseif ($_blocks[$name] === "") {
                $_blocks[$name] = \ob_get_contents(); // Stop recording and save on associated index
                ob_end_clean(); // Clean the output

            // If there is a non empty block start the recording and doesn't override
            } elseif ($_lastBlockName === null) {
                \ob_start();
                $_lastBlockName = $name;

            // Use last block name to clean the buffer and echo the the already saved block
            } else {
                \ob_end_clean();
                $_lastBlockName = null;
                echo $_blocks[$name];
            }
        };

        $component = function ($name, $data = []) {
            \extract($data);
            require_once(static::VIEWS_PATH . "Components/" . $name . ".php");
        };

        \extract($data);

        // Load Blocks and contents from view
        \ob_start();
        require_once(static::VIEWS_PATH . $path . ".php");
        \ob_end_clean();

        // Use layout and replace blocks
        \ob_start();
        require_once(static::VIEWS_PATH . "Layouts/" . $layout . ".php");
        $result = \ob_get_contents();
        \ob_end_clean();

        return $result;
    }
}
