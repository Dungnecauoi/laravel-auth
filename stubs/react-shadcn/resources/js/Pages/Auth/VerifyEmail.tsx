import { Head, router, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';

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
            <p className="mb-4 text-sm text-muted-foreground">
                Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác minh địa chỉ email bằng liên kết chúng tôi vừa
                gửi. Nếu chưa nhận được email, chúng tôi sẵn sàng gửi lại.
            </p>
            {status && (
                <Alert className="mb-4">
                    <AlertDescription>{status}</AlertDescription>
                </Alert>
            )}
            <form onSubmit={resend} className="flex justify-between">
                <Button type="submit" disabled={processing}>
                    Gửi lại email xác minh
                </Button>
            </form>
            <form onSubmit={logout} className="mt-4">
                <button type="submit" className="text-sm underline underline-offset-4">
                    Đăng xuất
                </button>
            </form>
        </AuthLayout>
    );
}
