import { router, useForm } from '@inertiajs/react';
import { Alert, Button, Form, Input, Space, Tag } from 'antd';

interface Props {
    twoFactorEnabled: boolean;
    twoFactorPending: boolean;
    twoFactorQrCodeSvg: string | null;
    recoveryCodes: string[] | null;
}

export default function TwoFactorAuthenticationForm({
    twoFactorEnabled,
    twoFactorPending,
    twoFactorQrCodeSvg,
    recoveryCodes,
}: Props) {
    const { data, setData, post, processing, errors } = useForm({ code: '' });

    function enable(e: React.FormEvent) {
        e.preventDefault();
        router.post('/user/two-factor-authentication');
    }

    function confirm(e: React.FormEvent) {
        e.preventDefault();
        post('/user/confirmed-two-factor-authentication');
    }

    function disable(e: React.FormEvent) {
        e.preventDefault();
        router.delete('/user/two-factor-authentication');
    }

    function regenerateRecoveryCodes(e: React.FormEvent) {
        e.preventDefault();
        router.post('/user/two-factor-recovery-codes');
    }

    return (
        <div>
            {recoveryCodes && recoveryCodes.length > 0 && (
                <Alert
                    type="warning"
                    message="Lưu lại các mã khôi phục này ở nơi an toàn"
                    description={
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 4, fontFamily: 'monospace', fontSize: 12, marginTop: 8 }}>
                            {recoveryCodes.map((code) => (
                                <span key={code}>{code}</span>
                            ))}
                        </div>
                    }
                    style={{ marginBottom: 16 }}
                />
            )}

            {twoFactorEnabled ? (
                <div>
                    <Tag color="success">Đã bật</Tag>
                    <Space style={{ marginTop: 16 }}>
                        <form onSubmit={regenerateRecoveryCodes}>
                            <Button htmlType="submit">Tạo lại mã khôi phục</Button>
                        </form>
                        <form onSubmit={disable}>
                            <Button htmlType="submit" danger>
                                Tắt xác thực hai yếu tố
                            </Button>
                        </form>
                    </Space>
                </div>
            ) : twoFactorPending ? (
                <div style={{ display: 'flex', gap: 24, alignItems: 'flex-start', flexWrap: 'wrap' }}>
                    <div
                        style={{ border: '1px solid #f0f0f0', borderRadius: 8, padding: 12 }}
                        // Server-generated SVG from pragmarx/google2fa + bacon/bacon-qr-code —
                        // same trust boundary as Blade's {!! $user->twoFactorQrCodeSvg() !!}.
                        dangerouslySetInnerHTML={{ __html: twoFactorQrCodeSvg ?? '' }}
                    />
                    <form onSubmit={confirm} style={{ maxWidth: 280, width: '100%' }}>
                        <Form.Item
                            label="Nhập mã xác thực để xác nhận"
                            validateStatus={errors.code ? 'error' : ''}
                            help={errors.code}
                        >
                            <Input
                                inputMode="numeric"
                                autoComplete="one-time-code"
                                value={data.code}
                                onChange={(e) => setData('code', e.target.value)}
                            />
                        </Form.Item>
                        <Button type="primary" htmlType="submit" loading={processing}>
                            Xác nhận
                        </Button>
                    </form>
                </div>
            ) : (
                <form onSubmit={enable}>
                    <Button htmlType="submit">Bật xác thực hai yếu tố</Button>
                </form>
            )}
        </div>
    );
}
