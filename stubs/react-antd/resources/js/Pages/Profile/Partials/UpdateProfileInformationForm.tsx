import { useForm } from '@inertiajs/react';
import { Button, Form, Input, Typography } from 'antd';

export default function UpdateProfileInformationForm({
    user,
    emailVerified,
}: {
    user: { name: string; email: string };
    emailVerified: boolean;
}) {
    const { data, setData, patch, processing, errors } = useForm({
        name: user.name,
        email: user.email,
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        patch('/profile');
    }

    return (
        <form onSubmit={submit} style={{ maxWidth: 400 }}>
            <Form.Item label="Họ tên" validateStatus={errors.name ? 'error' : ''} help={errors.name}>
                <Input value={data.name} onChange={(e) => setData('name', e.target.value)} />
            </Form.Item>
            <Form.Item label="Email" validateStatus={errors.email ? 'error' : ''} help={errors.email}>
                <Input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} />
                {!emailVerified && (
                    <Typography.Text type="warning" style={{ display: 'block', marginTop: 4 }}>
                        Email chưa được xác minh.
                    </Typography.Text>
                )}
            </Form.Item>
            <Button type="primary" htmlType="submit" loading={processing}>
                Lưu
            </Button>
        </form>
    );
}
