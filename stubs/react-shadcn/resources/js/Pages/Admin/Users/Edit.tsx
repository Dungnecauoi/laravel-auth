import { useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Role {
    id: number;
    name: string;
    label: string | null;
}

interface UserData {
    id: number;
    name: string;
    email: string;
    role_ids: number[];
}

export default function Edit({ user, roles }: { user: UserData; roles: Role[] }) {
    const { data, setData, put, processing, errors } = useForm({
        name: user.name,
        email: user.email,
        password: '',
        password_confirmation: '',
        roles: user.role_ids,
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        put(`/admin/users/${user.id}`);
    }

    function toggleRole(id: number, checked: boolean) {
        setData('roles', checked ? [...data.roles, id] : data.roles.filter((r) => r !== id));
    }

    return (
        <AdminLayout>
            <h2 className="mb-4 text-2xl font-semibold">Sửa người dùng</h2>
            <form onSubmit={submit} className="flex max-w-md flex-col gap-4">
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="name">Họ tên</Label>
                    <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} autoFocus />
                    {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                </div>
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="email">Email</Label>
                    <Input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} />
                    {errors.email && <p className="text-sm text-destructive">{errors.email}</p>}
                </div>
                <div className="flex flex-col gap-1.5">
                    <Label htmlFor="password">Mật khẩu (để trống nếu không đổi)</Label>
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
                <div className="flex flex-col gap-2">
                    <Label>Vai trò</Label>
                    {roles.length === 0 ? (
                        <p className="text-sm text-muted-foreground">Chưa có vai trò nào — tạo ở mục Vai trò.</p>
                    ) : (
                        <div className="flex flex-wrap gap-4">
                            {roles.map((role) => (
                                <div key={role.id} className="flex items-center gap-2">
                                    <Checkbox
                                        id={`role-${role.id}`}
                                        checked={data.roles.includes(role.id)}
                                        onCheckedChange={(checked) => toggleRole(role.id, checked === true)}
                                    />
                                    <Label htmlFor={`role-${role.id}`}>{role.label ?? role.name}</Label>
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
