import { Head } from '@inertiajs/react';
import { Alert, Card, Space, Typography } from 'antd';
import AdminLayout from '@/Layouts/AdminLayout';
import DeleteUserForm from './Partials/DeleteUserForm';
import SessionList from './Partials/SessionList';
import TwoFactorAuthenticationForm from './Partials/TwoFactorAuthenticationForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';

interface Session {
    id: string;
    is_current_device: boolean;
    ip_address: string | null;
    user_agent: string | null;
    last_active: string;
}

interface Props {
    user: { name: string; email: string };
    emailVerified: boolean;
    sessions: Session[];
    twoFactorEnabled: boolean;
    twoFactorPending: boolean;
    twoFactorQrCodeSvg: string | null;
    recoveryCodes: string[] | null;
    status?: string;
    twoFactorAvailable?: boolean;
    sessionManagementAvailable?: boolean;
}

export default function Edit({
    user,
    emailVerified,
    sessions,
    twoFactorEnabled,
    twoFactorPending,
    twoFactorQrCodeSvg,
    recoveryCodes,
    status,
    twoFactorAvailable = true,
    sessionManagementAvailable = true,
}: Props) {
    return (
        <AdminLayout>
            <Head title="Hồ sơ" />
            <Typography.Title level={2}>Hồ sơ</Typography.Title>
            {status && <Alert type="success" message={status} style={{ marginBottom: 16 }} />}
            <Space direction="vertical" size="large" style={{ width: '100%' }}>
                <Card title="Thông tin hồ sơ">
                    <UpdateProfileInformationForm user={user} emailVerified={emailVerified} />
                </Card>

                <Card title="Đổi mật khẩu">
                    <UpdatePasswordForm />
                </Card>

                {twoFactorAvailable && (
                    <Card title="Xác thực hai yếu tố">
                        <TwoFactorAuthenticationForm
                            twoFactorEnabled={twoFactorEnabled}
                            twoFactorPending={twoFactorPending}
                            twoFactorQrCodeSvg={twoFactorQrCodeSvg}
                            recoveryCodes={recoveryCodes}
                        />
                    </Card>
                )}

                {sessionManagementAvailable && sessions.length > 0 && (
                    <Card title="Phiên đăng nhập">
                        <SessionList sessions={sessions} />
                    </Card>
                )}

                <Card title="Xoá tài khoản">
                    <DeleteUserForm />
                </Card>
            </Space>
        </AdminLayout>
    );
}
