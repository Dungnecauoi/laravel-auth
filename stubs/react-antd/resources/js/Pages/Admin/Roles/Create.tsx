import { useForm } from '@inertiajs/react';
import { Button, Checkbox, Form, Input, Typography } from 'antd';
import AdminLayout from '@/Layouts/AdminLayout';

interface Permission {
    id: number;
    name: string;
    label: string | null;
}

export default function Create({ permissions }: { permissions: Permission[] }) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        label: '',
        permissions: [] as number[],
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/admin/roles');
    }

    return (
        <AdminLayout>
            <Typography.Title level={2}>Thêm vai trò</Typography.Title>
            <form onSubmit={submit} style={{ maxWidth: 480 }}>
                <Form.Item label="Tên" validateStatus={errors.name ? 'error' : ''} help={errors.name}>
                    <Input value={data.name} onChange={(e) => setData('name', e.target.value)} autoFocus />
                </Form.Item>
                <Form.Item label="Nhãn hiển thị" validateStatus={errors.label ? 'error' : ''} help={errors.label}>
                    <Input value={data.label} onChange={(e) => setData('label', e.target.value)} />
                </Form.Item>
                <Form.Item label="Quyền">
                    {permissions.length === 0 ? (
                        <Typography.Text type="secondary">Chưa có quyền nào — tạo ở mục Quyền.</Typography.Text>
                    ) : (
                        <Checkbox.Group
                            options={permissions.map((p) => ({ label: p.label ?? p.name, value: p.id }))}
                            value={data.permissions}
                            onChange={(checked) => setData('permissions', checked as number[])}
                        />
                    )}
                </Form.Item>
                <Button type="primary" htmlType="submit" loading={processing}>
                    Lưu
                </Button>
            </form>
        </AdminLayout>
    );
}
