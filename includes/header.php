<?php
class Header {
    // Method to output the HTML head section with metadata, title, and included assets
    public static function renderHead() {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . self::getTitle() . '</title>
            <script src="../assets/js/script.js" defer></script>
            <link rel="icon" href="../assets/images/favicon.png" type="image/png">
            <link rel="stylesheet" href="../assets/css/styles.css">  
        </head>
        <body>';
    }

    // Method to output the header section of the page
    public static function renderHeader() {
        echo '<header>
                <h1>' . self::getTitle() . '</h1>
              </header>';
    }

    // Method to get the page title
    private static function getTitle() {
        return 'ShopMaster System';
    }
}

// Render the head and header
Header::renderHead();
Header::renderHeader();

?>




