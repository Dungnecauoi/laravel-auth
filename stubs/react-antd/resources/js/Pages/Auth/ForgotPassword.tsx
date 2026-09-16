import { Head, Link, useForm } from '@inertiajs/react';
import { Alert, Button, Form, Input } from 'antd';
import AuthLayout from '@/Layouts/AuthLayout';

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({ email: '' });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/forgot-password');
    }

    return (
        <AuthLayout title="Quên mật khẩu">
            <Head title="Quên mật khẩu" />
            {status && <Alert type="success" message={status} style={{ marginBottom: 16 }} />}
            <form onSubmit={submit}>
                <Form.Item label="Email" validateStatus={errors.email ? 'error' : ''} help={errors.email}>
                    <Input
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        autoFocus
                    />
                </Form.Item>
                <Button type="primary" htmlType="submit" loading={processing} block>
                    Gửi liên kết đặt lại mật khẩu
                </Button>
                <div style={{ marginTop: 16 }}>
                    <Link href="/login">Quay lại đăng nhập</Link>
                </div>
            </form>
        </AuthLayout>
    );
}
