import { Banner } from '@shopify/polaris';
import React, { useState } from 'react';

const MessageComponent = ({ message, description = '',tone = 'success' }) => {
    const [visible, setVisible] = useState(true);

    if (!visible) return null;

    return (
        <Banner
            title={message}
            tone={tone}
            onDismiss={() => setVisible(false)}
        >
            {description}
        </Banner>
    );
};

export default MessageComponent;