import { router, useForm } from '@inertiajs/react';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Props {
    twoFactorEnabled: boolean;
    twoFactorPending: boolean;
    twoFactorQrCodeSvg: string | null;
    recoveryCodes: string[] | null;
}

export default function TwoFactorAuthenticationForm({
    twoFactorEnabled,
    twoFactorPending,
    twoFactorQrCodeSvg,
    recoveryCodes,
}: Props) {
    const { data, setData, post, processing, errors } = useForm({ code: '' });

    function enable(e: React.FormEvent) {
        e.preventDefault();
        router.post('/user/two-factor-authentication');
    }

    function confirm(e: React.FormEvent) {
        e.preventDefault();
        post('/user/confirmed-two-factor-authentication');
    }

    function disable(e: React.FormEvent) {
        e.preventDefault();
        router.delete('/user/two-factor-authentication');
    }

    function regenerateRecoveryCodes(e: React.FormEvent) {
        e.preventDefault();
        router.post('/user/two-factor-recovery-codes');
    }

    return (
        <div>
            {recoveryCodes && recoveryCodes.length > 0 && (
                <Alert className="mb-4">
                    <AlertTitle>Lưu lại các mã khôi phục này ở nơi an toàn</AlertTitle>
                    <AlertDescription>
                        <div className="mt-2 grid grid-cols-2 gap-1 font-mono text-xs">
                            {recoveryCodes.map((code) => (
                                <span key={code}>{code}</span>
                            ))}
                        </div>
                    </AlertDescription>
                </Alert>
            )}

            {twoFactorEnabled ? (
                <div>
                    <Badge variant="success">Đã bật</Badge>
                    <div className="mt-4 flex flex-wrap gap-2">
                        <form onSubmit={regenerateRecoveryCodes}>
                            <Button type="submit" variant="secondary" size="sm">
                                Tạo lại mã khôi phục
                            </Button>
                        </form>
                        <form onSubmit={disable}>
                            <Button type="submit" variant="destructive" size="sm">
                                Tắt xác thực hai yếu tố
                            </Button>
                        </form>
                    </div>
                </div>
            ) : twoFactorPending ? (
                <div className="flex flex-wrap items-start gap-6">
                    <div
                        className="rounded-lg border p-3"
                        dangerouslySetInnerHTML={{ __html: twoFactorQrCodeSvg ?? '' }}
                    />
                    <form onSubmit={confirm} className="flex w-full max-w-xs flex-col gap-3">
                        <div className="flex flex-col gap-1.5">
                            <Label htmlFor="code">Nhập mã xác thực để xác nhận</Label>
                            <Input
                                id="code"
                                inputMode="numeric"
                                autoComplete="one-time-code"
                                value={data.code}
                                onChange={(e) => setData('code', e.target.value)}
                            />
                            {errors.code && <p className="text-sm text-destructive">{errors.code}</p>}
                        </div>
                        <Button type="submit" disabled={processing} className="w-fit">
                            Xác nhận
                        </Button>
                    </form>
                </div>
            ) : (
                <form onSubmit={enable}>
                    <Button type="submit">Bật xác thực hai yếu tố</Button>
                </form>
            )}
        </div>
    );
}
