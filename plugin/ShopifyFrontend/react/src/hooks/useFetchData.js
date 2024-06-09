import { useState } from 'react';
import { useAuthFetch } from '../utils/AuthFetchContext';

const useFetchData = (baseUrl) => {
  const [data, setData] = useState(null);
  const [error, setError] = useState(null);
  const fetchFunction = useAuthFetch();

  const fetchList = async (query = {}, endpoint = '') => {
    try {
      const queryString = new URLSearchParams(query).toString();
      const fullUrl = queryString ? `${baseUrl}?${queryString}` : baseUrl;

      const response = await fetchFunction(fullUrl + endpoint);
      if (!response.ok) {
        const errorData = await response.json();
        throw errorData;
      }
      const result = await response.json();
      setError(null);
      setData(result);
    } catch (error) {
      setError(error);
      setData(null);
    }
  };

  const fetchShow = async (id, endpoint = 'show') => {
    try {
      const fullUrl = `${baseUrl}/${endpoint}/${id}`;

      const response = await fetchFunction(fullUrl);

      if (!response.ok) {
        const errorData = await response.json();
        throw errorData;
      }

      const result = await response.json();
      setError(null);
      setData(result);
    } catch (error) {
      setError(error);
      setData(null);
    }
  };

  const addData = async (newItem, endpoint = 'create') => {
    try {
      const response = await fetchFunction(`${baseUrl}/${endpoint}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(newItem),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw errorData;
      }

      const result = await response.json();
      setError(null);
      setData(result);
    } catch (error) {
      setError(error);
      setData(null);
    }
  };

  const updateData = async (id, updatedItem, endpoint = 'update') => {
    try {
      const response = await fetchFunction(`${baseUrl}/${endpoint}/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(updatedItem),
      });

      if (!response.ok) {
        const errorData = await response.json();

        throw errorData;
      }

      const result = await response.json();
      setError(null);
      setData(result);

    } catch (error) {
      setError(error);
      setData(null);
    }
  };

  const deleteData = async (id, endpoint = 'delete') => {
    try {
      const response = await fetchFunction(`${baseUrl}/${endpoint}/${id}`, {
        method: 'DELETE',
      });
      if (!response.ok) {
        const errorData = await response.json();
        throw errorData;
      }
      const result = await response.json();
      setError(null);
      setData(result);
    } catch (error) {
      setError(error);
      setData(null);
    }
  };

  return { data, error, fetchList, fetchShow, addData, updateData, deleteData };
};

export default useFetchData;
