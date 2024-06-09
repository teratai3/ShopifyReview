import React, { useState, useCallback } from 'react';
import { Frame, Navigation, Button } from '@shopify/polaris';
import './NavigationAside.css';
// import { useNavigate } from 'react-router-dom';
import { Link } from 'react-router-dom';
const NavigationAside = () => {
  const [mobileNavigationActive, setMobileNavigationActive] = useState(false);
  // const navigate = useNavigate();
  const toggleMobileNavigationActive = useCallback(() => {
    setMobileNavigationActive((mobileNavigationActive) => !mobileNavigationActive);
  }, []);


  const navigationItems = [
    {
      url: '/',
      label: 'ホーム',
    },
    {
      url: '/settings',
      label: '設定',
    },
  ];

  const navigationMarkup = (
    <Navigation location="/">
      <Navigation.Section
        items={navigationItems.map((item, index) => ({
          key: item.url, // ユニークなキーを設定
          label: <Link to={item.url}>{item.label}</Link>,
        }))}
      />
    </Navigation>
  );

  return (
    <>
      <div className="mobile-menu-button">
        <Button onClick={toggleMobileNavigationActive}>Menu</Button>
      </div>
      <div className={mobileNavigationActive ? 'frame-container active' : 'frame-container'}>
        <Frame
          navigation={navigationMarkup}
          showMobileNavigation={mobileNavigationActive}
          onNavigationDismiss={toggleMobileNavigationActive}
        >
        </Frame>
      </div>
    </>

  );
};

export default NavigationAside;
