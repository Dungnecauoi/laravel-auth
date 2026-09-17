import { useForm } from '@inertiajs/react';
import { Button, Form, Input, Modal, Typography } from 'antd';
import { useState } from 'react';

export default function DeleteUserForm() {
    const [open, setOpen] = useState(false);
    const { data, setData, delete: destroy, processing, errors, reset } = useForm({ password: '' });

    function confirmDelete(e: React.FormEvent) {
        e.preventDefault();
        destroy('/profile', {
            onSuccess: () => setOpen(false),
            onError: () => {}, // keep the modal open so the error shows
        });
    }

    return (
        <div>
            <Typography.Paragraph type="secondary" style={{ maxWidth: 480 }}>
                Một khi tài khoản bị xoá, toàn bộ dữ liệu sẽ bị xoá vĩnh viễn. Vui lòng tải xuống dữ liệu bạn muốn
                giữ lại trước khi tiếp tục.
            </Typography.Paragraph>
            <Button
                danger
                onClick={() => {
                    reset();
                    setOpen(true);
                }}
            >
                Xoá tài khoản
            </Button>

            <Modal
                title="Xoá tài khoản"
                open={open}
                onCancel={() => setOpen(false)}
                footer={[
                    <Button key="cancel" onClick={() => setOpen(false)}>
                        Huỷ
                    </Button>,
                    <Button key="confirm" danger loading={processing} onClick={confirmDelete}>
                        Xác nhận xoá
                    </Button>,
                ]}
            >
                <p>Nhập mật khẩu để xác nhận.</p>
                <Form.Item validateStatus={errors.password ? 'error' : ''} help={errors.password}>
                    <Input.Password
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        autoFocus
                    />
                </Form.Item>
            </Modal>
        </div>
    );
}
