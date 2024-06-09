import React, { useState, useCallback } from 'react';
import { TextField, FormLayout, Button, Select } from '@shopify/polaris';
import { STATUS_OPTIONS } from '../../config/productReviewsConfig';
const FilterComponent = ({ onSearch }) => {
    // const [query, setQuery] = useState('');
    // const [status, setStatus] = useState('');
    const [query, setQuery] = useState({ title: '', status: '' });

    const handleSearch = () => {
        onSearch(query);
    };


    const handleChange = (field) => (value) => {
        setQuery((prevState) => ({
            ...prevState,
            [field]: value
        }));
    };


    return (
        <FormLayout>
            <FormLayout.Group>
                <TextField
                    value={query.title}
                    onChange={handleChange("title")}
                    placeholder="検索したいタイトルを入力してください"
                />
                <Select
                    options={STATUS_OPTIONS}
                    onChange={handleChange("status")}
                    value={query.status}
                />
                <div style={{ paddingLeft: '8px' }}>
                    <Button onClick={handleSearch}>検索</Button>
                </div>
            </FormLayout.Group>
        </FormLayout>
    );
};

export default FilterComponent;
