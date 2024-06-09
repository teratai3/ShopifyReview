import React from 'react';
import ErrorComponent from '../components/Errors/ErrorComponent';
import { Link } from 'react-router-dom';
import { Page, Card, Button } from '@shopify/polaris';
const NotFoundPage = () => {
  return (
    <ErrorComponent error={"お探しのページは存在しません。"} title="404 - ページが見つかりません">
      <div className='mt20'>
        <Card sectioned>
          <Button><Link to="/">トップページに戻る</Link></Button>
        </Card>
      </div>
    </ErrorComponent>
  );
}

export default NotFoundPage;
