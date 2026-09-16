import { Head, Link, useForm } from '@inertiajs/react';
import { Button, Checkbox, Form, Input, Typography } from 'antd';
import AuthLayout from '@/Layouts/AuthLayout';

export default function Login({ canRegister, canResetPassword }: { canRegister: boolean; canResetPassword: boolean }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/login');
    }

    return (
        <AuthLayout title="Đăng nhập">
            <Head title="Đăng nhập" />
            <form onSubmit={submit}>
                <Form.Item label="Email" validateStatus={errors.email ? 'error' : ''} help={errors.email}>
                    <Input
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        autoFocus
                    />
                </Form.Item>
                <Form.Item label="Mật khẩu" validateStatus={errors.password ? 'error' : ''} help={errors.password}>
                    <Input.Password value={data.password} onChange={(e) => setData('password', e.target.value)} />
                </Form.Item>
                <Form.Item>
                    <Checkbox checked={data.remember} onChange={(e) => setData('remember', e.target.checked)}>
                        Ghi nhớ đăng nhập
                    </Checkbox>
                </Form.Item>
                <Button type="primary" htmlType="submit" loading={processing} block>
                    Đăng nhập
                </Button>
                <div style={{ marginTop: 16, display: 'flex', justifyContent: 'space-between' }}>
                    {canRegister && <Link href="/register">Đăng ký tài khoản</Link>}
                    {canResetPassword && <Link href="/forgot-password">Quên mật khẩu?</Link>}
                </div>
            </form>
        </AuthLayout>
    );
}
