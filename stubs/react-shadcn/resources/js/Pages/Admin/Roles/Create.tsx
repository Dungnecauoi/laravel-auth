import { useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Permission {
    id: number;
    name: string;
    label: string | null;
}

export default function Create({ permissions }: { permissions: Permission[] }) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        label: '',
        permissions: [] as number[],
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/admin/roles');
    }

    function togglePermission(id: number, checked: boolean) {
        setData('permissions', checked ? [...data.permissions, id] : data.permissions.filter((p) => p !== id));
    }

    return (
        <AdminLayout>
            <h2 className="mb-4 text-2xl font-semibold">Thêm vai trò</h2>
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
                <div className="flex flex-col gap-2">
                    <Label>Quyền</Label>
                    {permissions.length === 0 ? (
                        <p className="text-sm text-muted-foreground">Chưa có quyền nào — tạo ở mục Quyền.</p>
                    ) : (
                        <div className="flex flex-wrap gap-4">
                            {permissions.map((permission) => (
                                <div key={permission.id} className="flex items-center gap-2">
                                    <Checkbox
                                        id={`permission-${permission.id}`}
                                        checked={data.permissions.includes(permission.id)}
                                        onCheckedChange={(checked) => togglePermission(permission.id, checked === true)}
                                    />
                                    <Label htmlFor={`permission-${permission.id}`}>{permission.label ?? permission.name}</Label>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
                <Button type="submit" disabled={processing} className="w-fit">
                    Lưu
                </Button>
            </form>
        </AdminLayout>
    );
}
