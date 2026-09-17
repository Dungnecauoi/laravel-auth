import { useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function UpdateProfileInformationForm({
    user,
    emailVerified,
}: {
    user: { name: string; email: string };
    emailVerified: boolean;
}) {
    const { data, setData, patch, processing, errors } = useForm({
        name: user.name,
        email: user.email,
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        patch('/profile');
    }

    return (
        <form onSubmit={submit} className="flex max-w-md flex-col gap-4">
            <div className="flex flex-col gap-1.5">
                <Label htmlFor="name">Họ tên</Label>
                <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} />
                {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
            </div>
            <div className="flex flex-col gap-1.5">
                <Label htmlFor="email">Email</Label>
                <Input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} />
                {errors.email && <p className="text-sm text-destructive">{errors.email}</p>}
                {!emailVerified && <p className="text-sm text-amber-600">Email chưa được xác minh.</p>}
            </div>
            <Button type="submit" disabled={processing} className="w-fit">
                Lưu
            </Button>
        </form>
    );
}
