import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function TwoFactorChallenge() {
    const [useRecoveryCode, setUseRecoveryCode] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({
        code: '',
        recovery_code: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/two-factor-challenge');
    }

    function toggleRecoveryCode() {
        reset();
        setUseRecoveryCode((prev) => !prev);
    }

    return (
        <AuthLayout title="Xác thực hai lớp">
            <Head title="Xác thực hai lớp" />
            <p className="mb-4 text-sm text-muted-foreground">
                {useRecoveryCode
                    ? 'Nhập một trong các mã khôi phục của bạn.'
                    : 'Nhập mã xác thực từ ứng dụng authenticator của bạn.'}
            </p>
            <form onSubmit={submit} className="flex flex-col gap-4">
                {useRecoveryCode ? (
                    <div className="flex flex-col gap-1.5">
                        <Label htmlFor="recovery_code">Mã khôi phục</Label>
                        <Input
                            id="recovery_code"
                            value={data.recovery_code}
                            onChange={(e) => setData('recovery_code', e.target.value)}
                            autoFocus
                        />
                        {errors.code && <p className="text-sm text-destructive">{errors.code}</p>}
                    </div>
                ) : (
                    <div className="flex flex-col gap-1.5">
                        <Label htmlFor="code">Mã xác thực</Label>
                        <Input id="code" value={data.code} onChange={(e) => setData('code', e.target.value)} autoFocus />
                        {errors.code && <p className="text-sm text-destructive">{errors.code}</p>}
                    </div>
                )}
                <Button type="submit" disabled={processing} className="w-full">
                    Xác nhận
                </Button>
                <button
                    type="button"
                    onClick={toggleRecoveryCode}
                    className="text-left text-sm underline underline-offset-4"
                >
                    {useRecoveryCode ? 'Dùng mã xác thực thay vì mã khôi phục' : 'Dùng mã khôi phục thay vì mã xác thực'}
                </button>
            </form>
        </AuthLayout>
    );
}
