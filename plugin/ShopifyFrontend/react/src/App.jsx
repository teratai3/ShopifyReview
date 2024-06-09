import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import HomePage from './pages/HomePage';
import NotFoundPage from './pages/NotFoundPage';
import ProductReviewsCreatePage from './pages/ProductReviews/ProductReviewsCreatePage';
import ProductReviewsShowPage from './pages/ProductReviews/ProductReviewsShowPage';
import ProductReviewsDeletePage from './pages/ProductReviews/ProductReviewsDeletePage';
import SettingsIndexPage from './pages/Settings/SettingsIndexPage';

import Layout from './Layout';

const App = () => {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Layout />}>
          <Route index element={<HomePage />} />
          <Route path="/product_reviews/show/:id" element={<ProductReviewsShowPage />} />
          <Route path="/product_reviews/create" element={<ProductReviewsCreatePage />} />
          <Route path="/product_reviews/delete/:id" element={<ProductReviewsDeletePage />} />
          <Route path="/settings" element={<SettingsIndexPage />} />
          <Route path="*" element={<NotFoundPage />} />
        </Route>
      </Routes>
    </Router>
  );
}
export default App;