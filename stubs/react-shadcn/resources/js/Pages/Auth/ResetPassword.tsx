import { Head, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function ResetPassword({ email, token }: { email: string | null; token: string }) {
    const { data, setData, post, processing, errors } = useForm({
        token,
        email: email ?? '',
        password: '',
        password_confirmation: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/reset-password');
    }

    return (
        <AuthLayout title="Đặt lại mật khẩu">
            <Head title="Đặt lại mật khẩu" />
            <form onSubmit={submit} className="flex flex-col gap-4">
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    {errors.email && <p className="text-sm text-destructive">{errors.email}</p>}
                </div>
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="password">Mật khẩu mới</Label>
                    <Input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        autoFocus
                    />
                    {errors.password && <p className="text-sm text-destructive">{errors.password}</p>}
                </div>
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="password_confirmation">Xác nhận mật khẩu mới</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                    />
                </div>
                <Button type="submit" disabled={processing} className="w-full">
                    Đặt lại mật khẩu
                </Button>
            </form>
        </AuthLayout>
    );
}
