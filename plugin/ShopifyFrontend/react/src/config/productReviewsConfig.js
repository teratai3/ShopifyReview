export const STATUS_OPTIONS = [
    { label: '選択してください', value: '' },
    { label: '保留中', value: 'pending' },
    { label: '公開', value: 'publish' },
];

export const STATUS_LABELS = {
    pending: '保留中',
    publish: '公開',
};

export const getStatusLabel = (status) => {
    return STATUS_LABELS[status] || '不明なステータス';
};