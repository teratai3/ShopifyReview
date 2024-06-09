import React, { useEffect, useState } from 'react';
import useFetchData from '../../hooks/useFetchData';
import { Page, Card, Button, FormLayout, TextField, InlineError, Select } from '@shopify/polaris';
import { useNavigate } from 'react-router-dom';
import { STATUS_OPTIONS } from '../../config/productReviewsConfig';
import useForm from '../../hooks/useForm';
import StarRating from '../../components/StarRating/StarRating';

const ProductReviewsCreatePage = () => {

    const [formState, handleChange, setFormState] = useForm({
        product_id: '',
        name: '',
        title: '',
        description: '',
        status: '',
        recommend_level: ''
    });

    const [fieldErrors, setFieldErrors] = useState({});
    const { data, error, addData } = useFetchData('/api/product_reviews');

    const { data: products, error: productsError, fetchList: fetchProducts } = useFetchData('/api/products');

    const navigate = useNavigate();

    const handleSubmit = async () => {
        setFieldErrors({});
        await addData({
            product_id: formState.product_id,
            name: formState.name,
            title: formState.title,
            description: formState.description,
            status: formState.status,
            recommend_level: formState.recommend_level
        });
    };


    // 非同期処理後にerrorの更新を待機するためにuseEffectを使用
    useEffect(() => {
        if (!error && data?.id) {
            navigate('/', { state: { message: '新規追加に成功しました', description: data?.message } });
        } else if (error?.messages) {
            console.log(error.messages);
            setFieldErrors(error.messages);
        }

    }, [data, error]);


    useEffect(() => {
        fetchProducts();
    }, []);

    useEffect(() => {
        console.log(products);
    }, [products]);

    const productOptions = [
        { label: '選択してください', value: '' },
        ...products?.data?.map((product) => ({
            label: product.title,
            value: product.id.toString(),
        })) || []
    ];

    return (
        <Page
            backAction={{ content: 'Settings', onAction: () => navigate('/') }}
            title="商品レビュー新規作成"
        >
            <Card>
                <FormLayout>
                    {fieldErrors?.error && (
                        <InlineError message={fieldErrors.error} />
                    )}


                    <Select
                        label="製品"
                        options={productOptions}
                        onChange={handleChange('product_id')}
                        value={formState.product_id}
                    />
                    {fieldErrors?.product_id && (
                        <InlineError message={fieldErrors.product_id} />
                    )}

                    <TextField label="ユーザー名" value={formState.name} onChange={handleChange('name')} />
                    {fieldErrors?.title && (
                        <InlineError message={fieldErrors.name} />
                    )}

                    <TextField label="タイトル" value={formState.title} onChange={handleChange('title')} />
                    {fieldErrors?.title && (
                        <InlineError message={fieldErrors.title} />
                    )}

                    <TextField
                        label="コメント"
                        value={formState.description}
                        onChange={handleChange('description')}
                        multiline={4}
                    />

                    {fieldErrors?.description && (
                        <InlineError message={fieldErrors.description} />
                    )}

                    <Select
                        label="ステータス"
                        options={STATUS_OPTIONS}
                        onChange={handleChange('status')}
                        value={formState.status}
                    />

                    {fieldErrors?.status && (
                        <InlineError message={fieldErrors.status} />
                    )}

                    <div className='mb15'>
                        <label className='mb5 d-block'>評価</label>
                        <StarRating
                            count={5}
                            value={Number(formState.recommend_level)}
                            onChange={handleChange('recommend_level')}
                        />
                    </div>

                    <Button onClick={handleSubmit}>登録</Button>
                </FormLayout>
            </Card>
        </Page>
    );
};

export default ProductReviewsCreatePage;