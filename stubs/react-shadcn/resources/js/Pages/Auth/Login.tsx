import { Head, Link, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function Login({ canRegister, canResetPassword }: { canRegister: boolean; canResetPassword: boolean }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/login');
    }

    return (
        <AuthLayout title="Đăng nhập">
            <Head title="Đăng nhập" />
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
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="password">Mật khẩu</Label>
                    <Input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                    />
                    {errors.password && <p className="text-sm text-destructive">{errors.password}</p>}
                </div>
                <div className="flex items-center gap-2">
                    <Checkbox
                        id="remember"
                        checked={data.remember}
                        onCheckedChange={(checked) => setData('remember', checked === true)}
                    />
                    <Label htmlFor="remember">Ghi nhớ đăng nhập</Label>
                </div>
                <Button type="submit" disabled={processing} className="w-full">
                    Đăng nhập
                </Button>
                <div className="flex justify-between text-sm">
                    {canRegister && (
                        <Link href="/register" className="underline underline-offset-4">
                            Đăng ký tài khoản
                        </Link>
                    )}
                    {canResetPassword && (
                        <Link href="/forgot-password" className="underline underline-offset-4">
                            Quên mật khẩu?
                        </Link>
                    )}
                </div>
            </form>
        </AuthLayout>
    );
}
