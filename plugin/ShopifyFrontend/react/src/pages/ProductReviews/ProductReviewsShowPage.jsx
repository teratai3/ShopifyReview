import React, { useEffect, useState } from 'react';
import useFetchData from '../../hooks/useFetchData';
import { Page, Card, Button, FormLayout, TextField, InlineError, Select, Text } from '@shopify/polaris';
import { useParams, useNavigate } from 'react-router-dom';
import { STATUS_OPTIONS } from '../../config/productReviewsConfig';
import useForm from '../../hooks/useForm';
import StarRating from '../../components/StarRating/StarRating';

const ProductReviewsShowPage = () => {
    const { id } = useParams();

    const [formState, handleChange, setFormState] = useForm({
        product_id: '',
        name: '',
        title: '',
        description: '',
        status: '',
        recommend_level: '',
        updated_at: '',
    });

    const [fieldErrors, setFieldErrors] = useState({});

    const { data, error, updateData, fetchShow } = useFetchData('/api/product_reviews');
    const { data: products, error: productsError, fetchList: fetchProducts } = useFetchData('/api/products');


    const navigate = useNavigate();

    const handleSubmit = async () => {
        setFieldErrors({});
        await updateData(id, {
            product_id: formState.product_id,
            name: formState.name,
            title: formState.title,
            description: formState.description,
            status: formState.status,
            recommend_level: formState.recommend_level
        });
    };

    useEffect(() => {
        fetchProducts();
        fetchShow(id);
    }, []);


    // データ取得後にフォームの状態を更新
    useEffect(() => {
        if (data?.data) {
            setFormState({
                product_id: data.data.product_id,
                name: data.data.name,
                title: data.data.title,
                description: data.data.description,
                status: data.data.status,
                recommend_level: data.data.recommend_level,
                created_at: data.data.created_at,
                updated_at: data.data.updated_at
            });
        }
    }, [data, error]);


    // 非同期処理後にerrorの更新を待機するためにuseEffectを使用
    useEffect(() => {
        console.log(error);
        console.log(data);
        if (!error && data?.id) {
            navigate('/', { state: { message: '更新に成功しました', description: data?.message } });
        } else if (error?.messages) {
            if (error?.status == 404) {
                navigate('/', { state: { message: '見つかりませんでした', description: error.messages.error, tone: "critical" } });
            }
            setFieldErrors(error.messages);
        }
    }, [data, error]);


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
            title="商品レビュー編集"
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

                    <Text as="p">
                        作成日：{formState.created_at}<br />
                        更新日：{formState.updated_at}
                    </Text>

                    <Button onClick={handleSubmit}>更新</Button>
                </FormLayout>
            </Card>
        </Page>
    );
};

export default ProductReviewsShowPage;