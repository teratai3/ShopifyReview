import React, { useEffect, useState } from 'react';
import useFetchData from '../../hooks/useFetchData';
import { Page, Card, Button, InlineError, Text } from '@shopify/polaris';
import { useNavigate } from 'react-router-dom';

const SettingsIndexPage = () => {
  const { data, error, addData } = useFetchData('/api/script_tags');

  const { data: assets, fetchList: fetchAssets } = useFetchData('/api/assets');

  useEffect(() => {
    fetchAssets({}, "/review");
  }, []);



  const [fieldErrors, setFieldErrors] = useState({});
  const navigate = useNavigate();

  const handleSubmit = async () => {
    setFieldErrors({});
    await addData();
  };

  const handleCopy = () => {
    navigator.clipboard.writeText(assets?.data)
      .then(() => { })
      .catch(() => {
        alert("コピーに失敗しました");
      });
  };


  useEffect(() => {
    console.log(data);
    console.log(error);
    if (!error && data?.id) {
      navigate('/settings', { state: { message: '初期化に成功しました', description: data?.message } });
    } else if (error?.messages) {
      setFieldErrors(error.messages);
    }
  }, [data, error]);



  return (
    <Page
      title="設定ページ"
    >
      <div className='mb20'>
        <Card sectioned>
          <Button onClick={handleSubmit}>設定を初期化する</Button>
          {fieldErrors?.error && (
            <div className='mt15'>
              <InlineError message={fieldErrors.error} />
            </div>
          )}
        </Card>
      </div>

      {assets?.data && (
        <div className="mb20">
          <Card title="Fetched Data" sectioned>
            <div className='mb10'>
              <Text as="h3" fontWeight="bold">貼り付けhtml<br />(製品詳細に貼り付けてください)</Text>
              <InlineError message={"※Asset APIから更新、新規追加の規制が厳しくなっため、htmlをそのままLiquidファイルに貼り付けて、カスタマイズしてください"} />
            </div>
            <div className='mb30'>
              <Button onClick={handleCopy}>コピーする</Button>
            </div>
            <pre>
              {assets.data}
            </pre>
          </Card>
        </div>
      )}
    </Page>
  );
};

export default SettingsIndexPage;
