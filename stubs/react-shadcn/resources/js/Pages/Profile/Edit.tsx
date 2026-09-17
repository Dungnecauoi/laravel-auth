import { Head } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
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
            <h2 className="mb-4 text-2xl font-semibold">Hồ sơ</h2>
            {status && (
                <Alert className="mb-4">
                    <AlertDescription>{status}</AlertDescription>
                </Alert>
            )}
            <div className="flex flex-col gap-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Thông tin hồ sơ</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <UpdateProfileInformationForm user={user} emailVerified={emailVerified} />
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Đổi mật khẩu</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <UpdatePasswordForm />
                    </CardContent>
                </Card>

                {twoFactorAvailable && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Xác thực hai yếu tố</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <TwoFactorAuthenticationForm
                                twoFactorEnabled={twoFactorEnabled}
                                twoFactorPending={twoFactorPending}
                                twoFactorQrCodeSvg={twoFactorQrCodeSvg}
                                recoveryCodes={recoveryCodes}
                            />
                        </CardContent>
                    </Card>
                )}

                {sessionManagementAvailable && sessions.length > 0 && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Phiên đăng nhập</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <SessionList sessions={sessions} />
                        </CardContent>
                    </Card>
                )}

                <Card>
                    <CardHeader>
                        <CardTitle>Xoá tài khoản</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <DeleteUserForm />
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}
