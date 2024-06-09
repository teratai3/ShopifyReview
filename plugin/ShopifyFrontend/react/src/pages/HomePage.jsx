import React, { useEffect, useState, useCallback } from 'react';
import { Page, Card, Button } from '@shopify/polaris';
import useFetchData from '../hooks/useFetchData';
import ErrorComponent from '../components/Errors/ErrorComponent';
import { Link } from 'react-router-dom';
import ListComponent from '../components/ProductReviews/ListComponent';
import FilterComponent from '../components/ProductReviews/FilterComponent';

const HomePage = () => {
  const { data, error, fetchList } = useFetchData('/api/product_reviews');

  const [currentPage, setCurrentPage] = useState(1);
  const [query, setQuery] = useState({ title: '', status: '' });
  const perPage = 15;

  useEffect(() => {
    fetchList({ page: currentPage, perPage: perPage, title: query.title, status: query.status });
  }, [currentPage, query]);


  const handleSearch = useCallback((searchQuery) => {
    setQuery(searchQuery);
    setCurrentPage(1); // 検索時にページをリセット
  }, []);

  // console.log(query);

  const handleNextPage = () => {
    setCurrentPage((prevPage) => prevPage + 1);
  };

  const handlePreviousPage = () => {
    setCurrentPage((prevPage) => (prevPage > 1 ? prevPage - 1 : prevPage));
  };


  if (error) {
    return <ErrorComponent error={error} />;
  }

  return (
    <Page
      title="商品レビュー"
      primaryAction={<Link to="/product_reviews/create"><Button variant="primary">+ 新規追加</Button></Link>}
    >
      {data?.data && (
        <div className="mb20">
          <Card title="Fetched Data" sectioned>
            <div className='mb20'>
              <FilterComponent onSearch={handleSearch} />
            </div>
            <ListComponent
              items={data.data}
              hasNext={data.meta.hasNextPage}
              hasPrevious={currentPage > 1}
              onNext={handleNextPage}
              onPrevious={handlePreviousPage}
            />
          </Card>
        </div>
      )}
    </Page>
  );
};

export default HomePage;
