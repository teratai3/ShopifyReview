import React from 'react';
import { Outlet, useLocation } from 'react-router-dom';
import MessageComponent from './components/MessageDisplay/MessageComponent';
import NavigationAside from './components/Common/NavigationAside';
import { Page } from '@shopify/polaris';
import './Layout.css';

const Layout = () => {
    const location = useLocation();
    const { state } = location;
    const message = state?.message;
    const description = state?.description;
    const tone = state?.tone || 'success'; // デフォルトトーンを'success'に設定

    return (
        <>
            <div className='layout-container'>
                <aside className="layout-aside">
                    <NavigationAside />
                </aside>
                <main className="layout-main">
                    <Page>
                        {message && <MessageComponent message={message} description={description} tone={tone} />}
                    </Page>
                    <Outlet />
                </main>
            </div>
        </>
    );
};

export default Layout;