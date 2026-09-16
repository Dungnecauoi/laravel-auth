import { Head, Link, useForm } from '@inertiajs/react';
import { Button, Form, Input } from 'antd';
import AuthLayout from '@/Layouts/AuthLayout';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/register');
    }

    return (
        <AuthLayout title="Đăng ký">
            <Head title="Đăng ký" />
            <form onSubmit={submit}>
                <Form.Item label="Họ tên" validateStatus={errors.name ? 'error' : ''} help={errors.name}>
                    <Input value={data.name} onChange={(e) => setData('name', e.target.value)} autoFocus />
                </Form.Item>
                <Form.Item label="Email" validateStatus={errors.email ? 'error' : ''} help={errors.email}>
                    <Input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} />
                </Form.Item>
                <Form.Item label="Mật khẩu" validateStatus={errors.password ? 'error' : ''} help={errors.password}>
                    <Input.Password value={data.password} onChange={(e) => setData('password', e.target.value)} />
                </Form.Item>
                <Form.Item label="Xác nhận mật khẩu">
                    <Input.Password
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                    />
                </Form.Item>
                <Button type="primary" htmlType="submit" loading={processing} block>
                    Đăng ký
                </Button>
                <div style={{ marginTop: 16 }}>
                    <Link href="/login">Đã có tài khoản? Đăng nhập</Link>
                </div>
            </form>
        </AuthLayout>
    );
}
