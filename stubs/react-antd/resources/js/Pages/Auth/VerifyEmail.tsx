import { Head, router, useForm } from '@inertiajs/react';
import { Alert, Button, Typography } from 'antd';
import AuthLayout from '@/Layouts/AuthLayout';

export default function VerifyEmail({ status }: { status?: string }) {
    const { post, processing } = useForm({});

    function resend(e: React.FormEvent) {
        e.preventDefault();
        post('/email/verification-notification');
    }

    function logout(e: React.FormEvent) {
        e.preventDefault();
        router.post('/logout');
    }

    return (
        <AuthLayout title="Xác minh email">
            <Head title="Xác minh email" />
            <Typography.Paragraph type="secondary">
                Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác minh địa chỉ email bằng liên kết chúng tôi vừa
                gửi. Nếu chưa nhận được email, chúng tôi sẵn sàng gửi lại.
            </Typography.Paragraph>
            {status && <Alert type="success" message={status} style={{ marginBottom: 16 }} />}
            <form onSubmit={resend} style={{ display: 'flex', justifyContent: 'space-between' }}>
                <Button type="primary" htmlType="submit" loading={processing}>
                    Gửi lại email xác minh
                </Button>
            </form>
            <form onSubmit={logout} style={{ marginTop: 16 }}>
                <Button type="link" htmlType="submit" style={{ padding: 0 }}>
                    Đăng xuất
                </Button>
            </form>
        </AuthLayout>
    );
}
