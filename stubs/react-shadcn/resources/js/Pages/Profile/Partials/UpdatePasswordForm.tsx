import { useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function UpdatePasswordForm() {
    const { data, setData, put, processing, errors, reset } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        put('/password', { onSuccess: () => reset() });
    }

    return (
        <form onSubmit={submit} className="flex max-w-md flex-col gap-4">
            <div className="flex flex-col gap-1.5">
                <Label htmlFor="current_password">Mật khẩu hiện tại</Label>
                <Input
                    id="current_password"
                    type="password"
                    value={data.current_password}
                    onChange={(e) => setData('current_password', e.target.value)}
                />
                {errors.current_password && <p className="text-sm text-destructive">{errors.current_password}</p>}
            </div>
            <div className="flex flex-col gap-1.5">
                <Label htmlFor="password">Mật khẩu mới</Label>
                <Input
                    id="password"
                    type="password"
                    value={data.password}
                    onChange={(e) => setData('password', e.target.value)}
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
            <Button type="submit" disabled={processing} className="w-fit">
                Lưu
            </Button>
        </form>
    );
}
