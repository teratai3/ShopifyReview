import React from 'react';

const StarRatingView = ({ count }) => {
    const stars = [];
    for (let i = 0; i < 5; i++) {
        stars.push(
            <span key={i} style={{ color: i < count ? 'gold' : 'grey' }}>★</span>
        );
    }
    return <div>{stars}</div>;
};

export default StarRatingView;
