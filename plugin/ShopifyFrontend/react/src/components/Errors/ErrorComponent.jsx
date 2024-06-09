import React from 'react';
import { Page, Banner } from '@shopify/polaris';

const ErrorComponent = ({ error, title = "Error", children }) => {
  return (
    <Page title={title}>
      <Banner status="critical">
        <p>{error}</p>
      </Banner>
      
      <div>{children}</div>
    </Page>
  );
};

export default ErrorComponent;