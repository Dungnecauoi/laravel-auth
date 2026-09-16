import { Head, useForm } from '@inertiajs/react';
import { Button, Form, Input } from 'antd';
import AuthLayout from '@/Layouts/AuthLayout';

export default function ResetPassword({ email, token }: { email: string | null; token: string }) {
    const { data, setData, post, processing, errors } = useForm({
        token,
        email: email ?? '',
        password: '',
        password_confirmation: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/reset-password');
    }

    return (
        <AuthLayout title="Đặt lại mật khẩu">
            <Head title="Đặt lại mật khẩu" />
            <form onSubmit={submit}>
                <Form.Item label="Email" validateStatus={errors.email ? 'error' : ''} help={errors.email}>
                    <Input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} />
                </Form.Item>
                <Form.Item label="Mật khẩu mới" validateStatus={errors.password ? 'error' : ''} help={errors.password}>
                    <Input.Password
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        autoFocus
                    />
                </Form.Item>
                <Form.Item label="Xác nhận mật khẩu mới">
                    <Input.Password
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                    />
                </Form.Item>
                <Button type="primary" htmlType="submit" loading={processing} block>
                    Đặt lại mật khẩu
                </Button>
            </form>
        </AuthLayout>
    );
}
