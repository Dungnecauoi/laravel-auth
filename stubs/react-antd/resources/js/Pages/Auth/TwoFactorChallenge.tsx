import { Head, useForm } from '@inertiajs/react';
import { Button, Form, Input, Typography } from 'antd';
import { useState } from 'react';
import AuthLayout from '@/Layouts/AuthLayout';

export default function TwoFactorChallenge() {
    const [useRecoveryCode, setUseRecoveryCode] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({
        code: '',
        recovery_code: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/two-factor-challenge');
    }

    function toggleRecoveryCode() {
        reset();
        setUseRecoveryCode((prev) => !prev);
    }

    return (
        <AuthLayout title="Xác thực hai lớp">
            <Head title="Xác thực hai lớp" />
            <Typography.Paragraph type="secondary">
                {useRecoveryCode
                    ? 'Nhập một trong các mã khôi phục của bạn.'
                    : 'Nhập mã xác thực từ ứng dụng authenticator của bạn.'}
            </Typography.Paragraph>
            <form onSubmit={submit}>
                {useRecoveryCode ? (
                    <Form.Item
                        label="Mã khôi phục"
                        validateStatus={errors.code ? 'error' : ''}
                        help={errors.code}
                    >
                        <Input
                            value={data.recovery_code}
                            onChange={(e) => setData('recovery_code', e.target.value)}
                            autoFocus
                        />
                    </Form.Item>
                ) : (
                    <Form.Item label="Mã xác thực" validateStatus={errors.code ? 'error' : ''} help={errors.code}>
                        <Input value={data.code} onChange={(e) => setData('code', e.target.value)} autoFocus />
                    </Form.Item>
                )}
                <Button type="primary" htmlType="submit" loading={processing} block>
                    Xác nhận
                </Button>
                <div style={{ marginTop: 16 }}>
                    <a onClick={toggleRecoveryCode}>
                        {useRecoveryCode ? 'Dùng mã xác thực thay vì mã khôi phục' : 'Dùng mã khôi phục thay vì mã xác thực'}
                    </a>
                </div>
            </form>
        </AuthLayout>
    );
}
