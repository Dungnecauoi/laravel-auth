import { useForm } from '@inertiajs/react';
import { Button, Form, Input, Typography } from 'antd';
import AdminLayout from '@/Layouts/AdminLayout';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({ name: '', label: '' });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/admin/permissions');
    }

    return (
        <AdminLayout>
            <Typography.Title level={2}>Thêm quyền</Typography.Title>
            <form onSubmit={submit} style={{ maxWidth: 480 }}>
                <Form.Item label="Tên" validateStatus={errors.name ? 'error' : ''} help={errors.name}>
                    <Input value={data.name} onChange={(e) => setData('name', e.target.value)} autoFocus />
                </Form.Item>
                <Form.Item label="Nhãn hiển thị" validateStatus={errors.label ? 'error' : ''} help={errors.label}>
                    <Input value={data.label} onChange={(e) => setData('label', e.target.value)} />
                </Form.Item>
                <Button type="primary" htmlType="submit" loading={processing}>
                    Lưu
                </Button>
            </form>
        </AdminLayout>
    );
}
