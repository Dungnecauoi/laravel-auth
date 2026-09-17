import { useForm } from '@inertiajs/react';
import { Button, Checkbox, Form, Input, Typography } from 'antd';
import AdminLayout from '@/Layouts/AdminLayout';

interface Role {
    id: number;
    name: string;
    label: string | null;
}

interface UserData {
    id: number;
    name: string;
    email: string;
    role_ids: number[];
}

export default function Edit({ user, roles }: { user: UserData; roles: Role[] }) {
    const { data, setData, put, processing, errors } = useForm({
        name: user.name,
        email: user.email,
        password: '',
        password_confirmation: '',
        roles: user.role_ids,
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        put(`/admin/users/${user.id}`);
    }

    return (
        <AdminLayout>
            <Typography.Title level={2}>Sửa người dùng</Typography.Title>
            <form onSubmit={submit} style={{ maxWidth: 480 }}>
                <Form.Item label="Họ tên" validateStatus={errors.name ? 'error' : ''} help={errors.name}>
                    <Input value={data.name} onChange={(e) => setData('name', e.target.value)} autoFocus />
                </Form.Item>
                <Form.Item label="Email" validateStatus={errors.email ? 'error' : ''} help={errors.email}>
                    <Input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} />
                </Form.Item>
                <Form.Item
                    label="Mật khẩu"
                    validateStatus={errors.password ? 'error' : ''}
                    help={errors.password ?? 'để trống nếu không đổi'}
                >
                    <Input.Password value={data.password} onChange={(e) => setData('password', e.target.value)} />
                </Form.Item>
                <Form.Item label="Xác nhận mật khẩu">
                    <Input.Password
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                    />
                </Form.Item>
                <Form.Item label="Vai trò">
                    {roles.length === 0 ? (
                        <Typography.Text type="secondary">Chưa có vai trò nào — tạo ở mục Vai trò.</Typography.Text>
                    ) : (
                        <Checkbox.Group
                            options={roles.map((r) => ({ label: r.label ?? r.name, value: r.id }))}
                            value={data.roles}
                            onChange={(checked) => setData('roles', checked as number[])}
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
