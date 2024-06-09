import React, { useEffect, useState } from 'react';
import { createRoot } from 'react-dom/client';
import App from './App';
import { AppProvider } from '@shopify/polaris';
import '@shopify/polaris/build/esm/styles.css';
import createApp from '@shopify/app-bridge';
import { authenticatedFetch } from '@shopify/app-bridge-utils';
import { AuthFetchProvider } from './utils/AuthFetchContext';
import ErrorComponent from './components/Errors/ErrorComponent';
import './styles/global.css';

const RootComponent = () => {
  const [shopifyApp, setShopifyApp] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    const { apiKey, shopOrigin, host } = window.shopifyAppConfig;
    
    if (!apiKey || !shopOrigin || !host) {
      setError('必須の構成フィールドが欠落しています。 apiKey、shopOrigin、および host は必須です。');
      return;
    }


    const app = createApp({
      apiKey,
      shopOrigin,
      host,
      forceRedirect: true,
    });

    

    setShopifyApp(app);
  }, []);

  if (error) {
    return (
      <AppProvider i18n={{}}>
        <ErrorComponent error={error} />
      </AppProvider>
    );
  }

  if (!shopifyApp) {
    return <div>Loading...</div>;
  }

  const fetchFunction = authenticatedFetch(shopifyApp);

  return (
    <React.StrictMode>
      <AppProvider i18n={{}}>
          <AuthFetchProvider fetchFunction={fetchFunction}>
            <App />
          </AuthFetchProvider>
      </AppProvider>
    </React.StrictMode>
  );
};

// アプリケーションコンテナを取得し、ルートを作成
const container = document.getElementById('app');
const root = createRoot(container);

root.render(<RootComponent />);
