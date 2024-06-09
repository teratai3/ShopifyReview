import React, { createContext, useContext } from 'react';

const AuthFetchContext = createContext();

export const AuthFetchProvider = ({ fetchFunction, children }) => (
    <AuthFetchContext.Provider value={fetchFunction}>
        {children}
    </AuthFetchContext.Provider>
);

export const useAuthFetch = () => {
    const context = useContext(AuthFetchContext);
    if (context === undefined) {
        throw new Error('useFetch は AuthFetchProvider 内で使用する必要があります');
    }
    return context;
};