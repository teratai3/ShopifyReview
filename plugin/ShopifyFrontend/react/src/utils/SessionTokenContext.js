import React, { createContext, useContext, useEffect, useState } from 'react';
import { getSessionToken } from '@shopify/app-bridge-utils';

const SessionTokenContext = createContext();

export const SessionTokenProvider = ({ app, children }) => {
  const [sessionToken, setSessionToken] = useState(null);

  useEffect(() => {
    getSessionToken(app)
      .then(setSessionToken)
      .catch(console.error);
  }, [app]);

  return (
    <SessionTokenContext.Provider value={sessionToken}>
      {children}
    </SessionTokenContext.Provider>
  );
};

export const useSessionToken = () => useContext(SessionTokenContext);
