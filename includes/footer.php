<?php

class Footer {
    // Method to output the footer HTML section
    public static function renderFooter() {
        echo '
        <footer>
            <p>&copy; ' . self::getYear() . ' ShopMaster. All Rights Reserved.</p>
        </footer>
        <style>
            footer {
                background-color: #023336;
                color: white;
                text-align: center;
                padding: 15px 0;
                font-size: 14px;
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
            }

            footer p {
                margin: 0;
                letter-spacing: 1px;
            }
        </style>';
    }

    // Method to get the current year
    private static function getYear() {
        return date("Y"); // Returns the current year
    }
}

// Render the footer
Footer::renderFooter();
?>
