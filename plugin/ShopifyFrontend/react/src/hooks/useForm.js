import { useState } from 'react';

const useForm = (initialState) => {
    const [formState, setFormState] = useState(initialState);

    const handleChange = (field) => (value) => {
        setFormState((prevState) => ({
            ...prevState,
            [field]: value
        }));
    };

    return [formState, handleChange, setFormState];
};

export default useForm;