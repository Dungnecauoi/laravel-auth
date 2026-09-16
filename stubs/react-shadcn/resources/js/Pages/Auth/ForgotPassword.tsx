import { Head, Link, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({ email: '' });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/forgot-password');
    }

    return (
        <AuthLayout title="Quên mật khẩu">
            <Head title="Quên mật khẩu" />
            {status && (
                <Alert className="mb-4">
                    <AlertDescription>{status}</AlertDescription>
                </Alert>
            )}
            <form onSubmit={submit} className="flex flex-col gap-4">
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        autoFocus
                    />
                    {errors.email && <p className="text-sm text-destructive">{errors.email}</p>}
                </div>
                <Button type="submit" disabled={processing} className="w-full">
                    Gửi liên kết đặt lại mật khẩu
                </Button>
                <Link href="/login" className="text-sm underline underline-offset-4">
                    Quay lại đăng nhập
                </Link>
            </form>
        </AuthLayout>
    );
}
