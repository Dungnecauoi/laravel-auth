import { useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface PermissionData {
    id: number;
    name: string;
    label: string | null;
}

export default function Edit({ permission }: { permission: PermissionData }) {
    const { data, setData, put, processing, errors } = useForm({
        name: permission.name,
        label: permission.label ?? '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        put(`/admin/permissions/${permission.id}`);
    }

    return (
        <AdminLayout>
            <h2 className="mb-4 text-2xl font-semibold">Sửa quyền</h2>
            <form onSubmit={submit} className="flex max-w-md flex-col gap-4">
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="name">Tên</Label>
                    <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} autoFocus />
                    {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                </div>
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="label">Nhãn hiển thị</Label>
                    <Input id="label" value={data.label} onChange={(e) => setData('label', e.target.value)} />
                    {errors.label && <p className="text-sm text-destructive">{errors.label}</p>}
                </div>
                <Button type="submit" disabled={processing} className="w-fit">
                    Lưu
                </Button>
            </form>
        </AdminLayout>
    );
}
