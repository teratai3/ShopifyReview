<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shopify</title>
    <!-- 
        https://shopify.dev/docs/api/app-bridge/migration-guide
        v4から Providerが必要なくなる
     -->
    <meta name="shopify-api-key" content="<?= esc($apiKey) ?>" />
    <script src="https://cdn.shopify.com/shopifycloud/app-bridge.js"></script>
</head>

<body>
    <div id="app">
    </div>
    <script>
        window.shopifyAppConfig = {
            apiKey: '<?= esc($apiKey) ?>',
            shopOrigin: '<?= esc($shopOrigin) ?>',
            host: '<?= esc($host) ?>'
        };
    </script>
     <script src="<?= base_url("shopify_frontend/dist/main.js",env("CI_ENVIRONMENT") === "development" ? "https://localhost/" : ""); ?>"></script>
</body>
</html>