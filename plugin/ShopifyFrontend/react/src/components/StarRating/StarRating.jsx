import React, { useState } from 'react';
import PropTypes from 'prop-types';
import './StarRating.css';

const StarRating = ({ count = 5, value = 0, onChange }) => {
    const [hoverValue, setHoverValue] = useState(undefined); // ホバー中の星の状態を保持
    const stars = Array.from({ length: count }, (v, i) => i + 1);

    const handleClick = (newValue) => {
        onChange(newValue === value ? 0 : newValue);
    };

    const handleMouseOver = (newHoverValue) => {
        setHoverValue(newHoverValue); // ホバー中の星の値を設定
    };

    const handleMouseOut = () => {
        setHoverValue(undefined); // ホバー状態をクリア
    };

    return (
        <div className="star-rating">
            {stars.map((star) => (
                <svg
                    key={star}
                    className={`star ${star <= value ? 'filled' : ''}`}
                    onClick={() => handleClick(star)}
                    onMouseOver={() => handleMouseOver(star)}
                    onMouseOut={handleMouseOut}
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    fill={star <= (hoverValue || value) ? '#ffd700' : '#d3d3d3'}
                >
                    <path d="M12 .587l3.668 7.568L24 9.748l-6 5.857 1.415 8.23L12 18.897l-7.415 4.938L6 15.605 0 9.748l8.332-1.593z"/>
                </svg>
            ))}
        </div>
    );
};

StarRating.propTypes = {
    count: PropTypes.number,
    value: PropTypes.number,
    onChange: PropTypes.func.isRequired,
};


export default StarRating;
