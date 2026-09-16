import { Head, Link, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/register');
    }

    return (
        <AuthLayout title="Đăng ký">
            <Head title="Đăng ký" />
            <form onSubmit={submit} className="flex flex-col gap-4">
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="name">Họ tên</Label>
                    <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} autoFocus />
                    {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                </div>
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
                    <Label htmlFor="password">Mật khẩu</Label>
                    <Input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                    />
                    {errors.password && <p className="text-sm text-destructive">{errors.password}</p>}
                </div>
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="password_confirmation">Xác nhận mật khẩu</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                    />
                </div>
                <Button type="submit" disabled={processing} className="w-full">
                    Đăng ký
                </Button>
                <Link href="/login" className="text-sm underline underline-offset-4">
                    Đã có tài khoản? Đăng nhập
                </Link>
            </form>
        </AuthLayout>
    );
}
