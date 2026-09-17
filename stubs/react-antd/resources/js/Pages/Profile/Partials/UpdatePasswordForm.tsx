import { useForm } from '@inertiajs/react';
import { Button, Form, Input } from 'antd';

export default function UpdatePasswordForm() {
    const { data, setData, put, processing, errors, reset } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        put('/password', { onSuccess: () => reset() });
    }

    return (
        <form onSubmit={submit} style={{ maxWidth: 400 }}>
            <Form.Item
                label="Mật khẩu hiện tại"
                validateStatus={errors.current_password ? 'error' : ''}
                help={errors.current_password}
            >
                <Input.Password
                    value={data.current_password}
                    onChange={(e) => setData('current_password', e.target.value)}
                />
            </Form.Item>
            <Form.Item label="Mật khẩu mới" validateStatus={errors.password ? 'error' : ''} help={errors.password}>
                <Input.Password value={data.password} onChange={(e) => setData('password', e.target.value)} />
            </Form.Item>
            <Form.Item label="Xác nhận mật khẩu mới">
                <Input.Password
                    value={data.password_confirmation}
                    onChange={(e) => setData('password_confirmation', e.target.value)}
                />
            </Form.Item>
            <Button type="primary" htmlType="submit" loading={processing}>
                Lưu
            </Button>
        </form>
    );
}
